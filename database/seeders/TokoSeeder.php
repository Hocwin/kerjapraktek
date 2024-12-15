<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Toko;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toko = [
            [
                'idToko' => '1',
                'namaToko' => 'A',
                'alamatToko' => 'Jl. xxx',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ]
        ];

        foreach ($toko as $key => $value){
            Toko::create($value);
        }
    }
}
