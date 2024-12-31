@extends('layout.master')

@section('content')
<style>
    .add-employee-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        padding: 30px;
    }

    .form-group label {
        font-weight: bold;
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

    .submit-btn {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .back-btn {
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
        margin-top: 10px;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }
</style>

<div class="container add-employee-container">
    <h2 class="text-center mb-4">Tambah Karyawan</h2>

    <!-- Tampilkan Error Jika Ada -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Tambah Karyawan -->
    <form action="{{ route('store_karyawan') }}" method="POST">
        @csrf

        <!-- Nama Karyawan -->
        <div class="mb-3">
            <label for="namaPengguna" class="form-label">Nama Karyawan</label>
            <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" placeholder="Masukkan nama" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="emailPengguna" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailPengguna" name="emailPengguna" placeholder="Masukkan email" required>
        </div>

        <!-- Alamat -->
        <div class="mb-3">
            <label for="alamatPengguna" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamatPengguna" name="alamatPengguna" placeholder="Masukkan alamat" required>
        </div>

        <!-- Jenis Kelamin -->
        <div class="mb-3">
            <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenisKelamin" name="jenisKelamin" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="rolePengguna" class="form-label">Role</label>
            <select class="form-select" id="rolePengguna" name="rolePengguna" required>
                <option value="admin">Admin</option>
                <option value="sales">Sales</option>
                <option value="manager">Manager</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="submit-btn">Tambah</button>
    </form>
</div>
@endsection