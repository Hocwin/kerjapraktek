<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksi = [
            [
                'idTransaksi' => '1',
                'idToko' => '1',
                'tipePembayaran' => 'cash',
                'status' => 'belum lunas',
                'tanggalTransaksi' => now(),
            ]
        ];

        foreach ($transaksi as $key => $value){
            Transaksi::create($value);
        }
    }
}
