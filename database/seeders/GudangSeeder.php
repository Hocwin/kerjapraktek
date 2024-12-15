<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gudang;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudang = [
            [
                'idGudang' => '1',
                'namaGudang' => 'Gudang TAA',
                'lokasi' => 'Jl. Letjen Harun Sohar Komplek Pergudangan Palembang Star No. B11 Tanjung Api - Api Palembang',
                'imageAsset' => 'taa.jpeg'
            ],  [
                'idGudang' => '2',
                'namaGudang' => 'Gudang KM12',
                'lokasi' => 'Jl. Palembang Betung Alang - Alang Lebar Palembang KM.12',
                'imageAsset' => 'km12.jpeg'
            ],
            [
                'idGudang' => '3',
                'namaGudang' => 'Gudang Kenten',
                'lokasi' => 'Jl. Sako Baru RT.09 RW.04 Kel. Sako Baru Palembang',
                'imageAsset' => 'kenten.jpeg'
            ],
            [
                'idGudang' => '4',
                'namaGudang' => 'Gudang Perintis',
                'lokasi' => 'Jl. Perintis Kemerdekaan Lr. Buntu RT.012 RW.04 Lawang Kidul Ilir Timur II Palembang',
                'imageAsset' => 'perintis.jpeg'
            ]
            ];

            foreach ($gudang as $key => $value){
                Gudang::create($value);
            }
    }
}
