@extends('layout.master')
@section('title', 'Edit Gudang')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4 border rounded pb-3 pt-3 mt-5" style="height: 850px;overflow-y: scroll;">
            <form action="{{ route('proses-editgudang', ['idGudang' => $gudang->idGudang]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                <label for="namaProduk">Nama Gudang</label>
                <input type="text" name="namaGudang" id="namaGudang" class="form-control" value="{{$gudang->namaGudang}}">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{$gudang->lokasi}}">

                <div class="mb-3">
                    <label class="form-label">Pemasukan Produk</label>
                    <ul class="list-group">
                        @foreach($gudang->stokPerGudang as $stok)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stok->produk->namaProduk }}
                            <input type="number" name="pemasukan[{{ $stok->idProduk }}]" class="form-control w-50" min="0" placeholder="Input pemasukan" required>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mb-3">
                    <label class="form-label">Retur Produk</label>
                    <ul class="list-group">
                        @foreach($gudang->stokPerGudang as $stok)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stok->produk->namaProduk }}
                            <input type="number" name="retur[{{ $stok->idProduk }}]" class="form-control w-50" min="0" placeholder="Input retur">
                        </li>
                        @endforeach
                    </ul>
                </div>


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