<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi';

    protected $primaryKey = 'idDetailTransaksi';

    protected $fillable = [
        'idProduk',
        'idGudang',
        'namaGudang',
        'jumlahProduk',
        'harga',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idTransaksi');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'idGudang');
    }
}
