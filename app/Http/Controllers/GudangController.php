<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $gudangAktif = Gudang::where('namaGudang', 'like', '%' . $request->search . '%')
            ->whereNull('deleted_at') // Mengambil produk yang tidak dihapus
            ->get();

        foreach ($gudangAktif as $gudang) {
            foreach ($gudang->stokPerGudang as $stok) {
                // Hitung total pengeluaran stok dari transaksi
                $stok->pengeluaran = DetailTransaksi::where('idGudang', $gudang->idGudang)
                    ->where('idProduk', $stok->idProduk)
                    ->sum('jumlahProduk');

                $stok->pemasukan = $stok->pemasukan;
            }
        }

        return view('gudang', [
            'gudangAktif' => $gudangAktif,
            'search' => $request->search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = \App\Models\Produk::all(); // Fetch all products

        return view('add_gudang', ['produk' => $produk]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'namaGudang' => 'required|max:255',
            'lokasi' => 'required|max:255',
            'imageAsset' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional image upload
        ]);

        // Create a new Gudang instance
        $gudang = new Gudang();
        $gudang->namaGudang = $request->namaGudang;
        $gudang->lokasi = $request->lokasi;

        // Handle image upload if a file is provided
        if ($request->hasFile('imageAsset')) {
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage/images/', $filename);  // Store the image in the storage/images/ directory
            $gudang->imageAsset = $filename;
        }

        // Save the Gudang instance to the database
        $gudang->save();

        // Save the stock for each product in the new Gudang
        if ($request->has('stok')) {
            foreach ($request->stok as $idProduk => $jumlahStok) {
                // Create or update stock per Gudang record
                $gudang->stokPerGudang()->create([
                    'idProduk' => $idProduk,
                    'stok' => $jumlahStok,
                ]);
            }
        }

        return redirect()->route('gudang')->with('success', 'Gudang berhasil ditambahkan.');
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
    public function edit(string $idGudang)
    {
        $gudang = Gudang::with(['stokPerGudang.produk' => function ($query) {
            $query->withTrashed(); // Include soft-deleted products in the stokPerGudang relation
        }])->find($idGudang);

        // Cek apakah gudang ditemukan
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }

        if (!Auth::user()->gudang->contains($idGudang)) {
            return redirect()->route('gudang')->with('error', 'Anda tidak memiliki izin untuk mengedit gudang ini.');
        }

        // Simpan stok lama ke sesi untuk referensi
        session(['stokLama' => $gudang->stokPerGudang->pluck('stok', 'idProduk')->toArray()]);

        return view('edit_gudang', ['gudang' => $gudang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idGudang)
    {
        if (!Auth::user()->gudang->contains($idGudang)) {
            return redirect()->route('gudang')->with('error', 'Anda tidak memiliki izin untuk mengedit gudang ini.');
        }

        $request->validate([
            'namaGudang' => 'required|max:255',
            'lokasi' => 'required',
        ]);

        // Find the Gudang by ID
        $gudang = Gudang::find($idGudang);
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }

        // Update Gudang properties
        $gudang->namaGudang = $request->namaGudang;
        $gudang->lokasi = $request->lokasi;

        // Handle image upload if a new file is provided
        if ($request->hasFile('imageAsset')) {

            // Hapus gambar lama jika ada
            if ($gudang->imageAsset && Storage::exists('images/' . $gudang->imageAsset)) {
                Storage::delete('images/' . $gudang->imageAsset);
            }

            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            if ($gudang->imageAsset != $filename) {
                if (Storage::exists('images/' . $gudang->imageAsset)) {
                    Storage::delete('images/' . $gudang->imageAsset);
                }
                $file->move('storage/images/', $filename);
                $gudang->imageAsset = $filename;
            }
            // Save the new image
            $file->move('storage/images/', $filename);
            $gudang->imageAsset = $filename;
        }

        // Save the Gudang instance to the database
        $gudang->save();

        if ($request->has('pemasukan')) {
            foreach ($request->pemasukan as $idProduk => $jumlahPemasukan) {
                $stokGudang = $gudang->stokPerGudang()->where('idProduk', $idProduk)->first();
                if ($stokGudang) {
                    $stokGudang->stok += $jumlahPemasukan;
                    $stokGudang->pemasukan += $jumlahPemasukan;
                    $stokGudang->save();
                } else {
                    $gudang->stokPerGudang()->create([
                        'idProduk' => $idProduk,
                        'stok' => $jumlahPemasukan,
                        'pemasukan' => $jumlahPemasukan,
                    ]);
                }
            }
        }

        return redirect()->route('gudang')->with('success', 'Gudang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idGudang)
    {
        // Find the Gudang by ID
        $gudang = Gudang::find($idGudang);

        // Check if the Gudang exists
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }

        // Soft delete the Gudang (this will not delete the related 'stokPerGudang' records)
        $gudang->delete();

        // Hide related DetailTransaksi when Gudang is deleted
        DetailTransaksi::where('idGudang', $idGudang)->update(['deleted_at' => now()]);

        $page = ['page' => 'gudang'];
        return redirect()->route('gudang_tidak_aktif')->with($page);
    }

    public function restore(string $idGudang)
    {
        // Cari produk yang sudah dihapus
        $gudang = Gudang::onlyTrashed()->find($idGudang);

        // Jika produk tidak ditemukan
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan atau belum dihapus.');
        }

        // Pulihkan produk
        $gudang->restore();

        DetailTransaksi::onlyTrashed()->where('idGudang', $idGudang)->restore();

        // Pulihkan stok per gudang jika terkait dengan produk yang tidak terhapus
        foreach ($gudang->stokPerGudang as $stok) {
            // Periksa apakah produk terkait masih ada dan belum terhapus
            if ($stok->produk && !$stok->produk->trashed()) {
                // Tidak perlu mengubah stok jika produk masih ada dan tidak terhapus
                continue;
            }

            // Jika produk sudah terhapus, bisa menyetel stok menjadi 0 atau data lainnya
            $stok->stok = 0;  // Atau bisa sesuai dengan kebutuhan Anda
            $stok->save();
        }

        return redirect()->route('gudang')->with('success', 'Gudang berhasil dipulihkan.');
    }
}
