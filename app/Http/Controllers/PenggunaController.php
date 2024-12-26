<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager') {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        $pengguna = Pengguna::all();
        return view('karyawan', compact('pengguna'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengguna = Pengguna::all();
        return view('add_karyawan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'admin') {
            return redirect()->route('karyawan')->with('error', 'Access denied');
        }

        // Validasi input
        $request->validate([
            'namaPengguna' => 'required|string|max:255',
            'emailPengguna' => 'required|string|max:255',
            'alamatPengguna' => 'required|string|max:255',
            'jenisKelamin' => 'required|in:L,P',
            'rolePengguna' => 'required|in:admin,sales,manager',
        ]);

        $defaultPassword = Str::random(8);

        $pengguna = new Pengguna();
        $pengguna->namaPengguna = $request->namaPengguna;
        $pengguna->emailPengguna = $request->emailPengguna;
        $pengguna->alamatPengguna = $request->alamatPengguna;
        $pengguna->jenisKelamin = $request->jenisKelamin;
        $pengguna->rolePengguna = $request->rolePengguna;
        $pengguna->password = bcrypt($defaultPassword);
        $pengguna->plaintext_password = $defaultPassword;


        $pengguna->save();

        return redirect()->route('karyawan')->with('success', 'Password default berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idPengguna)
    {
        $pengguna = Pengguna::findOrFail($idPengguna);
        return view('edit_karyawan', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idPengguna)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager') {
            return redirect()->route('karyawan')->with('error', 'Access denied');
        }

        // Validasi input
        $request->validate([
            'namaPengguna' => 'required|string|max:255',
            'emailPengguna' => 'required|string|max:255',
            'alamatPengguna' => 'required|string|max:255',
            'jenisKelamin' => 'required|in:L,P',
            'rolePengguna' => 'required|in:admin,sales,manager',
        ]);

        $pengguna = Pengguna::findOrFail($idPengguna);
        $pengguna->namaPengguna = $request->namaPengguna;
        $pengguna->emailPengguna = $request->emailPengguna;
        $pengguna->alamatPengguna = $request->alamatPengguna;
        $pengguna->jenisKelamin = $request->jenisKelamin;
        $pengguna->rolePengguna = $request->rolePengguna;

        $pengguna->save();

        return redirect()->route('karyawan')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idPengguna)
    {
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager') {
            return redirect()->route('karyawan')->with('error', 'Access denied');
        }

        $pengguna = Pengguna::findOrFail($idPengguna);
        $pengguna->delete();

        return redirect()->route('karyawan')->with('success', 'Data pengguna berhasil dihapus.');
    }
}
