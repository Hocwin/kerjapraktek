@extends('layout.master')
@section('title', 'Edit Product')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4 border rounded pb-3 pt-3 mt-5">
            <form action="{{ route('proses-editgudang', ['idGudang' => $gudang->idGudang]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                <label for="namaProduk">Nama Gudang</label>
                <input type="text" name="namaGudang" id="namaGudang" class="form-control" value="{{$gudang->namaGudang}}">
                <label for="hargaCash">Lokasi</label>

                <!-- Tambahkan Input untuk Stok -->
                <label>Stok Produk</label>
                    <ul class="list-group">
                        @foreach($gudang->stokPerGudang as $stok)
                            <li class="list-group-item">
                                {{ $stok->produk->namaProduk }}:
                                <input type="number" name="stok[{{ $stok->idProduk }}]" class="form-control" value="{{ $stok->stok }}">
                            </li>
                        @endforeach
                    </ul>
                    
                <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{$gudang->lokasi}}">
                <label for="imageAsset">Gambar Gudang</label><br>
                <img src="{{asset('/storage/images/' . $gudang->imageAsset)}}" alt="current-image" class="w-100">
                <input type="file" name="imageAsset" id="imageAsset" class="form-control">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</div>
@endsection