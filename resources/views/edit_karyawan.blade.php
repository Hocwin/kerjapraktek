@extends('layout.master')

@section('title', 'Edit Karyawan')

<style>
    .form-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
    }

    .form-control {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-select {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        border-color: #007bff;
    }

    .submit-btn {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .form-container select,
    .form-container input {
        background-color: #ffffff;
    }
</style>

@section('content')
<div class="container form-container">
    <h2>Edit Karyawan</h2>
    <form action="{{ route('update_karyawan', $pengguna->idPengguna) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Karyawan -->
        <div class="form-group">
            <label for="namaPengguna">Nama Karyawan</label>
            <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" value="{{ $pengguna->namaPengguna }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="emailPengguna">Email</label>
            <input type="email" class="form-control" id="emailPengguna" name="emailPengguna" value="{{ $pengguna->emailPengguna }}" required>
        </div>

        <!-- Alamat -->
        <div class="form-group">
            <label for="alamatPengguna">Alamat</label>
            <input type="text" class="form-control" id="alamatPengguna" name="alamatPengguna" value="{{ $pengguna->alamatPengguna }}" required>
        </div>

        <!-- Jenis Kelamin -->
        <div class="form-group">
            <label for="jenisKelamin">Jenis Kelamin</label>
            <select class="form-select" id="jenisKelamin" name="jenisKelamin" required>
                <option value="L" {{ $pengguna->jenisKelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $pengguna->jenisKelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <!-- Role -->
        <div class="form-group">
            <label for="rolePengguna">Role</label>
            <select class="form-select" id="rolePengguna" name="rolePengguna" required>
                <option value="admin" {{ $pengguna->rolePengguna == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="sales" {{ $pengguna->rolePengguna == 'sales' ? 'selected' : '' }}>Sales</option>
                <option value="manager" {{ $pengguna->rolePengguna == 'manager' ? 'selected' : '' }}>Manager</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="submit-btn w-100 mt-3">Simpan</button>
    </form>
</div>
@endsection