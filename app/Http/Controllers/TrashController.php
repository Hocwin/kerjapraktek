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

        $page = $request->input('page');
        $page1 = session('page');
        // Tampilkan semua data di halaman trash
        if($page === 'toko' || $page1 === 'toko'){
            return view('toko_blacklist', [
                'tokoTerhapus' => $tokoTerhapus,
                'search' => $request->search,
            ]);
        } else if($page === 'produk' || $page1 === 'produk'){
            return view('produk_tidak_aktif', [
                'produkTerhapus' => $produkTerhapus,
                'search' => $request->search,
            ]);
        } else if($page === 'gudang'|| $page1 === 'gudang'){
            return view('gudang_tidak_aktif', [
                'gudangTerhapus' => $gudangTerhapus,
                'search' => $request->search,
            ]);
        }
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
