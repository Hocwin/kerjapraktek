<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class StokPerGudang extends Model
{
    use HasFactory;

    protected $table = 'stok_per_gudang';
    protected $primaryKey = 'idStok';

    protected $fillable = ['idGudang', 'idProduk', 'stok'];

    // Relasi dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk');
    }

    // Relasi dengan gudang
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'idGudang');
    }
}
