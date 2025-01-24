<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Laravel\Prompts\password;

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
        if (!Auth::user() || Auth::user()->rolePengguna != 'admin' && Auth::user()->rolePengguna != 'manager') {
            return redirect()->route('karyawan')->with('error', 'Access denied');
        }

        // Validasi input
        $request->validate([
            'namaPengguna' => 'required|string|max:255',
            'emailPengguna' => 'required|string|max:255',
            'alamatPengguna' => 'required|string|max:255',
            'jenisKelamin' => 'required|in:L,P',
            'rolePengguna' => 'required|in:admin,sales,manager,gudang',
        ]);

        // Tentukan kode unik berdasarkan role pengguna
        $kode = match (strtolower($request->rolePengguna)) {
            'manager' => '10',
            'admin' => '11',
            'sales' => '12',
            'gudang' => '13',
            default => '00', // Default jika role tidak sesuai
        };

        // Buat password default (kombinasi nama pengguna kecil tanpa spasi + role + kode)
        $defaultPassword = str_replace(' ', '', strtolower($request->namaPengguna) . strtolower($request->rolePengguna) . $kode);

        $pengguna = new Pengguna();
        $pengguna->namaPengguna = $request->namaPengguna;
        $pengguna->emailPengguna = $request->emailPengguna;
        $pengguna->alamatPengguna = $request->alamatPengguna;
        $pengguna->jenisKelamin = $request->jenisKelamin;
        $pengguna->rolePengguna = $request->rolePengguna;
        $pengguna->password = bcrypt($defaultPassword);
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
            'rolePengguna' => 'required|in:admin,sales,manager,gudang',
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

    public function gantiPassForm()
    {
        // Get the authenticated user
        $pengguna = Auth::user();
        return view('edit_pass', compact('pengguna'));
    }

    public function gantiPass(Request $request, string $idPengguna)
    {

        if (!Auth::user()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengubah kata sandi.');
        }

        $pengguna = Pengguna::findOrFail($idPengguna);

        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $pengguna->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Update password
        try {
            $pengguna->password = Hash::make($request->new_password); // Hash password baru
            $pengguna->plaintext_password = $request->new_password; // Opsional: Kosongkan password plaintext
            $pengguna->save(); // Simpan perubahan ke database
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }

        return redirect()->route('profile')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
