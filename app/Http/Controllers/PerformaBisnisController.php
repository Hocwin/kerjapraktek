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
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager') {
            return redirect()->route('produk')->with('error', 'Access denied');
        }
        // Mengambil bulan dan tahun dari request atau menggunakan nilai default (bulan dan tahun saat ini)
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Keuntungan per toko
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
            ->groupBy('idToko')
            ->get();

        // Produk terlaris
        $produkTerlaris = DetailTransaksi::with('produk')
            ->selectRaw('idProduk, SUM(jumlahProduk) AS totalTerjual')
            ->whereHas('transaksi', function($query) use ($bulan, $tahun) {
                $query->whereYear('tanggalTransaksi', $tahun)
                    ->whereMonth('tanggalTransaksi', $bulan);
            })
            ->groupBy('idProduk')
            ->orderBy('totalTerjual', 'desc')
            ->limit(10)
            ->get();

        // Toko dengan pembelian terbanyak (limit 10)
        $tokoBanyakPembelian = Transaksi::selectRaw('transaksi.idToko, toko.namaToko, SUM(detail_transaksi.jumlahProduk) AS totalPembelian')
            ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
            ->join('toko', 'transaksi.idToko', '=', 'toko.idToko')  // Join dengan tabel toko untuk mendapatkan nama toko
            ->whereYear('transaksi.tanggalTransaksi', $tahun)
            ->whereMonth('transaksi.tanggalTransaksi', $bulan)
            ->groupBy('transaksi.idToko', 'toko.namaToko')
            ->orderBy('totalPembelian', 'desc')
            ->limit(10)  // Hanya ambil 10 toko teratas
            ->get();

        return view('performa_bisnis', compact('keuntungan', 'produkTerlaris', 'tokoBanyakPembelian', 'bulan', 'tahun'));
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
