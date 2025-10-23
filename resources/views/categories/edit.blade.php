@extends('layouts.main')

@section('title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4 text-center">Edit Category</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Category Name --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Category Name</label>
                        <input 
                            type="text" 
                            name="product_category_name" 
                            class="form-control @error('product_category_name') is-invalid @enderror"
                            value="{{ old('product_category_name', $category->product_category_name) }}"
                            placeholder="Masukkan nama category">

                        @error('product_category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary me-3 px-4">⬅️ Back</a>
                        <button type="reset" class="btn btn-warning me-3 px-4">🔄 Reset</button>
                        <button type="submit" class="btn btn-primary px-4">💾 Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
