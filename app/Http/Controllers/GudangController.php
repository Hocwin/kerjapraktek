<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\HistoriGudang;
use App\Models\StokPerGudang;
use App\Models\Produk;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $oldData = $gudang->toArray();

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

    public function index_retur(string $idGudang)
    {
        $gudang = Gudang::with(['stokPerGudang.produk'])->find($idGudang);

        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }

        $stokRetur = HistoriGudang::where('idGudang', $idGudang)
            ->with('pengguna')
            ->orderBy('created_at', 'desc')
            ->get();

        // foreach ($stokRetur as $history) {
        //     $history->retur_details = json_decode($history->details)->retur ?? [];
        // }

        foreach ($stokRetur as $history) {
            // Decode the 'details' field
            $details = json_decode($history->details, true);

            // Extract 'retur' and 'changes' from the decoded details
            $history->retur_details = $details['retur'] ?? [];
            $history->changes = $details['changes'] ?? [];
        }


        return view('retur', ['gudang' => $gudang, 'histories' => $stokRetur]);
    }


    public function edit_input(string $idGudang)
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

        return view('input_gudang', ['gudang' => $gudang]);
    }

    public function input(Request $request, string $idGudang)
    {
        if (!Auth::user()->gudang->contains($idGudang)) {
            return redirect()->route('gudang')->with('error', 'Anda tidak memiliki izin untuk mengedit gudang ini.');
        }

        // Find the Gudang by ID
        $gudang = Gudang::find($idGudang);
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }

        $request->validate([
            'gambarBukti' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional image upload
        ]);

        $gambarBukti = null; // Initialize the image path variable

        // Handle the image upload if exists
        if ($request->hasFile('gambarBukti')) {
            $gambarBukti = $request->file('gambarBukti');
            $gambarBukti = $gambarBukti->store('bukti_gambar', 'public'); // Store the image in the 'public/bukti_gambar' directory
        }

        if ($request->has('pemasukan')) {
            $changes = [];
            foreach ($request->pemasukan as $idProduk => $jumlahPemasukan) {
                if ($jumlahPemasukan !== null) { // Only process non-null pemasukan
                    $stokGudang = $gudang->stokPerGudang()->where('idProduk', $idProduk)->first();



                    if ($stokGudang) {
                        // Update stok and pemasukan if stok already exists
                        $stokGudang->stok += $jumlahPemasukan;
                        $stokGudang->totalPemasukan += $jumlahPemasukan; // Update total pemasukan
                        $stokGudang->pemasukan = $jumlahPemasukan; // Update pemasukan with new input

                        $stokGudang->save();
                    } else {
                        // If stok does not exist, create new record
                        $gudang->stokPerGudang()->create([
                            'idProduk' => $idProduk,
                            'stok' => $jumlahPemasukan,
                            'pemasukan' => $jumlahPemasukan, // Set pemasukan as new input
                            'totalPemasukan' => $jumlahPemasukan, // Total pemasukan equals new input

                        ]);
                    }

                    $changes[] = [
                        'idProduk' => $idProduk,
                        'jumlahPemasukan' => $jumlahPemasukan,
                    ];
                }
            }

            // Check if there are any returns and process them (Accumulate the return quantity instead of overwriting it)
            if ($request->has('retur')) {
                $retur = [];
                foreach ($request->retur as $idProduk => $jumlahRetur) {
                    if ($jumlahRetur !== null) {
                        $stokGudang = $gudang->stokPerGudang()->where('idProduk', $idProduk)->first();
                        $produk = Produk::find($idProduk); // Get product data
                        $hargaBeli = $produk ? $produk->hargaBeli : 0; // Get product's purchase price

                        if ($stokGudang) {
                            // If stock exists for this product, replace the previous return with the new input
                            $stokGudang->retur = $jumlahRetur; // Set the return quantity to the new input value
                            $totalHargaRetur = $jumlahRetur * $hargaBeli; // Calculate total return price
                            $stokGudang->totalHargaRetur = $totalHargaRetur; // Update the total return price with the new value
                            $stokGudang->save(); // Save changes to the database
                        } else {
                            // If stock doesn't exist for the product, create a new record for the return
                            $gudang->stokPerGudang()->create([
                                'idProduk' => $idProduk,
                                'retur' => $jumlahRetur, // Set return as the new input value
                                'totalHargaRetur' => $jumlahRetur * $hargaBeli, // Calculate and set the total return price
                            ]);
                        }

                        $retur[] = [
                            'idProduk' => $idProduk,
                            'jumlahRetur' => $jumlahRetur,
                            'hargaRetur' => $totalHargaRetur,
                        ];
                    }
                }
            }

            // Save the history even if pemasukan or retur has no changes
            HistoriGudang::create([
                'idGudang' => $gudang->idGudang,
                'idPengguna' => Auth::user()->idPengguna,
                'action' => 'update',
                'details' => json_encode([
                    'changes' => $changes,
                    'retur' => $retur,
                    'bukti_gambar' => $gambarBukti,
                ]),
            ]);

            return redirect()->route('gudang')->with('success', 'Gudang berhasil diperbarui.');
        }
    }

    public function history($idGudang)
    {
        $histories = HistoriGudang::where('idGudang', $idGudang)
            ->with('pengguna') // Pastikan pengguna dimuat
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history', [
            'histories' => $histories,
        ]);
    }
}
