@extends('layouts.main')

@section('title', 'Show Transaction')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-center mb-4 text-dark">📦 Detail Transaksi Penjualan</h3>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">

                    {{-- Informasi Transaksi --}}
                    <div class="mb-5">
                        <h5 class="fw-bold mb-3 text-primary">Informasi Transaksi</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID Transaksi:</strong></td>
                                <td>TRX-0{{ $transaksi->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Kasir:</strong></td>
                                <td>{{ $transaksi->nama_kasir }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email Pembeli:</strong></td>
                                <td>{{ $transaksi->email_pembeli }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Transaksi:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y, H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Rincian Produk --}}
                    <h5 class="fw-bold mb-3 text-primary">Rincian Produk</h5>
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($transaksi->details as $detail)
                                @php 
                                    $hargaSatuan = $detail->product->price;
                                    $subtotal = $detail->jumlah_pembelian * $hargaSatuan;
                                    $grandTotal += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $detail->product->title }}</td>
                                    <td>{{ $detail->jumlah_pembelian }}</td>
                                    <td>Rp{{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-warning fw-bold">
                                <td colspan="3" class="text-end">Grand Total</td>
                                <td>Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Tombol Kembali --}}
                    <div class="text-end mt-4">
                        <a href="{{ route('transaksis.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
