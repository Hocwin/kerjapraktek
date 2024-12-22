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
        // Apakah ingin menggunakan input manual? Set ke true jika ya.
        $manualInput = false;

        if ($manualInput) {
            // Data manual untuk detail transaksi
            $manualData = [
                [
                    'idTransaksi' => 1,
                    'idProduk' => 1,
                    'idGudang' => 1,
                    'jumlahProduk' => 90,
                ],
                [
                    'idTransaksi' => 1,
                    'idProduk' => 2,
                    'idGudang' => 1,
                    'jumlahProduk' => 85,
                ],
            ];

            foreach ($manualData as $data) {
                $this->createDetailTransaksi($data);
            }
        } else {
            // Ambil transaksi yang belum memiliki detail
            $transaksi = Transaksi::whereDoesntHave('detailTransaksi')->first();

            if (!$transaksi) {
                $this->command->info('Tidak ada transaksi yang belum memiliki detail.');
                return;
            }

            // Data produk untuk diisi secara otomatis
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

            foreach ($produkData as $produkInput) {
                $data = [
                    'idTransaksi' => $transaksi->idTransaksi,
                    'idProduk' => $produkInput['idProduk'],
                    'idGudang' => $produkInput['idGudang'],
                    'jumlahProduk' => $produkInput['jumlahProduk'],
                ];
                $this->createDetailTransaksi($data);
            }
        }

        $this->command->info('Detail transaksi berhasil ditambahkan.');
    }

    private function createDetailTransaksi(array $data)
    {
        // Ambil data produk
        $produk = Produk::find($data['idProduk']);
        if (!$produk) {
            $this->command->warn("Produk dengan ID {$data['idProduk']} tidak ditemukan.");
            return;
        }

        // Ambil gudang
        $gudang = Gudang::find($data['idGudang']);
        $namaGudang = $gudang ? $gudang->namaGudang : '';

        // Ambil transaksi
        $transaksi = Transaksi::find($data['idTransaksi']);
        if (!$transaksi) {
            $this->command->warn("Transaksi dengan ID {$data['idTransaksi']} tidak ditemukan.");
            return;
        }

        // Tentukan harga berdasarkan tipe pembayaran
        $harga = ($transaksi->tipePembayaran === 'cash') ? $produk->hargaCash : $produk->hargaTempo;

        // Simpan detail transaksi
        DetailTransaksi::create([
            'idTransaksi' => $data['idTransaksi'],
            'idProduk' => $data['idProduk'],
            'idGudang' => $data['idGudang'],
            'namaGudang' => $namaGudang,
            'jumlahProduk' => $data['jumlahProduk'],
            'harga' => $harga,
        ]);

        // Kurangi stok di gudang
        $stokGudang = StokPerGudang::where('idProduk', $data['idProduk'])
            ->where('idGudang', $data['idGudang'])
            ->first();

        if ($stokGudang) {
            $stokGudang->stok -= $data['jumlahProduk'];
            $stokGudang->save();
            $this->command->info("Stok produk dengan ID {$data['idProduk']} di gudang {$data['idGudang']} berhasil dikurangi.");
        } else {
            $this->command->warn("Stok untuk produk dengan ID {$data['idProduk']} di gudang {$data['idGudang']} tidak ditemukan.");
        }
    }
}
