<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

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

    public function getHargaCorrectAttribute()
    {
        // Return the appropriate price based on the payment type
        if ($this->transaksi->tipePembayaran === 'cash') {
            return $this->produk->harga_cash;  // Using harga_cash directly
        } else if ($this->transaksi->tipePembayaran === 'tempo') {
            return $this->produk->harga_tempo;  // Using harga_tempo directly
        }

        return 0;  // Return 0 if no payment type is found (should never happen)
    }

    /**
     * Get the total price based on the correct unit price and quantity.
     *
     * @return float
     */
    public function getTotalHargaAttribute()
    {
        return $this->getHargaCorrectAttribute() * $this->jumlahProduk;
    }
}
