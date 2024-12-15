<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';

    protected $primaryKey = 'idTransaksi';

    protected $fillable = [
        'idToko',
        'namaToko',
        'tipePembayaran',
        'status',
        'tanggalTransaksi'
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'idTransaksi');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'idToko', 'idToko');
    }
}
