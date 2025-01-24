<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Pengguna;

class AdminController extends Controller
{
    // Menampilkan daftar akses gudang
    public function showSetUserGudang()
    {
        $users = Pengguna::with('gudang')->get(); // Ambil semua pengguna beserta gudang mereka
        return view('akses_gudang', compact('users'));
    }

    // Menampilkan form untuk set akses gudang
    public function showFormSetUserGudang()
    {
        $gudang = Gudang::all(); // Ambil semua data gudang
        $users = Pengguna::all(); // Ambil semua data pengguna

        return view('form_set_user_gudang', compact('gudang', 'users'));
    }

    // Menyimpan akses pengguna ke gudang
    public function setUserGudang(Request $request)
    {
        $request->validate([
            'idGudang' => 'required|exists:gudang,idGudang',
            'idPengguna' => 'required|exists:penggunas,idPengguna',
        ]);

        $gudang = Gudang::find($request->idGudang);
        $gudang->pengguna()->syncWithoutDetaching($request->idPengguna);

        return redirect()->route('admin.showSetUserGudang')->with('success', 'Akses pengguna berhasil diatur.');
    }

    // Menampilkan form untuk mengedit akses gudang pengguna
    public function showEditUserGudang($idPengguna)
    {
        $gudang = Gudang::all(); // Ambil semua data gudang
        $pengguna = Pengguna::findOrFail($idPengguna); // Ambil pengguna berdasarkan id
        $currentGudang = $pengguna->gudang; // Akses gudang yang sudah dimiliki pengguna

        return view('update_akses', compact('gudang', 'pengguna', 'currentGudang'));
    }

    // Menyimpan perubahan akses gudang pengguna
    public function updateUserGudang(Request $request, $idPengguna)
    {
        $request->validate([
            'idGudang' => 'required|exists:gudang,idGudang', // Validasi untuk memastikan ID gudang ada di tabel gudang
        ]);

        $pengguna = Pengguna::findOrFail($idPengguna); // Temukan pengguna berdasarkan ID
        $currentGudang = $pengguna->gudang; // Ambil gudang yang sedang diakses oleh pengguna

        // Jika pengguna memiliki akses ke gudang yang sama dengan yang dipilih, tidak perlu melakukan perubahan
        if ($currentGudang->contains($request->idGudang)) {
            return redirect()->route('admin.showSetUserGudang')->with('error', 'Pengguna sudah memiliki akses ke gudang ini.');
        }

        // Pindahkan akses gudang ke gudang yang baru
        $pengguna->gudang()->sync([$request->idGudang]);

        return redirect()->route('admin.showSetUserGudang')->with('success', 'Akses gudang pengguna berhasil dipindahkan.');
    }
}
