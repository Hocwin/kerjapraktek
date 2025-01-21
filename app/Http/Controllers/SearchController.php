<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Transaksi;
use App\Models\Toko;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        // Cari di tabel Toko
        $toko = Toko::select('namaToko', 'alamatToko', 'nomorTelepon', 'jamOperasional', 'namaSopir', 'imageAsset')
            ->where('namaToko', 'like', "%{$query}%")
            ->orWhere('alamatToko', 'like', "%{$query}%")
            ->orWhere('nomorTelepon', 'like', "%{$query}%")
            ->get();

        // Cari di tabel Produk
        $produkAktif = Produk::select('namaProduk', 'hargaCash', 'hargaTempo', 'hargaBeli', 'imageAsset')
            ->where('namaProduk', 'like', "%{$query}%")
            ->orWhere('hargaCash', 'like', "%{$query}%")
            ->orWhere('hargaTempo', 'like', "%{$query}%")
            ->get();

        // Cari di tabel Gudang


        $gudang = Gudang::select('idGudang', 'namaGudang', 'lokasi', 'imageAsset')
            ->where('namaGudang', 'like', "%{$query}%")
            ->orWhereHas('stokPerGudang.produk', function ($q) use ($query) {
                $q->where('namaProduk', 'like', "%{$query}%");
            })
            ->with(['stokPerGudang.produk'])
            ->get();

        // Cari di tabel Transaksi
        $transaksi = Transaksi::with(['toko', 'detailTransaksi.produk'])
            ->whereHas('toko', function ($q) use ($query) {
                $q->where('namaToko', 'like', "%{$query}%");
            })
            ->orWhere('idTransaksi', 'like', "%{$query}%")
            ->orWhere('tipePembayaran', 'like', "%{$query}%")
            ->orWhere('status', 'like', "%{$query}%")
            ->get();

        return view('search', compact('toko', 'produkAktif', 'gudang', 'transaksi', 'query'));
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
