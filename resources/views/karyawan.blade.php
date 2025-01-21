@extends('layout.master')
@section('title', 'Halaman Daftar Karyawan')
<style>
    .karyawan-container {
      padding-top: 125px;
    }

    .aksi-btn {
      display: flex;
      flex-direction: flex;
      justify-content: center;
      align-items: center;
      gap: 5px;
    }

    .edit-btn,
    .detail-btn,
    .add-btn {
      background-color: transparent;
      border: none;
      color: #007bff;
      padding: 6px 12px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: underline;
    }

    .detail-btn {
      color: rgb(220, 106, 53);
    }

    .detail-btn:hover {
      color: #b02a37;
    }

    .status-lunas {
      color: green;
      font-weight: bold;
    }

    .status-belum-lunas {
      color: red;
      font-weight: bold;
    }

    .add-btn-container {
      text-align: right;
      margin-bottom: 20px;
    }

    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      padding: 10px;
      background-color: #f8f9fa;
    }

    .table td {
      vertical-align: middle;
    }

    .table th,
    .table td {
      text-align: center;
    }

    .table td.aksi-btn {
      padding-top: 10px;
    }
  </style>

@section('content')
<div class="container karyawan-container">
    <h2>Daftar Karyawan</h2>
    <div class="add-btn-container">
        @if (Auth::check() && Auth::user()->rolePengguna == 'manager')
            <form method="GET" action="{{ route('add_karyawan') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Tambah Karyawan</button>
            </form>
        @endif
    </div>
    <div class="table-responsive" style="height: 400px; overflow-y: scroll;">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Role</th>
                <th>Aksi</th>
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
            <td>
                <a href="{{ route('edit_karyawan', ['idPengguna' => $item->idPengguna]) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('destroy_karyawan', ['idPengguna' => $item->idPengguna]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger " onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">Hapus</button>
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
