<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Toko;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with(['toko', 'detailTransaksi.produk'])->get();
        return view('transaksi', ['transaksi' => $transaksi]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $toko = Toko::all(); // Pastikan Anda sudah membuat model Toko
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
            'tanggalTransaksi' => 'required|date',
            'produk.*.idProduk' => 'required|exists:produk,idProduk',
            'produk.*.jumlahProduk' => 'required|integer|min:1',
        ]);

        // Simpan transaksi
        $transaksi = new Transaksi();
        $transaksi->idToko = $request->idToko;
        $transaksi->tipePembayaran = $request->tipePembayaran;
        $transaksi->status = $request->status;
        $transaksi->tanggalTransaksi = now();
        $transaksi->save();

        // Setelah transaksi disimpan, lanjutkan ke DetailTransaksi
        return redirect()->route('detail_transaksi', ['idTransaksi' => $transaksi->idTransaksi]);
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
