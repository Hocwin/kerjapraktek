@extends('layout.master')

@section('title', 'Ubah Kata Sandi')

@section('content')
<style>
    /* Custom Styles */
    .form-container {
        margin-top: 125px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f9f9;
    }

    .form-container img {
        max-width: 100px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .form-container label {
        font-weight: bold;
        margin-top: 10px;
    }

    .form-container input,
    .form-container select,
    .form-container button {
        margin-bottom: 15px;
        font-size: 14px;
        padding: 10px;
    }

    .form-container button {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
    }

    .alert {
        margin-bottom: 20px;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .form-container {
            margin: 0 10px;
        }

        .container {
            padding-left: 0;
            padding-right: 0;
        }

        .col-4 {
            display: none;
        }

        .col-8 {
            width: 100%;
        }
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4 form-container">
            <form action="{{ route('gantiPass', $pengguna->idPengguna) }}" method="POST">
                @csrf

                <!-- Menampilkan Error -->
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach

                <!-- Kata Sandi Saat Ini -->
                <label for="current_password">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>

                <!-- Kata Sandi Baru -->
                <label for="new_password">Kata Sandi Baru</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>

                <!-- Konfirmasi Kata Sandi Baru -->
                <label for="new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</div>

@endsection