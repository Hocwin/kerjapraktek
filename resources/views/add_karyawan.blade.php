@extends('layout.master')

@section('content')
<div class="container mt-5 pt-5">
    <h2>Tambah Karyawan</h2>
    <form action="{{ route('store_karyawan') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="namaPengguna" class="form-label">Nama Karyawan</label>
            <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" placeholder="Masukkan nama" required>
        </div>
        <div class="mb-3">
            <label for="emailPengguna" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailPengguna" name="emailPengguna" placeholder="Masukkan email" required>
        </div>
        <div class="mb-3">
            <label for="alamatPengguna" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamatPengguna" name="alamatPengguna" placeholder="Masukkan alamat" required>
        </div>
        <div class="mb-3">
            <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenisKelamin" name="jenisKelamin" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="rolePengguna" class="form-label">Role</label>
            <select class="form-select" id="rolePengguna" name="rolePengguna" required>
                <option value="admin">Admin</option>
                <option value="sales">Sales</option>
                <option value="manager">Manager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('karyawan') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection