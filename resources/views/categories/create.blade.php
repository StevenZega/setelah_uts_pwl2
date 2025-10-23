@extends('layouts.main')

@section('title', 'Add New Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4 text-center">Add New Category</h3>
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Category</label>
                        <input type="text" name="product_category_name"
                               class="form-control @error('product_category_name') is-invalid @enderror"
                               placeholder="Masukkan nama category"
                               value="{{ old('product_category_name') }}">
                        @error('product_category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary me-3 px-4">💾 Save</button>
                        <button type="reset" class="btn btn-warning me-3 px-4">🔄 Reset</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
