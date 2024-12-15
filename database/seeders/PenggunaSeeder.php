<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengguna = [
            [
                'idPengguna' => '1',
                'namaPengguna' => 'Admin',
                'emailPengguna' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'alamatPengguna' => 'Jalan Bunga 123',
                'jenisKelamin' => 'L',
                'rolePengguna' => 'admin'
            ],[
                'idPengguna' => '2',
                'namaPengguna' => 'Rayvin',
                'emailPengguna' => 'rayvin@gmail.com',
                'password' => Hash::make('1234567'),
                'alamatPengguna' => 'Jalan Singa 123',
                'jenisKelamin' => 'L',
                'rolePengguna' => 'sales'
            ],
            [
                'idPengguna' => '3',
                'namaPengguna' => 'Suhartoyo',
                'emailPengguna' => 'suhartoyo@gmail.com',
                'password' => Hash::make('1234567'),
                'alamatPengguna' => 'Jalan Singa 123',
                'jenisKelamin' => 'L',
                'rolePengguna' => 'manager'
            ],
        ];

        foreach ($pengguna as $key => $value){
            Pengguna::create($value);
        }
    }
}

