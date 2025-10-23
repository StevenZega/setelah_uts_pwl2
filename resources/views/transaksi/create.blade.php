@extends('layouts.main')

@section('title', 'Add New Transaction')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h3 class="mb-4">Add New Transaction</h3>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form id="transaksiForm" action="{{ route('transaksis.store') }}" method="POST">
                        @csrf

                        {{-- Nama Kasir --}}
                        <div class="mb-3">
                            <label for="nama_kasir" class="form-label">Nama Kasir</label>
                            <input type="text" name="nama_kasir"
                                class="form-control @error('nama_kasir') is-invalid @enderror"
                                placeholder="Masukkan Nama Kasir">
                            @error('nama_kasir')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email Pembeli --}}
                        <div class="mb-3">
                            <label for="email_pembeli" class="form-label">Email Pembeli</label>
                            <input type="email" name="email_pembeli"
                                class="form-control @error('email_pembeli') is-invalid @enderror"
                                placeholder="Masukkan Email Pembeli">
                            @error('email_pembeli')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Transaksi --}}
                        <div class="mb-4">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi"
                                class="form-control @error('tanggal_transaksi') is-invalid @enderror">
                            @error('tanggal_transaksi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        {{-- Produk --}}
                        <h5 class="mb-3 text-primary">Daftar Produk Dibeli</h5>
                        <div id="product-container">
                            <div class="product-group mb-3">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label for="product_id[]" class="form-label">Pilih Produk</label>
                                        <select name="product_id[]" class="form-select">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->title }} - Rp{{ number_format($product->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="jumlah_pembelian[]" class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah_pembelian[]" class="form-control" min="1"
                                            placeholder="Jumlah">
                                    </div>

                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-product">&times;</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="button" id="addProduct" class="btn btn-sm btn-outline-primary">
                                + Tambah Produk
                            </button>
                        </div>

                        <hr class="my-4">

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-3 px-4">💾 Save</button>
                            <button type="button" onclick="resetForm()" class="btn btn-warning me-3 px-4">🔄 Reset</button>
                            <a href="{{ route('transaksis.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function resetForm() {
            document.getElementById('transaksiForm').reset();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('product-container');
            const tambahBtn = document.getElementById('addProduct');

            tambahBtn.addEventListener('click', () => {
                const item = document.querySelector('.product-group').cloneNode(true);
                item.querySelectorAll('select, input').forEach(el => {
                    if (el.tagName === 'INPUT') el.value = '';
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                });
                container.appendChild(item);
            });

            container.addEventListener('click', e => {
                if (e.target.classList.contains('remove-product') && container.children.length > 1) {
                    e.target.closest('.product-group').remove();
                }
            });
        });
    </script>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    function checkDuplicateProducts() {
        const selects = document.querySelectorAll('select[name="product_id[]"]');
        const selectedValues = [];

        selects.forEach(select => {
            const value = select.value;

            if (value && selectedValues.includes(value)) {
                Swal.fire({
                    icon: "warning",
                    title: "Produk sudah dipilih!",
                    text: "Kamu tidak bisa memilih produk yang sama dua kali.",
                    showConfirmButton: false,
                    timer: 2000
                });
                select.value = ""; // reset pilihan
            } else if (value) {
                selectedValues.push(value);
            }
        });
    }

    document.addEventListener("change", function (e) {
        if (e.target && e.target.matches('select[name="product_id[]"]')) {
            checkDuplicateProducts();
        }
    });
});
</script>
@endpush
