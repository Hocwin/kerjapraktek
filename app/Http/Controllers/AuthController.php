<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'emailPengguna' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('emailPengguna', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('produk');
        }

        return back()->withErrors([
            'emailPengguna' => 'Email atau Password salah',
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
