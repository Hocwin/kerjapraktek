@extends('layout.master')

@section('content')
<div class="container mt-5 pt-5">
    <h1 class="mb-4">Tambah Gudang Baru</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('store_gudang') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="namaGudang">Nama Gudang</label>
            <input type="text" name="namaGudang" id="namaGudang" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="lokasi">Lokasi Gudang</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="imageAsset">Gambar Gudang (opsional)</label>
            <input type="file" name="imageAsset" id="imageAsset" class="form-control">
        </div>

        <h3 class="mt-4">Stok Produk</h3>

        @foreach($produk as $item)
            <div class="form-group mb-3">
                <label for="stok_{{ $item->idProduk }}">Stok untuk {{ $item->namaProduk }}</label>
                <input type="number" name="stok[{{ $item->idProduk }}]" id="stok_{{ $item->idProduk }}" class="form-control" min="0" value="0">
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Simpan Gudang</button>
    </form>
</div>
@endsection
