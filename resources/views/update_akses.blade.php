@extends('layout.master')

@section('content')
<div class="container mt-5"> <!-- Menambahkan margin-top untuk memberi jarak dari navbar -->

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.updateUserGudang', $pengguna->idPengguna) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3 pt-5">
            <label for="namaPengguna" class="form-label">Nama Pengguna</label>
            <input type="text" class="form-control" id="namaPengguna" value="{{ $pengguna->namaPengguna }}" disabled>
        </div>

        <div class="mb-3">
            <label for="idGudang" class="form-label">Pilih Gudang Baru</label>
            <select class="form-select" id="idGudang" name="idGudang" required>
                @foreach ($gudang as $g)
                <option value="{{ $g->idGudang }}" {{ $currentGudang->contains($g->idGudang) ? 'selected' : '' }}>
                    {{ $g->namaGudang }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Pindahkan Akses</button>
    </form>
</div>
@endsection