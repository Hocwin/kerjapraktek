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
                'namaToko' => 'Logam Mulia',
                'alamatToko' => 'Pasar Rakyat, Mariana Ilir, Kec. Banyuasin I, Kab. Banyuasin, Sumatera Selatan',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '2',
                'namaToko' => 'Mitra Perumnas',
                'alamatToko' => 'Jl. Siaran, Sako Baru, Kec. Sako, Kota Palembang, Sumatera Selatan 30163',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'tokoa.jpeg'
            ],
            [
                'idToko' => '3',
                'namaToko' => 'Inti Jaya FR',
                'alamatToko' => 'Jl. H. Abdul Rozak, Kalidoni, Kec. Kalidoni, Kota Palembang, Sumatera Selatan 30961',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '4',
                'namaToko' => 'Sinar Terang',
                'alamatToko' => 'Perumahan Talang, JL. Kelapa Gading Raya, Kelapa Blok 4 No.48-49, Talang Klp., Kec. Alang-Alang Lebar, Kota Palembang, Sumatera Selatan 30961',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '5',
                'namaToko' => 'Bintang Timur',
                'alamatToko' => 'Jl. kelapa sawit raya perumnas talang kelapa no 15/16, Talang Klp., Kec. Alang-Alang Lebar, Kota Palembang, Sumatera Selatan 30961',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '6',
                'namaToko' => 'WF 3',
                'alamatToko' => 'Jl. Tj. Api-Api, Kebun Bunga, Kec. Sukarami, Kota Palembang, Sumatera Selatan',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '7',
                'namaToko' => 'Kim Jaya 2',
                'alamatToko' => 'Kebun Bunga, Sukarami, Palembang City, South Sumatra 30961',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '8',
                'namaToko' => 'Medy Jaya',
                'alamatToko' => 'Jl. Macan Lindungan No.1, Bukit Baru, Kec. Ilir Bar. I, Kota Palembang, Sumatera ',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '9',
                'namaToko' => 'Mitra Sarana Utama',
                'alamatToko' => '3QGP+VW3, Jl. Sematang Borang, Sako, Kec. Sako, Kota Palembang, Sumatera Selatan 30163',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '10',
                'namaToko' => 'Citra Permai',
                'alamatToko' => 'Jl. R. E. Martadinata No.765, Sei Buah, Kec. Ilir Tim. II, Kota Palembang, Sumatera Selatan 30118',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ],
            [
                'idToko' => '11',
                'namaToko' => 'Inti Jaya FR',
                'alamatToko' => 'Bukit besar, Jl. Jaksa Agung R. Soeprapto No.31 A-B, 26 Ilir D. I, Kec. Ilir Bar. I, Kota Palembang, Sumatera Selatan 30139',
                'nomorTelepon' => '089638978165',
                'jamOperasional' => '07.00 - 18.00',
                'namaSopir' => 'Budi',
                'imageAsset' => 'toko.jpeg'
            ]
        ];

        foreach ($toko as $key => $value) {
            Toko::create($value);
        }
    }
}
