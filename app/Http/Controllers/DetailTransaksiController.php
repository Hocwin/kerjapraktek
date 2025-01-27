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
        $detailTransaksi = DetailTransaksi::with(['produk' => function ($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])
            ->where('idTransaksi', $idTransaksi)
            ->get();

        // Ambil data transaksi untuk informasi tambahan
        $transaksi = Transaksi::findOrFail($idTransaksi);
        $toko = $transaksi->toko;

        return view('detail_transaksi', compact('detailTransaksi', 'transaksi', 'toko'));
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
        // Validasi input data
        $request->validate([
            'produk.*.idProduk' => 'required|exists:produk,idProduk|nullable', // Produk baru atau lama
            'produk.*.jumlahProduk' => 'required|integer|min:1',
            'produk.*.idGudang' => 'required|exists:gudang,idGudang|nullable', // Gudang baru atau lama
            'produk.*.diskon' => 'nullable|numeric|min:0|max:100', // Diskon dalam persen (0-100)
        ]);

        $transaksi = Transaksi::findOrFail($idTransaksi);

        foreach ($request->produk as $produkInput) {
            // Produk baru atau lama
            $produk = isset($produkInput['newProduk']) && !empty($produkInput['newProduk'])
                ? Produk::create([
                    'namaProduk' => $produkInput['newProduk'],
                    'hargaCash' => $produkInput['hargaCash'] ?? 0,
                    'hargaTempo' => $produkInput['hargaTempo'] ?? 0,
                ])
                : Produk::findOrFail($produkInput['idProduk']);

            // Gudang baru atau lama
            $gudang = isset($produkInput['newGudang']) && !empty($produkInput['newGudang'])
                ? Gudang::create(['namaGudang' => $produkInput['newGudang']])
                : Gudang::findOrFail($produkInput['idGudang']);

            // Validasi stok gudang
            $stokGudang = StokPerGudang::where('idProduk', $produk->idProduk)
                ->where('idGudang', $gudang->idGudang)
                ->first();
            if (!$stokGudang || $stokGudang->stok < $produkInput['jumlahProduk']) {
                return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
                    ->with('error', 'Stok tidak cukup untuk produk: ' . $produk->namaProduk);
            }

            // Hitung harga berdasarkan diskon (jika ada)
            $diskon = $produkInput['diskon'] ?? $produk->diskon ?? 0;
            $hargaCash = $produk->hargaCash - ($produk->hargaCash * $diskon / 100);
            $hargaTempo = $produk->hargaTempo - ($produk->hargaTempo * $diskon / 100);

            // Simpan detail transaksi
            $detailTransaksi = new DetailTransaksi();
            $detailTransaksi->idTransaksi = $transaksi->idTransaksi;
            $detailTransaksi->idProduk = $produk->idProduk;
            $detailTransaksi->jumlahProduk = $produkInput['jumlahProduk'];
            $detailTransaksi->diskon = $diskon;
            $detailTransaksi->hargaC = $hargaCash; // Harga cash setelah diskon
            $detailTransaksi->hargaT = $hargaTempo; // Harga tempo setelah diskon
            $detailTransaksi->idGudang = $gudang->idGudang;
            $detailTransaksi->namaGudang = $gudang->namaGudang;
            $detailTransaksi->save();

            // Kurangi stok gudang
            $stokGudang->stok -= $produkInput['jumlahProduk'];
            $stokGudang->save();
        }

        return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
            ->with('success', 'Detail transaksi berhasil disimpan dengan diskon.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $idTransaksi)
    {
        $detailTransaksi = DetailTransaksi::with(['produk' => function ($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])
            ->where('idTransaksi', $idTransaksi)
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
            'diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        // Detail transaksi saat ini
        $detailTransaksi = DetailTransaksi::findOrFail($idDetailTransaksi);

        $produkBaru = Produk::find($request->idProduk); // Produk baru
        $gudangBaru = Gudang::find($request->idGudang); // Gudang baru
        $stokGudangBaru = StokPerGudang::where('idProduk', $produkBaru->idProduk)
            ->where('idGudang', $gudangBaru->idGudang)
            ->first();

        // Stok gudang lama
        $stokGudangLama = StokPerGudang::where('idProduk', $detailTransaksi->idProduk)
            ->where('idGudang', $detailTransaksi->idGudang)
            ->first();

        // Hitung selisih jumlah produk
        $selisihJumlah = $request->jumlahProduk - $detailTransaksi->jumlahProduk;

        // Jika stok tidak cukup untuk perubahan
        if ($stokGudangBaru && $selisihJumlah > 0 && $stokGudangBaru->stok < $selisihJumlah) {
            return back()->with('error', 'Stok tidak cukup untuk produk: ' . $produkBaru->namaProduk);
        }

        // Jika gudang atau produk berubah, kembalikan stok lama ke gudang lama
        if ($detailTransaksi->idGudang != $request->idGudang || $detailTransaksi->idProduk != $request->idProduk) {
            if ($stokGudangLama) {
                $stokGudangLama->stok += $detailTransaksi->jumlahProduk; // Kembalikan stok lama sepenuhnya
                $stokGudangLama->save();
            }
        } else {
            // Jika gudang dan produk sama, sesuaikan stok berdasarkan selisih jumlah produk
            if ($stokGudangLama && $selisihJumlah != 0) {
                $stokGudangLama->stok -= $selisihJumlah; // Kurangi/lebihkan stok sesuai selisih
                $stokGudangLama->save();
            }
        }

        // Kurangi stok dari gudang baru jika ada perubahan
        if ($detailTransaksi->idGudang != $request->idGudang || $detailTransaksi->idProduk != $request->idProduk) {
            if ($stokGudangBaru) {
                $stokGudangBaru->stok -= $request->jumlahProduk; // Kurangi stok baru sepenuhnya
                $stokGudangBaru->save();
            }
        }
        $diskon = $request->diskon ?? 0; // Ambil diskon dari produk (jika tidak ada, anggap 0%)

        // Hitung harga berdasarkan diskon yang diambil dari produk
        $hargaCash = $produkBaru->hargaCash - ($produkBaru->hargaCash * $diskon / 100);
        $hargaTempo = $produkBaru->hargaTempo - ($produkBaru->hargaTempo * $diskon / 100);

        // Update the detail transaksi record
        $detailTransaksi->idProduk = $request->idProduk;
        $detailTransaksi->jumlahProduk = $request->jumlahProduk;
        $detailTransaksi->diskon = $diskon; // Simpan diskon
        $detailTransaksi->hargaC = $hargaCash; // Harga cash setelah diskon
        $detailTransaksi->hargaT = $hargaTempo;
        $detailTransaksi->idGudang = $request->idGudang;
        $detailTransaksi->namaGudang = $gudangBaru->namaGudang; // Update the warehouse name
        $detailTransaksi->save();

        return redirect()->route('detail_transaksi', ['idTransaksi' => $detailTransaksi->idTransaksi])
            ->with('success', 'Detail transaksi berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idDetailTransaksi)
    {
        $detailTransaksi = DetailTransaksi::withTrashed()->findOrFail($idDetailTransaksi); // Termasuk soft-deleted
        $idTransaksi = $detailTransaksi->idTransaksi;

        // Kembalikan stok ke gudang
        $stokGudang = StokPerGudang::where('idProduk', $detailTransaksi->idProduk)
            ->where('idGudang', $detailTransaksi->idGudang)
            ->first();

        if ($stokGudang) {
            $stokGudang->stok += $detailTransaksi->jumlahProduk; // Kembalikan stok
            $stokGudang->save();
        }

        // Hapus detail transaksi secara permanen
        $detailTransaksi->forceDelete();

        // Cek apakah transaksi memiliki detail lain
        $remainingDetails = DetailTransaksi::where('idTransaksi', $idTransaksi)->count();

        if ($remainingDetails === 0) {
            return redirect()->route('transaksi')->with('info', 'Semua detail transaksi telah dihapus.');
        }

        return redirect()->route('detail_transaksi', ['idTransaksi' => $idTransaksi])
            ->with('success', 'Detail transaksi berhasil dihapus permanen.');
    }
}
