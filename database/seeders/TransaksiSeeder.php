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
                'tipePembayaran' => 'tempo',
                'status' => 'belum lunas',
                // Set tanggal secara manual (ubah sesuai kebutuhan), atau hapus untuk otomatis pakai now()
                'tanggalTransaksi' => now(),
            ],
            [
                'idTransaksi' => '2',
                'idToko' => '2',
                'tipePembayaran' => 'tempo',
                'status' => 'lunas',
                // Set tanggal secara manual
                'tanggalTransaksi' => now(),
            ],
            [
                'idTransaksi' => '3',
                'idToko' => '3',
                'tipePembayaran' => 'cash',
                'status' => 'belum lunas',
                'tanggalTransaksi' => now(),
            ],
            [
                'idTransaksi' => '4',
                'idToko' => '4',
                'tipePembayaran' => 'tempo',
                'status' => 'lunas',
                // Set tanggal secara manual
                'tanggalTransaksi' => now(),
            ],
            [
                'idTransaksi' => '5',
                'idToko' => '5',
                'tipePembayaran' => 'tempo',
                'status' => 'belum lunas',
                // Set tanggal secara manual
                'tanggalTransaksi' => now(),
            ]
        ];

        foreach ($transaksi as $value) {
            // Menyimpan transaksi ke database
            Transaksi::create([
                'idTransaksi' => $value['idTransaksi'],
                'idToko' => $value['idToko'],
                'tipePembayaran' => $value['tipePembayaran'],
                'status' => $value['status'],
                'tanggalTransaksi' => $value['tanggalTransaksi'],
            ]);
        }
    }
}
