@extends('layouts.main')

@section('title', 'Detail Supplier')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4 text-center fw-bold">📦 Detail Supplier</h3>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">Informasi Supplier</h5>
                    <hr>

                    <div class="mb-2">
                        <strong>ID:</strong>
                        <p class="mb-0">{{ $supplier->id }}</p>
                    </div>
                    <hr>

                    <div class="mb-2">
                        <strong>Nama Supplier:</strong>
                        <p class="mb-0">{{ $supplier->supplier_name }}</p>
                    </div>
                    <hr>

                    <div class="mb-2">
                        <strong>PIC Supplier:</strong>
                        <p class="mb-0">{{ $supplier->pic_supplier }}</p>
                    </div>
                    <hr>

                    <div class="text-end mt-4">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary px-4">
                            ⬅️ Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
