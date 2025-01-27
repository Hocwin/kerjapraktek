@extends('layout.master')
@section('title', 'Edit Gudang')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 border rounded p-4 bg-light shadow">
            <h4 class="text-center mb-4">Edit Gudang</h4>
            <form action="{{ route('proses-inputgudang', ['idGudang' => $gudang->idGudang]) }}" method="POST" enctype="multipart/form-data">
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

                <!-- Pemasukan Produk -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Pemasukan Produk</label>
                    <ul class="list-group">
                        @foreach($gudang->stokPerGudang as $stok)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stok->produk->namaProduk }}
                            <input type="number" name="pemasukan[{{ $stok->idProduk }}]" class="form-control w-50" min="0" placeholder="Input pemasukan" required>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Retur Produk -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Retur Produk</label>
                    <ul class="list-group">
                        @foreach($gudang->stokPerGudang as $stok)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stok->produk->namaProduk }}
                            <input type="number" name="retur[{{ $stok->idProduk }}]" class="form-control w-50" min="0" placeholder="Input retur">
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Upload Bukti -->
                <div class="mb-4">
                    <label for="gambarBukti" class="form-label fw-bold">Upload Bukti</label>
                    <input type="file" name="gambarBukti" id="gambarBukti" accept="image/*" class="form-control">
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