<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoriGudang extends Model
{
    use HasFactory;

    protected $table = 'gudang_histori';
    protected $primaryKey = 'idHistori';

    protected $fillable = [
        'idGudang',
        'idPengguna',
        'action',
        'details',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'idGudang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'idPengguna');
    }
}
