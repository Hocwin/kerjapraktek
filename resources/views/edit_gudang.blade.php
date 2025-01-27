@extends('layout.master')
@section('title', 'Edit Gudang')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 border rounded p-4 bg-light shadow">
            <h4 class="text-center mb-4">Edit Gudang</h4>
            <form action="{{ route('proses-editgudang', ['idGudang' => $gudang->idGudang]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Nama Gudang -->
                <div class="mb-3">
                    <label for="namaGudang" class="form-label fw-bold">Nama Gudang</label>
                    <input type="text" name="namaGudang" id="namaGudang" class="form-control" value="{{ $gudang->namaGudang }}" required>
                </div>

                <!-- Lokasi Gudang -->
                <div class="mb-3">
                    <label for="lokasi" class="form-label fw-bold">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ $gudang->lokasi }}" required>
                </div>

                <!-- Gambar Gudang -->
                <div class="mb-3">
                    <label for="imageAsset" class="form-label fw-bold">Gambar Gudang</label><br>
                    <img src="{{ asset('/storage/images/' . $gudang->imageAsset) }}" alt="current-image" class="w-100 rounded mb-3">
                    <input type="file" name="imageAsset" id="imageAsset" class="form-control">
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection