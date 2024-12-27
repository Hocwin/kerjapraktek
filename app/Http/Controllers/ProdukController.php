<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $produkAktif = Produk::where('namaProduk', 'like', '%' . $request->search . '%')
            ->whereNull('deleted_at') // Mengambil produk yang tidak dihapus
            ->get();

        return view('produk', [
            'produkAktif' => $produkAktif,
            'search' => $request->search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gudangs = \App\Models\Gudang::all();
        return view('add_produk', compact('gudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin') {
            return redirect()->route('toko')->with('error', 'Access denied');
        }

        // Validasi input
        $request->validate([
            'namaProduk' => 'required|string|max:255',
            'hargaCash' => 'required',
            'hargaTempo' => 'required',
            'hargaBeli' => 'required',
            'imageAsset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        $produk = new Produk();
        $produk->namaProduk = $request->namaProduk;
        $produk->hargaCash = $request->hargaCash;
        $produk->hargaTempo = $request->hargaTempo;
        $produk->hargaBeli = $request->hargaBeli;

        if ($request->hasFile('imageAsset')) {

            // Hapus gambar lama jika ada
            if ($produk->imageAsset && Storage::exists('images/' . $produk->imageAsset)) {
                Storage::delete('images/' . $produk->imageAsset);
            }
            
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage/images/', $filename);
            $produk->imageAsset = $filename;
        }
        $produk->save();

        // Tambahkan stok untuk setiap gudang
        $gudangList = \App\Models\Gudang::all(); // Ambil semua gudang
        foreach ($gudangList as $gudang) {
            // Periksa apakah ada stok yang dikirimkan untuk gudang ini
            $stok = $request->stok[$gudang->idGudang] ?? 0; // Jika tidak ada stok, set ke 0
            \App\Models\StokPerGudang::create([
                'idGudang' => $gudang->idGudang,
                'idProduk' => $produk->idProduk,
                'stok' => $stok, // Simpan stok sesuai input
            ]);
        }

        return redirect()->route('produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idProduk)
    {
        // Cek apakah produk ada, termasuk produk yang dihapus (soft delete)
        $produk = Produk::withTrashed()->find($idProduk);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return redirect()->route('produk')->with('error', 'Produk tidak ditemukan.');
        }

        // Periksa jika produk sudah dihapus
        if ($produk->trashed()) {
            return view('detail_produk', [
                'produk' => $produk,
                'isTrashed' => true, // Menandakan bahwa produk sudah dihapus
            ]);
        }

        return view('detail_produk', ['produk' => $produk]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idProduk)
    {
        $produk = Produk::find($idProduk);

        // Cek apakah produk ditemukan
        if (!$produk) {
            return redirect()->route('produk')->with('error', 'Produk tidak ditemukan.');
        }

        return view('edit_product', ['produk' => $produk]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idProduk)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin') {
            return redirect()->route('produk')->with('error', 'Access denied');
        }

        $validator = $request->validate([
            'namaProduk' => 'required|max:255',
            'hargaCash' => 'required',
            'hargaTempo' => 'required',
            'hargaBeli' => 'required',
        ]);

        $produk = Produk::find($idProduk);
        if ($produk->namaProduk != $request->namaProduk) {
            $produk->namaProduk = $request->namaProduk;
        }
        if ($produk->hargaCash != $request->hargaCash) {
            $produk->hargaCash = $request->hargaCash;
        }

        if ($produk->hargaTempo != $request->hargaTempo) {
            $produk->hargaTempo = $request->hargaTempo;
        }
        if ($produk->hargaBeli != $request->hargaBeli) {
            $produk->hargaBeli = $request->hargaBeli;
        }

        if ($request->hasFile('imageAsset')) {
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            if ($produk->imageAsset != $filename) {
                if (Storage::exists($produk->idProduk)) {
                    Storage::delete($produk->idProduk);
                }
                $file->move('storage/images/', $filename);
                $produk->imageAsset = $filename;
            }
        }

        $produk->save();
        return redirect()->route('produk', ['idProduk' => $produk->idProduk])->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idProduk)
    { // Periksa hak akses (hanya admin yang boleh menghapus)
        // Periksa hak akses (hanya admin yang boleh menghapus)
        if (!Auth::check() || Auth::user()->rolePengguna !== 'admin') {
            return redirect()->route('produk')->with('error', 'Access denied');
        }

        // Cari produk berdasarkan id
        $produk = Produk::find($idProduk);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return redirect()->route('produk')->with('error', 'Produk tidak ditemukan.');
        }

        // Soft delete produk
        $produk->delete();
        $page = ['page' => 'produk'];
        return redirect()->route('produk_tidak_aktif')->with($page);
    }

    public function restore(string $idProduk)
    {
        // Cari produk yang sudah dihapus
        $produk = Produk::onlyTrashed()->find($idProduk);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return redirect()->route('produk')->with('error', 'Produk tidak ditemukan atau belum dihapus.');
        }

        // Pulihkan produk
        $produk->restore();
        return redirect()->route('produk')->with('success', 'Produk berhasil dipulihkan.');
    }
}
