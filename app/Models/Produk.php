<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'idProduk';

    use SoftDeletes;

    protected $fillable = [
        'namaProduk',
        'hargaCash',
        'hargaTempo',
        'hargaBeli',
        'imageAsset',
    ];

    public function stokPerGudang()
    {
        return $this->hasMany(StokPerGudang::class, 'idProduk');
    }

    public function detailTransaksi()
    {
    return $this->hasMany(DetailTransaksi::class, 'idProduk');
    }
}
