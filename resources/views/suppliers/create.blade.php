@extends('layouts.main')

@section('title', 'Add New Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4 text-center">Add New Supplier</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf

                    {{-- Supplier Name --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">Supplier Name</label>
                        <input 
                            type="text" 
                            name="supplier_name"
                            class="form-control @error('supplier_name') is-invalid @enderror" 
                            placeholder="Masukkan nama supplier" 
                            value="{{ old('supplier_name') }}">
                        @error('supplier_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PIC Supplier --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">PIC Supplier</label>
                        <input 
                            type="text" 
                            name="pic_supplier"
                            class="form-control @error('pic_supplier') is-invalid @enderror" 
                            placeholder="Masukkan nama PIC" 
                            value="{{ old('pic_supplier') }}">
                        @error('pic_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary me-3 px-4">💾 Save</button>
                        <button type="reset" class="btn btn-warning me-3 px-4">🔄 Reset</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
