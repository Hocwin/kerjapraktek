<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StokPerGudang;

class StokPerGudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StokPerGudang::create([
            'idGudang' => 1,  // Gudang TAA
            'idProduk' => 1,  // Produk ID 1
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 1,  // Gudang TAA
            'idProduk' => 2,  // Produk ID 2
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 1,  // Gudang TAA
            'idProduk' => 3,  // Produk ID 3
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        // Stok untuk Gudang dengan idGudang = 2
        StokPerGudang::create([
            'idGudang' => 2,  // Gudang KM12
            'idProduk' => 1,  // Produk ID 1
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 2,  // Gudang KM12
            'idProduk' => 2,  // Produk ID 2
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 2,  // Gudang KM12
            'idProduk' => 3,  // Produk ID 3
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        // Stok untuk Gudang dengan idGudang = 3
        StokPerGudang::create([
            'idGudang' => 3,  // Gudang KM12
            'idProduk' => 1,  // Produk ID 1
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 3,  // Gudang KM12
            'idProduk' => 2,  // Produk ID 2
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 3,  // Gudang KM12
            'idProduk' => 3,  // Produk ID 3
            'stok' => 5000,
        ]);

        // Stok untuk Gudang dengan idGudang = 4
        StokPerGudang::create([
            'idGudang' => 4,  // Gudang KM12
            'idProduk' => 1,  // Produk ID 1
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 4,  // Gudang KM12
            'idProduk' => 2,  // Produk ID 2
            'stok' => 5000,
            'pemasukan' => 0,
        ]);

        StokPerGudang::create([
            'idGudang' => 4,  // Gudang KM12
            'idProduk' => 3,  // Produk ID 3
            'stok' => 5000,
            'pemasukan' => 0,
        ]);
    }
}
