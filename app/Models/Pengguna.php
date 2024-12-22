<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class Pengguna extends AuthenticatableUser
{
    use HasFactory;

    protected $primaryKey = 'idPengguna';

    protected $guarded = ['idPengguna'];

    protected $fillable = [
        'rolePengguna',
        'namaPengguna',
        'emailPengguna',
        'password',
        'remember_token',
        'alamatPengguna',
        'jenisKelamin',
    ];

    public function getAuthIdentifierName()
    {
        return 'idPengguna';
    }

    public function getAuthIdentifier()
    {
        return $this->idPengguna;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
