@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h2>Edit Karyawan</h2>
    <form action="{{ route('update_karyawan', $pengguna->idPengguna) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="namaPengguna" class="form-label">Nama Karyawan</label>
            <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" value="{{ $pengguna->namaPengguna }}" required>
        </div>
        <div class="mb-3">
            <label for="emailPengguna" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailPengguna" name="emailPengguna" value="{{ $pengguna->emailPengguna }}" required>
        </div>
        <div class="mb-3">
            <label for="alamatPengguna" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamatPengguna" name="alamatPengguna" value="{{ $pengguna->alamatPengguna }}" required>
        </div>
        <div class="mb-3">
            <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenisKelamin" name="jenisKelamin" required>
                <option value="L" {{ $pengguna->jenisKelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $pengguna->jenisKelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="rolePengguna" class="form-label">Role</label>
            <select class="form-select" id="rolePengguna" name="rolePengguna" required>
                <option value="admin" {{ $pengguna->rolePengguna == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="sales" {{ $pengguna->rolePengguna == 'sales' ? 'selected' : '' }}>Sales</option>
                <option value="manager" {{ $pengguna->rolePengguna == 'manager' ? 'selected' : '' }}>Manager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('karyawan') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection