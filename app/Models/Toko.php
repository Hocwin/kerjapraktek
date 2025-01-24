<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toko extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'toko';
    protected $primaryKey = 'idToko';
    protected $fillable = [
        'namaToko',
        'alamatToko',
        'nomorTelepon',
        'jamOperasional',
        'namaSopir',
        'idPengguna',
        'imageAsset'
    ];

    /**
     * Relasi dengan model Transaksi (One-to-Many).
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'idToko', 'idToko');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'idPengguna');
    }
}
