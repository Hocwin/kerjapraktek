<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $tokoAktif = Toko::where('namaToko', 'like', '%' . $request->search . '%')
            ->whereNull('deleted_at') // Mengambil produk yang tidak dihapus
            ->orderBy('namaToko', 'asc')
            ->get();

        return view('toko', [
            'tokoAktif' => $tokoAktif,
            'search' => $request->search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $toko = Toko::all();
        return view('add_toko');
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
            'namaToko' => 'required|string|max:255',
            'alamatToko' => 'required|string|max:255',
            'nomorTelepon' => 'required|string|max:15',
            'jamOperasional' => 'required|string|max:255',
            'namaSopir' => 'required|string|max:255',
            'imageAsset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        $toko = new Toko();
        $toko->namaToko = $request->namaToko;
        $toko->alamatToko = $request->alamatToko;
        $toko->nomorTelepon = $request->nomorTelepon;
        $toko->jamOperasional = $request->jamOperasional;
        $toko->namaSopir = $request->namaSopir;

        if ($request->hasFile('imageAsset')) {
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage/images/', $filename);
            $toko->imageAsset = $filename;
        }
        $toko->save();

        return redirect()->route('toko');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idToko)
    {
        $toko = Toko::findOrFail($idToko);
        $transaksi = Transaksi::where('idToko', $idToko)->with('detailTransaksi')->get();

        return view('detail_toko', compact('toko', 'transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idToko)
    {
        $toko = Toko::findOrFail($idToko);
        // Cek apakah produk ditemukan
        if (!$toko) {
            return redirect()->route('toko')->with('error', 'Produk tidak ditemukan.');
        }
        return view('edit_toko', compact('toko'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idToko)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin') {
            return redirect()->route('gudang')->with('error', 'Access denied');
        }


        $validator = $request->validate([
            'namaToko' => 'required|max:255',
            'alamatToko' => 'required',
            'nomorTelepon' => 'required',
            'jamOperasional' => 'required',
            'namaSopir' => 'required',
        ]);

        $toko = Toko::find($idToko);
        if ($toko->namaToko != $request->namaToko) {
            $toko->namaToko = $request->namaToko;
        }

        if ($toko->alamatToko != $request->alamatToko) {
            $toko->alamatToko = $request->alamatToko;
        }

        if ($toko->nomorTelepon != $request->nomorTelepon) {
            $toko->nomorTelepon = $request->nomorTelepon;
        }

        if ($toko->jamOperasional != $request->jamOperasional) {
            $toko->jamOperasional = $request->jamOperasional;
        }

        if ($toko->namaSopir != $request->namaSopir) {
            $toko->namaSopir = $request->namaSopir;
        }

        if ($request->hasFile('imageAsset')) {

            // Hapus gambar lama jika ada
            if ($toko->imageAsset && Storage::exists('images/' . $toko->imageAsset)) {
                Storage::delete('images/' . $toko->imageAsset);
            }
            $file = $request->file('imageAsset');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            if ($toko->imageAsset != $filename) {
                if (Storage::exists('images/' . $toko->imageAsset)) {
                    Storage::delete('images/' . $toko->imageAsset);
                }
                $file->move('storage/images/', $filename);
                $toko->imageAsset = $filename;
            }
        }

        $toko->save();

        return redirect()->route('toko', ['idToko' => $toko->idToko]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idToko)
    {
        // Retrieve the Toko and eager load the related transactions
        $toko = Toko::with('transaksi')->find($idToko);

        if ($toko) {
            // Manually delete related transactions and their details
            foreach ($toko->transaksi as $transaction) {
                // Delete related details of each transaction
                foreach ($transaction->detailTransaksi as $detailTransaction) {
                    $detailTransaction->delete();
                }
                // Delete the transaction itself
                $transaction->delete();
            }

            // Delete the image file if it exists
            if ($toko->imageAsset && Storage::exists('public/' . $toko->imageAsset)) {
                Storage::delete('public/' . $toko->imageAsset);
            }

            // Now delete the Toko record
            $toko->delete();
            $page = ['page' => 'toko'];
            return redirect()->route('toko_blacklist')->with($page);
        }

        return redirect()->route('toko')->with('error', 'Toko tidak ditemukan.');
    }

    public function restore(string $idToko)
    {
        // Cari produk yang sudah dihapus
        $toko = Toko::onlyTrashed()->find($idToko);

        // Jika produk tidak ditemukan
        if (!$toko) {
            return redirect()->route('toko')->with('error', 'Toko tidak ditemukan atau belum dihapus.');
        }

        // Pulihkan produk
        $toko->restore();

        // Pulihkan transaksi yang terkait dengan toko ini
        // Pulihkan transaksi yang terkait dengan toko ini
        $transaksi = Transaksi::onlyTrashed()->where('idToko', $idToko)->get();
        foreach ($transaksi as $trx) {
            $trx->restore();

            // Pulihkan detail transaksi yang terkait dengan transaksi ini
            DetailTransaksi::onlyTrashed()->where('idTransaksi', $trx->idTransaksi)->restore();
        }

        return redirect()->route('toko')->with('success', 'Toko berhasil dipulihkan.');
    }
}
