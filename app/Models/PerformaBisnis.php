<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PerformaBisnis extends Model
{
    use HasFactory;

    protected $table = 'performa_bisnis'; // This is the table to store performance data
    protected $primaryKey = 'idLaporan';

    protected $fillable = [
        'idToko',
        'total_penjualan',
        'total_profit',
        'produk_terlaris',
        'periode',
    ];

    // Define the relationship with Toko (Store)
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'idToko', 'idToko');
    }
}
