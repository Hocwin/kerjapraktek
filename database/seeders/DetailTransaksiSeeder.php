<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\StokPerGudang;
use App\Models\Produk;
use App\Models\Gudang;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Ambil transaksi yang akan diisi detailnya
        // Kita ambil transaksi pertama yang belum memiliki detail transaksi
        $transaksi = Transaksi::whereDoesntHave('detailTransaksi') // Pastikan transaksi belum memiliki detail
                              ->first(); // Ambil transaksi pertama yang memenuhi kondisi

        // Pastikan transaksi ditemukan
        if (!$transaksi) {
            $this->command->info('Tidak ada transaksi yang belum memiliki detail.');
            return;
        }

        // Contoh data produk yang digunakan untuk detail transaksi
        $produkData = [
            [
                'idProduk' => 1,
                'idGudang' => 1,
                'jumlahProduk' => 20,
            ],
            [
                'idProduk' => 2,
                'idGudang' => 1,
                'jumlahProduk' => 10,
            ],
        ];

        // Loop untuk memasukkan data detail transaksi
        foreach ($produkData as $produkInput) {
            // Ambil produk berdasarkan ID
            $produk = Produk::find($produkInput['idProduk']);

            // Jika produk tidak ditemukan, lanjutkan ke produk berikutnya
            if (!$produk) {
                continue;
            }

            // Ambil nama gudang berdasarkan idGudang
            $gudang = Gudang::find($produkInput['idGudang']);
            $namaGudang = $gudang ? $gudang->namaGudang : '';  // Ensure default is empty if not found

            // Tentukan harga sesuai tipe pembayaran
            $harga = ($transaksi->tipePembayaran == 'cash') ? $produk->hargaCash : $produk->hargaTempo;

            // Simpan detail transaksi
            $detailTransaksi = DetailTransaksi::create([
                'idTransaksi' => $transaksi->idTransaksi,
                'idProduk' => $produk->idProduk,
                'idGudang' => $produkInput['idGudang'],
                'namaGudang' => $namaGudang,  // Use the populated name
                'jumlahProduk' => $produkInput['jumlahProduk'],
                'harga' => $harga,
            ]);

            // Ambil stok produk di gudang tertentu berdasarkan idProduk dan idGudang
            $stokGudang = StokPerGudang::where('idProduk', $produk->idProduk)
                                        ->where('idGudang', $produkInput['idGudang']) // Mengambil stok di gudang yang sesuai
                                        ->first();

            if ($stokGudang) {
                // Kurangi stok produk di gudang setelah transaksi
                $stokGudang->stok -= $produkInput['jumlahProduk'];
                $stokGudang->save();
            }
        }

        $this->command->info('Detail transaksi berhasil ditambahkan.');

    }
}
