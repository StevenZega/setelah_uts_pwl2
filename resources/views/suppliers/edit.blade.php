@extends('layouts.main')

@section('title', 'Edit Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-4 text-center">✏️ Edit Supplier</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Supplier Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Supplier Name</label>
                        <input 
                            type="text" 
                            name="supplier_name" 
                            class="form-control @error('supplier_name') is-invalid @enderror"
                            value="{{ old('supplier_name', $supplier->supplier_name) }}"
                            placeholder="Masukkan nama supplier">
                        @error('supplier_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PIC Supplier --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">PIC Supplier</label>
                        <input 
                            type="text" 
                            name="pic_supplier" 
                            class="form-control @error('pic_supplier') is-invalid @enderror"
                            value="{{ old('pic_supplier', $supplier->pic_supplier) }}"
                            placeholder="Masukkan nama PIC">
                        @error('pic_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary me-3">⬅️ Back</a>
                        <button type="reset" class="btn btn-warning me-3">🔄 Reset</button>
                        <button type="submit" class="btn btn-primary">💾 Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
