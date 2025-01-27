@extends('layout.master')

@section('content')
<div class="container mt-5">
<h1 class="mt-4 pt-5 text-center">Retur {{ $gudang->namaGudang }}</h1>


    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th class="col-2">Tanggal</th>
                    <th class="col-3">User</th>
                    <th class="col-3">Detail</th>
                    <th class="col-2">Bukti Gambar</th> <!-- New column for image -->
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                <tr>
                    <td class="text-center">{{ $history->created_at->format('d-m-Y H:i') }}</td>
                    <td class="text-center">{{ $history->pengguna->namaPengguna ?? 'Tidak diketahui' }}</td>

                    <td>
                        <div class="bg-light p-3 rounded" style="max-height: 150px; overflow-y: auto; white-space: normal;">
                            @php
                            // Decode the JSON details for easier handling
                            $details = json_decode($history->details, true);
                            @endphp

                            @if($details && isset($details['retur']) && is_array($details['retur']))
                            <ul class="list-unstyled">
                                @foreach ($details['retur'] as $retur)
                                @if(isset($retur['idProduk']))
                                <li>
                                    <strong>ID Produk:</strong> {{ $retur['idProduk'] }}<br>
                                    @if(isset($retur['jumlahRetur']))
                                    <strong>Jumlah Retur:</strong> {{ $retur['jumlahRetur'] }}<br>
                                    @endif
                                    @if(isset($retur['hargaRetur']))
                                    <strong>Harga Retur:</strong> {{ number_format($retur['hargaRetur'], 0, ',', '.') }}<br>
                                    @endif
                                </li>
                                <hr>
                                @endif
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted">Tidak ada detail retur yang tersedia.</p>
                            @endif

                            @if($details && isset($details['changes']) && is_array($details['changes']))
                            <ul class="list-unstyled">
                                @foreach ($details['changes'] as $change)
                                <li>
                                    @if(isset($change['idProduk']))
                                    <strong>ID Produk:</strong> {{ $change['idProduk'] }}<br>
                                    @endif
                                    @if(isset($change['jumlahPemasukan']))
                                    <strong>Jumlah Pemasukan:</strong> {{ $change['jumlahPemasukan'] }}<br>
                                    @endif
                                </li>
                                <hr>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted">Tidak ada perubahan yang tersedia.</p>
                            @endif
                        </div>
                    </td>

                    <!-- New Column for Bukti Gambar -->
                    <td class="text-center">
                        @php
                        // Assuming 'bukti_gambar' field in the 'details' json
                        $imagePath = isset($details['bukti_gambar']) ? $details['bukti_gambar'] : null;
                        @endphp
                        @if($imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Bukti Gambar" class="img-fluid" style="max-width: 150px; max-height: 100px;">
                        @else
                        <p class="text-muted">Tidak ada gambar.</p>
                        @endif

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