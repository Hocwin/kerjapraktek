<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\StokPerGudang;
use App\Models\DetailTransaksi;
use App\Models\Gudang;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $idTransaksi)
    {
        $detailTransaksi = DetailTransaksi::with(['produk' => function($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])
        ->where('idTransaksi', $idTransaksi)
        ->get();
    
        // Ambil data transaksi untuk informasi tambahan
        $transaksi = Transaksi::findOrFail($idTransaksi);
    
        return view('detail_transaksi', compact('detailTransaksi', 'transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $idTransaksi)
    {
        // Ambil produk dan gudang untuk form
        $produk = Produk::all();
        $gudang = Gudang::all();
    
        return view('add_detail_transaksi', compact('idTransaksi', 'produk', 'gudang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $idTransaksi)
    {
       // Validate input data for produk and gudang
    $request->validate([
        'produk.*.idProduk' => 'required|exists:produk,idProduk|nullable', // Allow null for new produk
        'produk.*.jumlahProduk' => 'required|integer|min:1',
        'produk.*.idGudang' => 'required|exists:gudang,idGudang|nullable', // Allow null for new gudang
    ]);

    $transaksi = Transaksi::findOrFail($idTransaksi);

    foreach ($request->produk as $produkInput) {
        // Check if the product is new or existing
        if (isset($produkInput['newProduk']) && !empty($produkInput['newProduk'])) {
            // Create new produk if necessary
            $produk = new Produk();
            $produk->namaProduk = $produkInput['newProduk'];
            $produk->hargaCash = $produkInput['hargaCash'] ?? 0; // Ensure hargaCash is provided
            $produk->hargaTempo = $produkInput['hargaTempo'] ?? 0; // Ensure hargaTempo is provided
            $produk->save();
        } else {
            // If produk is selected, find the existing one
            $produk = Produk::find($produkInput['idProduk']);
        }

        // Check if the gudang is new or existing
        if (isset($produkInput['newGudang']) && !empty($produkInput['newGudang'])) {
            // Create new gudang if necessary
            $gudang = new Gudang();
            $gudang->namaGudang = $produkInput['newGudang'];
            $gudang->save();
        } else {
            // If gudang is selected, find the existing one
            $gudang = Gudang::find($produkInput['idGudang']);
        }

        // Get the warehouse stock
        $stokGudang = StokPerGudang::where('idProduk', $produk->idProduk)
                                    ->where('idGudang', $gudang->idGudang)
                                    ->first();

        // Ensure enough stock is available
        if ($stokGudang && $stokGudang->stok >= $produkInput['jumlahProduk']) {
            // Create the detail transaksi entry
            $detailTransaksi = new DetailTransaksi();
            $detailTransaksi->idTransaksi = $transaksi->idTransaksi;
            $detailTransaksi->idProduk = $produk->idProduk;
            $detailTransaksi->jumlahProduk = $produkInput['jumlahProduk'];
            $detailTransaksi->harga = $transaksi->tipePembayaran == 'cash' ? $produk->hargaCash : $produk->hargaTempo;
            $detailTransaksi->idGudang = $gudang->idGudang;
            $detailTransaksi->namaGudang = $gudang->namaGudang;  // Store the warehouse name
            $detailTransaksi->save();

            // Reduce stock for the selected product and warehouse
            $stokGudang->stok -= $produkInput['jumlahProduk'];
            $stokGudang->save();
        } else {
            return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
                             ->with('error', 'Stok tidak cukup untuk produk: ' . $produk->namaProduk);
        }
    }

    return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
                     ->with('success', 'Detail transaksi berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detailTransaksi = DetailTransaksi::with(['produk' => function($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])
        ->where('idTransaksi', $id)
        ->get();
    
        return view('detail_transaksi', compact('detailTransaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idDetailTransaksi)
    {
        $detailTransaksi = DetailTransaksi::findOrFail($idDetailTransaksi);
        $produk = Produk::all();
        $gudang = Gudang::all();

        return view('edit_detail_transaksi', compact('detailTransaksi', 'produk', 'gudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idDetailTransaksi)
    {
        $request->validate([
            'idProduk' => 'required|exists:produk,idProduk',
            'jumlahProduk' => 'required|integer|min:1',
            'idGudang' => 'required|exists:gudang,idGudang',
        ]);

        $detailTransaksi = DetailTransaksi::findOrFail($idDetailTransaksi);
        $produk = Produk::find($request->idProduk);
        $gudang = Gudang::find($request->idGudang);
        $namaGudang = $gudang ? $gudang->namaGudang : '';
        $stokGudang = StokPerGudang::where('idProduk', $produk->idProduk)
                                    ->where('idGudang', $request->idGudang)
                                    ->first();

        // Pastikan stok cukup
        if ($stokGudang && $stokGudang->stok >= $request->jumlahProduk) {
            $detailTransaksi->idProduk = $request->idProduk;
            $detailTransaksi->jumlahProduk = $request->jumlahProduk;
            $detailTransaksi->harga = $produk->hargaCash; // Atau harga sesuai pembayaran
            $detailTransaksi->idGudang = $request->idGudang;
            $detailTransaksi->namaGudang = $namaGudang;
            $detailTransaksi->save();

            // Pemotongan stok
            $stokGudang->stok -= $request->jumlahProduk;
            $stokGudang->save();

            return redirect()->route('detail_transaksi', ['idTransaksi' => $detailTransaksi->idTransaksi])
                            ->with('success', 'Detail transaksi berhasil diperbarui');
        } else {
            return back()->with('error', 'Stok tidak cukup untuk produk: ' . $produk->namaProduk);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idDetailTransaksi)
    {
        $detailTransaksi = DetailTransaksi::findOrFail($idDetailTransaksi);
    $idTransaksi = $detailTransaksi->idTransaksi;

    $stokGudang = StokPerGudang::where('idProduk', $detailTransaksi->idProduk)
                                ->where('idGudang', $detailTransaksi->idGudang)
                                ->first();

    if ($stokGudang) {
        $stokGudang->stok += $detailTransaksi->jumlahProduk; // Kembalikan stok
        $stokGudang->save();
    }

    $detailTransaksi->delete();

    // Cek apakah transaksi memiliki detail lain
    $remainingDetails = DetailTransaksi::where('idTransaksi', $idTransaksi)->count();

    if ($remainingDetails === 0) {
        return redirect()->route('transaksi')->with('info', 'Semua detail transaksi telah dihapus.');
    }

    return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
                    ->with('success', 'Detail transaksi berhasil dihapus');
    }
}
