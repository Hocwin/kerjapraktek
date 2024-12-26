@extends('layout.master')

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        padding-top: 150px;
    }


    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: 3px solid #004085;
        /* Add accent border for header */
    }

    .card-header h3 {
        font-size: 1.5rem;
        font-weight: bold;
    }


    .table {
        margin-top: 20px;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-bordered {
        border-color: #dee2e6;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
        font-size: 0.9rem;
    }

    /* Table header background */
    .table-light {
        background-color: #e9ecef;
        color: #495057;
    }

    /* Zebra stripes */
    .table tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #e2e6ea;
    }


    .btn-sm {
        padding: 6px 12px;
        border-radius: 25px;
        font-size: 0.85rem;
    }


    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        font-size: 0.9rem;
    }


    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
        font-size: 0.9rem;
    }


    .card-footer {
        background-color: #f1f1f1;
        font-size: 0.85rem;
    }


    .btn:hover {
        opacity: 0.9;
        transition: 0.2s ease-in-out;
    }


    .btn-light {
        font-weight: bold;
        color: #004085;
        border: 1px solid #004085;
    }

    .btn-light:hover {
        background-color: #004085;
        color: #fff;
    }


    form button {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    form button:hover {
        transform: scale(1.05);
    }
</style>

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Daftar Karyawan</h3>
                    <a href="{{ route('add_karyawan') }}" class="btn btn-light btn-sm">Tambah Karyawan</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <table class="table table-bordered table-hover mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengguna as $item)
                            <tr>
                                <td>{{ $item->namaPengguna }}</td>
                                <td>{{ $item->emailPengguna }}</td>
                                <td>{{ $item->alamatPengguna }}</td>
                                <td>{{ $item->jenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ ucfirst($item->rolePengguna) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('edit_karyawan', ['idPengguna' => $item->idPengguna]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('destroy_karyawan', ['idPengguna' => $item->idPengguna]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted text-center">
                    Total Karyawan: {{ $pengguna->count() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection