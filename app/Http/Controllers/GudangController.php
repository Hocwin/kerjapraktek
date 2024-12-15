<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $gudang = Gudang::where('namaGudang', 'like', '%' . $request['search'] . '%')
        ->with(['stokPerGudang.produk' => function($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])
        ->get();

    return view('gudang', ['gudang' => $gudang, 'search' => $request['search']]);
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
        $gudang = Gudang::with(['stokPerGudang.produk' => function($query) {
            $query->withTrashed(); // Include soft-deleted products in the stokPerGudang relation
        }])->find($idGudang);
    
        // Cek apakah gudang ditemukan
        if (!$gudang) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak ditemukan.');
        }
    
        return view('edit_gudang', ['gudang' => $gudang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idGudang)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin') {
            return redirect()->route('gudang')->with('error', 'Access denied');
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
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            // Delete old image if exists
            if ($gudang->imageAsset && Storage::exists('storage/images/' . $gudang->imageAsset)) {
                Storage::delete('storage/images/' . $gudang->imageAsset);
            }
            // Save the new image
            $file->move('storage/images/', $filename);
            $gudang->imageAsset = $filename;
        }
    
        // Save the Gudang instance to the database
        $gudang->save();
    
        // Update stok based on product ID
        if ($request->has('stok')) {
            foreach ($request->stok as $idProduk => $jumlahStok) {
                // Find the stok record for the product
                $stokGudang = $gudang->stokPerGudang->where('idProduk', $idProduk)->first();
    
                // If the product stock exists, update it, otherwise create a new record
                if ($stokGudang) {
                    // If a new stock value is provided, update it
                    $stokGudang->stok = $jumlahStok ? $jumlahStok : $stokGudang->stok;
                    $stokGudang->save();
                } else {
                    // If no stock record exists, create a new one with the provided stock value
                    $gudang->stokPerGudang()->create([
                        'idProduk' => $idProduk,
                        'stok' => $jumlahStok ?: 0,  // If no stok is provided, set it to 0
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

    // Optionally, you can also delete related stock records
    $gudang->stokPerGudang()->delete(); // This will delete all stock records related to the Gudang

    // If the Gudang has an image, delete it from storage
    if ($gudang->imageAsset && Storage::exists('storage/images/' . $gudang->imageAsset)) {
        Storage::delete('storage/images/' . $gudang->imageAsset);
    }

    // Delete the Gudang itself
    $gudang->delete();

    // Redirect back to the Gudang index with a success message
    return redirect()->route('gudang')->with('success', 'Gudang berhasil dihapus.');
    }
}
