<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Toko;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil filter dari query string
        $filterToko = $request->input('toko'); // Nama toko
        $filterBulan = $request->input('bulan'); // Bulan dalam format angka
        $filterTahun = $request->input('tahun'); // Tahun dalam format angka

        // Query dasar
        $query = Transaksi::with(['toko', 'detailTransaksi.produk']);

        // Filter berdasarkan nama toko
        if (!empty($filterToko)) {
            $query->whereHas('toko', function ($q) use ($filterToko) {
                $q->where('namaToko', 'like', '%' . $filterToko . '%');
            });
        }

        // Filter berdasarkan bulan dan tahun
        if (!empty($filterBulan) || !empty($filterTahun)) {
            $query->whereMonth('tanggalTransaksi', $filterBulan)
                ->whereYear('tanggalTransaksi', $filterTahun);
        }

        // Eksekusi query
        $transaksi = $query->get();

        // Ambil data toko untuk dropdown filter
        $toko = Toko::all();
        $toko = Toko::orderBy('namaToko', 'asc')->get();

        return view('transaksi', [
            'transaksi' => $transaksi,
            'toko' => $toko,
            'filterToko' => $filterToko,
            'filterBulan' => $filterBulan,
            'filterTahun' => $filterTahun,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $toko = Toko::all(); // Pastikan Anda sudah membuat model Toko
        $toko = Toko::orderBy('namaToko', 'asc')->get();
        return view('add_transaksi', ['toko' => $toko]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idToko' => 'required',
            'tipePembayaran' => 'required|in:cash,tempo',
            'tanggalTransaksi' => 'required|date_format:Y-m-d\TH:i',
            'produk.*.idProduk' => 'required|exists:produk,idProduk',
            'produk.*.jumlahProduk' => 'required|integer|min:1',
        ]);

        // Simpan transaksi
        $transaksi = new Transaksi();
        $transaksi->idToko = $request->idToko;
        $transaksi->tipePembayaran = $request->tipePembayaran;
        $transaksi->status = $request->status;
        $transaksi->tanggalTransaksi = $request->tanggalTransaksi;;
        $transaksi->save();

        // Setelah transaksi disimpan, lanjutkan ke DetailTransaksi
        return redirect()->route('detail_transaksi', ['idTransaksi' => $transaksi->idTransaksi]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::all()->map(function ($item) {
            // Format 'tanggalTransaksi' to show only hours and minutes
            $item->tanggalTransaksi = Carbon::parse($item->tanggalTransaksi)->format('Y-m-d\H:i'); // 'H:i' gives hour and minute in 24-hour format
            return $item;
        });

        return view('transaksi', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = Transaksi::with('toko')->find($id);
        $toko = Toko::all();

        if (!$transaksi) {
            return redirect()->route('transaksi')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('edit_transaksi', compact('transaksi', 'toko'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'idToko' => 'required|exists:toko,idToko',
            'tipePembayaran' => 'required|in:cash,tempo',
            'status' => 'required|in:lunas,belum lunas',
            'tanggalTransaksi' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return redirect()->route('transaksi')->with('error', 'Transaksi tidak ditemukan.');
        }

        $transaksi->idToko = $request->idToko;
        $transaksi->tipePembayaran = $request->tipePembayaran;
        $transaksi->status = $request->status;

        // Gunakan tanggal baru jika diubah, atau tetap gunakan tanggal lama
        $transaksi->tanggalTransaksi = $request->tanggalTransaksi ?: $transaksi->tanggalTransaksi;

        if ($transaksi->save()) {
            return redirect()->route('transaksi')->with('success', 'Transaksi berhasil diperbarui.');
        } else {
            return back()->with('error', 'Gagal memperbarui transaksi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idTransaksi)
    {
        $transaksi = Transaksi::find($idTransaksi);

        if ($transaksi) {
            // Hapus detail transaksi terkait
            $transaksi->detailTransaksi()->delete();

            // Hapus transaksi utama
            $transaksi->delete();

            return redirect()->back()->with('success', 'Transaksi dan detailnya berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
    }
}
