@extends('layout.master')
@section('title', 'Edit Product')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4 border rounded pb-3 pt-3 mt-5">
            <form action="{{ route('proses_editproduct', ['idProduk' => $produk->idProduk]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                <label for="namaProduk">Nama Produk</label>
                <input type="text" name="namaProduk" id="namaProduk" class="form-control" value="{{$produk->namaProduk}}">
                <label for="hargaCash">Harga Cash</label>
                <input type="text" name="hargaCash" id="hargaCash" class="form-control" value="{{$produk->hargaCash}}">
                <label for="hargaTempo">Harga Tempo</label>
                <input type="text" name="hargaTempo" id="hargaTempo" class="form-control" value="{{$produk->hargaTempo}}">
                <label for="imageAsset">Gambar Produk</label><br>
                <img src="{{asset('/storage/images/' . $produk->imageAsset)}}" alt="current-image" class="w-100">
                <input type="file" name="imageAsset" id="imageAsset" class="form-control">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</div>
@endsection