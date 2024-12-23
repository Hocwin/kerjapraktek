<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\PerformaBisnisController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
});

Route::post('proses-login', [AuthController::class, 'prosesLogin'])->name('proses-login');

Route::get('/produk', function () {
    return view('produk');
})->name('produk');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk');

Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');

Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');

Route::get('/toko', [TokoController::class, 'index'])->name('toko');

Route::get('/transaksi/{idTransaksi}/detail', [DetailTransaksiController::class, 'index'])->name('detail_transaksi');

Route::post('/search', [SearchController::class, 'search'])->name('search');


Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/logout', function () {
        Auth::logout(); // Proses logout
        return redirect('/login'); // Arahkan ke halaman login
    })->name('logout');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/produk/edit/{idProduk}', [ProdukController::class, 'edit'])->name('edit_product');
    Route::post('/produk/edit/{idProduk}', [ProdukController::class, 'update'])->name('proses_editproduct');
    Route::get('/produk/add', [ProdukController::class, 'create'])->name('add_produk');
    Route::post('/produk/store', [ProdukController::class, 'store'])->name('store_product');
    Route::delete('/produk/hapus/{idProduk}', [ProdukController::class, 'destroy'])->name('destroy_product');
    Route::put('/produk/restore/{idProduk}', [ProdukController::class, 'restore'])->name('restore_product');

    Route::get('/gudang/edit/{idGudang}', [GudangController::class, 'edit'])->name('edit-gudang');
    Route::post('/gudang/edit/{idGudang}', [GudangController::class, 'update'])->name('proses-editgudang');
    Route::get('/gudang/add', [GudangController::class, 'create'])->name('add_gudang');
    Route::post('gudang', [GudangController::class, 'store'])->name('store_gudang');
    Route::delete('/gudang/{idGudang}', [GudangController::class, 'destroy'])->name('destroy_gudang');
    Route::put('/gudang/restore/{idGudang}', [GudangController::class, 'restore'])->name('restore_gudang');


    Route::get('/transaksi/add', [TransaksiController::class, 'create'])->name('add_transaksi');
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('store_transaksi');
    Route::delete('/transaksi/delete/{idTransaksi}', [TransaksiController::class, 'destroy'])->name('proses_deletetransaksi');
    Route::get('/transaksi/edit/{idTransaksi}', [TransaksiController::class, 'edit'])->name('edit_transaksi');
    Route::put('/transaksi/edit/{idTransaksi}', [TransaksiController::class, 'update'])->name('update_transaksi');

    Route::get('/toko/edit/{idToko}', [TokoController::class, 'edit'])->name('edit_toko');
    Route::put('/toko/edit/{idToko}', [TokoController::class, 'update'])->name('update_toko');
    Route::delete('/toko/delete/{idToko}', [TokoController::class, 'destroy'])->name('delete_toko');
    Route::post('/toko/store', [TokoController::class, 'store'])->name('store_toko');
    Route::get('/toko/add', [TokoController::class, 'create'])->name('add_toko');
    Route::get('/toko/{idToko}/detail', [TokoController::class, 'show'])->name('detail_toko');
    Route::put('/toko/restore/{idToko}', [TokoController::class, 'restore'])->name('restore_toko');


    Route::get('/transaksi/{idTransaksi}/detail/add', [DetailTransaksiController::class, 'create'])->name('add_detail_transaksi');

    Route::get('/detail-transaksi/{idDetailTransaksi}/edit', [DetailTransaksiController::class, 'edit'])->name('edit_detail_transaksi');
    Route::put('/detail-transaksi/{idDetailTransaksi}', [DetailTransaksiController::class, 'update'])->name('update_detail_transaksi');
    Route::delete('/detail-transaksi/{idDetailTransaksi}', [DetailTransaksiController::class, 'destroy'])->name('destroy_detail_transaksi');
    Route::post('/detail-transaksi/{idTransaksi}/store', [DetailTransaksiController::class, 'store'])->name('store_detail_transaksi');

    Route::get('/performa_bisnis', [PerformaBisnisController::class, 'index'])->name('performa_bisnis');
});
