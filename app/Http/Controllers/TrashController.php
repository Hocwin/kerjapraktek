<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Toko;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        // Ambil semua produk yang dihapus (soft deleted)
        $produkTerhapus = Produk::onlyTrashed()
            ->where('namaProduk', 'like', '%' . $request->search . '%')
            ->get();

        // Ambil semua gudang yang dihapus (soft deleted)
        $gudangTerhapus = Gudang::onlyTrashed()
            ->where('namaGudang', 'like', '%' . $request->search . '%')
            ->get();

        // Ambil semua toko yang dihapus (soft deleted)
        $tokoTerhapus = Toko::onlyTrashed()
            ->where('namaToko', 'like', '%' . $request->search . '%')
            ->get();

        // Tampilkan semua data di halaman trash
        return view('trash', [
            'produkTerhapus' => $produkTerhapus,
            'gudangTerhapus' => $gudangTerhapus,
            'tokoTerhapus' => $tokoTerhapus,
            'search' => $request->search,
        ]);
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
