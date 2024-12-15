<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gudang extends Model
{
    use HasFactory;
    protected $table = 'gudang';

    protected $primaryKey = 'idGudang';

    protected $fillable = [
        'namaGudang',
        'lokasi',
        'imageAsset',
    ];

    public function stokPerGudang()
    {
        return $this->hasMany(StokPerGudang::class, 'idGudang');
    }

    public function detailTransaksi()
    {
    return $this->hasMany(DetailTransaksi::class, 'idGudang');
    }
}
