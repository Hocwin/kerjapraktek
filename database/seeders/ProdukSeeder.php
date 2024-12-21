<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = [
            [
                'idProduk' => '1',
                'namaProduk' => 'Semen Baturaja',
                'hargaCash' => 62000,
                'hargaTempo' => 63000,
                'hargaBeli' => 61000,
                'imageAsset' => 'baturaja.jpg'
            ], [
                'idProduk' => '2',
                'namaProduk' => 'Semen Dynamix',
                'hargaCash' => 58000,
                'hargaTempo' => 59000,
                'hargaBeli' => 57000,
                'imageAsset' => 'dynamix.jpeg'
            ], [
                'idProduk' => '3',
                'namaProduk' => 'Semen Merdeka',
                'hargaCash' => 54000,
                'hargaTempo' => 55000,
                'hargaBeli' => 53000,
                'imageAsset' => 'merdeka.jpeg'
            ]
            ];

            foreach ($produk as $key => $value){
                Produk::create($value);
            }
    }
}
