@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h2>Form Set Akses Gudang</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.setUserGudang') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="idPengguna" class="form-label">Pilih Pengguna</label>
            <select class="form-select" name="idPengguna" id="idPengguna" required>
                <option value="">-- Pilih Pengguna --</option>
                @foreach ($users as $user)
                <option value="{{ $user->idPengguna }}">{{ $user->namaPengguna }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="idGudang" class="form-label">Pilih Gudang</label>
            <select class="form-select" name="idGudang" id="idGudang" required>
                <option value="">-- Pilih Gudang --</option>
                @foreach ($gudang as $g)
                <option value="{{ $g->idGudang }}">{{ $g->namaGudang }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.showSetUserGudang') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection