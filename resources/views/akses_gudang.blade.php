@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h2>Atur Akses Pengguna ke Gudang</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Akses Gudang</h3>
        <!-- Tombol untuk Set User Gudang -->
        <a href="{{ route('admin.showFormSetUserGudang') }}" class="btn btn-primary">Set User Gudang</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Gudang yang Dapat Diakses</th>
                <th>Aksi</th> <!-- Kolom Aksi untuk Edit -->
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->namaPengguna }}</td>
                <td>{{ $user->emailPengguna }}</td>
                <td>
                    @if ($user->gudang && $user->gudang->isNotEmpty())
                    <ul>
                        @foreach ($user->gudang as $g)
                        <li>{{ $g->namaGudang }}</li>
                        @endforeach
                    </ul>
                    @else
                    <span class="text-muted">Tidak ada akses gudang</span>
                    @endif
                </td>
                <td>
                    <!-- Tombol Edit Akses -->
                    <a href="{{ route('admin.showEditUserGudang', ['idPengguna' => $user->idPengguna]) }}" class="btn btn-warning">Edit Akses</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data pengguna</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection