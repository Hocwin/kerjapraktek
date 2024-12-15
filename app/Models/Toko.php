<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';
    protected $primaryKey = 'idToko';
    protected $fillable = [
        'namaToko',
        'alamatToko',
        'nomorTelepon',
        'jamOperasional',
        'namaSopir',
        'imageAsset'
    ];

    /**
     * Relasi dengan model Transaksi (One-to-Many).
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'idToko', 'idToko');
    }
}
