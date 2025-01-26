@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h1 class="mt-4 pt-5 text-center">Histori Gudang</h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th class="col-2">Tanggal</th>
                    <th class="col-3">User</th>
                    <th class="col-2">Aksi</th>
                    <th class="col-3">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                <tr>
                    <td class="text-center">{{ $history->created_at->format('d-m-Y H:i') }}</td>
                    <td class="text-center">{{ $history->pengguna->namaPengguna ?? 'Tidak diketahui' }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary text-uppercase">{{ ucfirst($history->action) }}</span>
                    </td>
                    <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;">
                        <div class="bg-light p-3 rounded" style="max-height: 150px; overflow-y: auto; white-space: normal;">
                            @php
                            // Decode the JSON details
                            $details = json_decode($history->details, true);
                            @endphp
                            @if($details && isset($details['changes']) && is_array($details['changes']))
                            @foreach ($details['changes'] as $change)
                            @if(isset($change['idProduk']))
                            <p class="mb-1"><strong>ID Produk:</strong> {{ $change['idProduk'] }}</p>
                            @endif
                            @if(isset($change['jumlahPemasukan']))
                            <p class="mb-1"><strong>Jumlah Pemasukan:</strong> {{ $change['jumlahPemasukan'] }}</p>
                            @endif
                            @if(isset($change['jumlahRetur']))
                            <p class="mb-1"><strong>Jumlah Retur:</strong> {{ $change['jumlahRetur'] }}</p>
                            @endif
                            @if(isset($change['hargaRetur']))
                            <p class="mb-1"><strong>Harga Retur:</strong> {{ number_format($change['hargaRetur'], 0, ',', '.') }}</p>
                            @endif
                            <hr>
                            @endforeach
                            @else
                            <p class="text-muted">Tidak ada detail yang tersedia.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada histori untuk gudang ini.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection