<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Auth;

class PerformaBisnisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user() || (Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager')) {
            return redirect()->route('produk')->with('error', 'Access denied');
        }

        // Mendapatkan bulan, tahun, dan jenis tampilan dari request
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);
        $viewType = $request->input('view_type', 'monthly'); // Pilihan tampilan (monthly / yearly)

        if ($viewType == 'yearly') {
            // Keuntungan per toko untuk seluruh tahun
            $keuntungan = Transaksi::with('toko')
                ->selectRaw('idToko, SUM(detail_transaksi.jumlahProduk * 
                    CASE 
                        WHEN transaksi.tipePembayaran = "cash" THEN (produk.hargaCash - produk.hargaBeli)
                        WHEN transaksi.tipePembayaran = "tempo" THEN (produk.hargaTempo - produk.hargaBeli)
                    END) AS totalKeuntungan')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
                ->whereYear('transaksi.tanggalTransaksi', $tahun)
                ->whereNull('transaksi.deleted_at')  // Menambahkan pengecekan untuk transaksi yang tidak dihapus
                ->groupBy('idToko')
                ->get();

            // Produk terlaris untuk seluruh tahun
            $produkTerlaris = DetailTransaksi::with('produk')
                ->selectRaw('idProduk, SUM(jumlahProduk) AS totalTerjual')
                ->whereHas('transaksi', function ($query) use ($tahun) {
                    $query->whereYear('tanggalTransaksi', $tahun)
                        ->whereNull('transaksi.deleted_at');  // Menambahkan pengecekan untuk transaksi yang tidak dihapus
                })
                ->groupBy('idProduk')
                ->orderBy('totalTerjual', 'desc')
                ->limit(10)
                ->get();

            // Toko dengan pembelian terbanyak untuk seluruh tahun
            $tokoBanyakPembelian = Transaksi::selectRaw('transaksi.idToko, toko.namaToko, SUM(detail_transaksi.jumlahProduk) AS totalPembelian')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('toko', 'transaksi.idToko', '=', 'toko.idToko')
                ->whereYear('transaksi.tanggalTransaksi', $tahun)
                ->whereNull('transaksi.deleted_at')  // Menambahkan pengecekan untuk transaksi yang tidak dihapus
                ->groupBy('transaksi.idToko', 'toko.namaToko')
                ->orderBy('totalPembelian', 'desc')
                ->get();

            $produkTerlarisPerToko = DB::table('transaksi')
                ->selectRaw('toko.namaToko, produk.namaProduk, MAX(totalTerjual) AS totalTerjual')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
                ->join('toko', 'transaksi.idToko', '=', 'toko.idToko')
                ->joinSub(
                    DB::table('detail_transaksi')
                        ->selectRaw('transaksi.idToko, detail_transaksi.idProduk, SUM(jumlahProduk) AS totalTerjual')
                        ->join('transaksi', 'detail_transaksi.idTransaksi', '=', 'transaksi.idTransaksi')
                        ->whereYear('transaksi.tanggalTransaksi', $tahun)
                        ->whereNull('transaksi.deleted_at')
                        ->groupBy('transaksi.idToko', 'detail_transaksi.idProduk'),
                    'subquery',
                    function ($join) {
                        $join->on('transaksi.idToko', '=', 'subquery.idToko')
                            ->on('detail_transaksi.idProduk', '=', 'subquery.idProduk');
                    }
                )
                ->whereNull('transaksi.deleted_at')
                ->groupBy('toko.namaToko', 'produk.namaProduk', 'transaksi.idToko')
                ->orderBy('toko.namaToko')
                ->orderByDesc('totalTerjual')
                ->get()
                ->groupBy('namaToko') // Mengelompokkan berdasarkan toko
                ->map(function ($group) {
                    // Hanya ambil produk pertama (dengan jumlah totalTerjual tertinggi) dari setiap toko
                    return $group->first();
                })
                ->values(); // Mengembalikan array dengan indeks terurut

            // Gudang yang sering digunakan untuk pembelian berdasarkan detail transaksi
            // Menghitung pengeluaran per gudang berdasarkan detail transaksi
            $gudangPengeluaranTerbesar = DB::table('detail_transaksi')
                ->selectRaw('
            detail_transaksi.idGudang, 
            gudang.namaGudang, 
            SUM(detail_transaksi.jumlahProduk) AS totalStokKeluar
        ')
                ->join('gudang', 'detail_transaksi.idGudang', '=', 'gudang.idGudang') // Relasi ke tabel gudang
                ->join('transaksi', 'detail_transaksi.idTransaksi', '=', 'transaksi.idTransaksi') // Relasi ke transaksi untuk filter tanggal
                ->whereYear('transaksi.tanggalTransaksi', $tahun) // Filter tahun
                ->whereNull('transaksi.deleted_at') // Pastikan transaksi tidak dihapus
                ->groupBy('detail_transaksi.idGudang', 'gudang.namaGudang') // Kelompokkan berdasarkan gudang
                ->orderByDesc('totalStokKeluar') // Urutkan dari stok terbanyak ke terkecil
                ->get();
        } else {
            // Keuntungan per toko berdasarkan bulan dan tahun
            $keuntungan = Transaksi::with('toko')
                ->selectRaw('idToko, SUM(detail_transaksi.jumlahProduk * 
                CASE 
                    WHEN transaksi.tipePembayaran = "cash" THEN (produk.hargaCash - produk.hargaBeli)
                    WHEN transaksi.tipePembayaran = "tempo" THEN (produk.hargaTempo - produk.hargaBeli)
                END) AS totalKeuntungan')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
                ->whereYear('transaksi.tanggalTransaksi', $tahun)
                ->whereMonth('transaksi.tanggalTransaksi', $bulan)
                ->whereNull('produk.deleted_at')  // Pastikan produk tidak terhapus secara soft delete
                ->groupBy('idToko')
                ->get();

            // Produk terlaris berdasarkan bulan dan tahun
            $produkTerlaris = DetailTransaksi::with('produk')
                ->selectRaw('idProduk, SUM(jumlahProduk) AS totalTerjual')
                ->whereHas('transaksi', function ($query) use ($bulan, $tahun) {
                    $query->whereYear('tanggalTransaksi', $tahun)
                        ->whereMonth('tanggalTransaksi', $bulan)
                        ->whereNull('transaksi.deleted_at');  // Pengecekan transaksi yang belum dihapus secara soft delete
                })
                ->whereHas('produk', function ($query) {
                    $query->whereNull('deleted_at'); // Pastikan produk tidak terhapus secara soft delete
                })
                ->groupBy('idProduk')
                ->orderBy('totalTerjual', 'desc')
                ->limit(10)
                ->get();

            $tokoBanyakPembelian = Transaksi::selectRaw('transaksi.idToko, toko.namaToko, SUM(detail_transaksi.jumlahProduk) AS totalPembelian')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('toko', 'transaksi.idToko', '=', 'toko.idToko')
                ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk') // Join produk to check if it's deleted
                ->whereYear('transaksi.tanggalTransaksi', $tahun)
                ->whereMonth('transaksi.tanggalTransaksi', $bulan)
                ->whereNull('toko.deleted_at') // Pastikan toko tidak terhapus secara soft delete
                ->whereNull('produk.deleted_at') // Pastikan produk tidak terhapus secara soft delete
                ->groupBy('transaksi.idToko', 'toko.namaToko')
                ->orderBy('totalPembelian', 'desc')
                ->get();

            $produkTerlarisPerToko = DB::table('transaksi')
                ->selectRaw('toko.namaToko, produk.namaProduk, MAX(totalTerjual) AS totalTerjual')
                ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
                ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
                ->join('toko', 'transaksi.idToko', '=', 'toko.idToko')
                ->joinSub(
                    DB::table('detail_transaksi')
                        ->selectRaw('transaksi.idToko, detail_transaksi.idProduk, SUM(jumlahProduk) AS totalTerjual')
                        ->join('transaksi', 'detail_transaksi.idTransaksi', '=', 'transaksi.idTransaksi')
                        ->whereYear('transaksi.tanggalTransaksi', $tahun)
                        ->when($viewType == 'monthly', function ($query) use ($bulan) {
                            $query->whereMonth('transaksi.tanggalTransaksi', $bulan);
                        })
                        ->whereNull('transaksi.deleted_at')
                        ->groupBy('transaksi.idToko', 'detail_transaksi.idProduk'),
                    'subquery',
                    function ($join) {
                        $join->on('transaksi.idToko', '=', 'subquery.idToko')
                            ->on('detail_transaksi.idProduk', '=', 'subquery.idProduk');
                    }
                )
                ->whereNull('transaksi.deleted_at')
                ->groupBy('toko.namaToko', 'produk.namaProduk', 'transaksi.idToko')
                ->orderBy('toko.namaToko')
                ->orderByDesc('totalTerjual')
                ->get()
                ->groupBy('namaToko') // Mengelompokkan berdasarkan toko
                ->map(function ($group) {
                    // Hanya ambil produk pertama (dengan jumlah totalTerjual tertinggi) dari setiap toko
                    return $group->first();
                })
                ->values(); // Mengembalikan array dengan indeks terurut

            // Gudang yang sering digunakan untuk pembelian berdasarkan detail transaksi
            // Menghitung pengeluaran per gudang berdasarkan detail transaksi
            $gudangPengeluaranTerbesar = DB::table('detail_transaksi')
                ->selectRaw('
            detail_transaksi.idGudang, 
            gudang.namaGudang, 
            SUM(detail_transaksi.jumlahProduk) AS totalStokKeluar
        ')
                ->join('gudang', 'detail_transaksi.idGudang', '=', 'gudang.idGudang') // Relasi ke tabel gudang
                ->join('transaksi', 'detail_transaksi.idTransaksi', '=', 'transaksi.idTransaksi') // Relasi ke transaksi untuk filter tanggal
                ->whereYear('transaksi.tanggalTransaksi', $tahun) // Filter tahun
                ->when($viewType == 'monthly', function ($query) use ($bulan) {
                    $query->whereMonth('transaksi.tanggalTransaksi', $bulan); // Filter bulan (jika monthly)
                })
                ->whereNull('transaksi.deleted_at') // Pastikan transaksi tidak dihapus
                ->groupBy('detail_transaksi.idGudang', 'gudang.namaGudang') // Kelompokkan berdasarkan gudang
                ->orderByDesc('totalStokKeluar') // Urutkan dari stok terbanyak ke terkecil
                ->get();
        }

        // Menampilkan data pada view
        return view('performa_bisnis', compact('keuntungan', 'produkTerlaris', 'tokoBanyakPembelian', 'produkTerlarisPerToko', 'gudangPengeluaranTerbesar', 'bulan', 'tahun', 'viewType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
