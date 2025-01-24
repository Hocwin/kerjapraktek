<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'gudang';

    protected $primaryKey = 'idGudang';

    protected $fillable = [
        'namaGudang',
        'lokasi',
        'imageAsset',
    ];

    public function stokPerGudang()
    {
        return $this->hasMany(StokPerGudang::class, 'idGudang', 'idGudang')->whereHas('produk', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'idGudang');
    }

    public function pengguna()
    {
        return $this->belongsToMany(User::class, 'gudang_pengguna', 'idGudang', 'idPengguna');
    }
}
