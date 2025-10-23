@extends('layouts.main')

@section('title', 'Edit Transaction')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h3 class="mb-4">Edit Transaction</h3>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('transaksis.update', $transaksi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Kasir --}}
                        <div class="mb-3">
                            <label for="nama_kasir" class="form-label">Nama Kasir</label>
                            <input type="text" name="nama_kasir" id="nama_kasir"
                                class="form-control @error('nama_kasir') is-invalid @enderror"
                                value="{{ old('nama_kasir', $transaksi->nama_kasir) }}" required>
                            @error('nama_kasir')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email Pembeli --}}
                        <div class="mb-3">
                            <label for="email_pembeli" class="form-label">Email Pembeli</label>
                            <input type="email" name="email_pembeli" id="email_pembeli"
                                class="form-control @error('email_pembeli') is-invalid @enderror"
                                value="{{ old('email_pembeli', $transaksi->email_pembeli) }}" required>
                            @error('email_pembeli')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Transaksi --}}
                        <div class="mb-4">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d')) }}"
                                required>
                            @error('tanggal_transaksi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Produk & Jumlah --}}
                        <label class="form-label">Pilih Produk & Jumlah</label>
                        <div id="produk-list">
                            @foreach($transaksi->details as $index => $detail)
                                <div class="row align-items-center mb-3 produk-item">
                                    <div class="col-md-6">
                                        <select name="product_id[]" class="form-select" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $product->id == $detail->id_product ? 'selected' : '' }}>
                                                    {{ $product->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="number" name="jumlah_pembelian[]" class="form-control" placeholder="Jumlah"
                                            value="{{ old('jumlah_pembelian.' . $index, $detail->jumlah_pembelian) }}" required>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger remove-produk">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" id="add-produk" class="btn btn-outline-primary">
                                + Tambah Produk
                            </button>
                        </div>

                        <hr class="my-4">

                        {{-- Tombol Aksi --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">💾 Simpan Perubahan</button>
                            <a href="{{ route('transaksis.index') }}" class="btn btn-secondary px-4">⬅️ Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Template Produk --}}
    <template id="produk-template">
        <div class="row align-items-center mb-3 produk-item">
            <div class="col-md-6">
                <select name="product_id[]" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="jumlah_pembelian[]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-danger remove-produk">Hapus</button>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const produkList = document.getElementById('produk-list');
            const addButton = document.getElementById('add-produk');
            const template = document.getElementById('produk-template').content;

            addButton.addEventListener('click', () => {
                produkList.appendChild(template.cloneNode(true));
            });

            produkList.addEventListener('click', e => {
                if (e.target.classList.contains('remove-produk')) {
                    e.target.closest('.produk-item').remove();
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