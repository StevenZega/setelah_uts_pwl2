@extends('layouts.main')

@section('title', 'Add New Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h3 class="mb-4 text-center">🧩 Add New Product</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body p-4">
                <form id="productForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image</label>
                        <input 
                            type="file" 
                            class="form-control @error('image') is-invalid @enderror" 
                            name="image"
                        >
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRODUCT CATEGORY --}}
                    <div class="mb-3">
                        <label for="product_category_id" class="form-label fw-semibold">Product Category</label>
                        <select 
                            class="form-select @error('product_category') is-invalid @enderror" 
                            id="product_category_id" 
                            name="product_category"
                        >
                            <option value="">-- Select Product Category --</option>
                            @foreach($data['categories'] as $category)
                                <option value="{{ $category->id }}">{{ $category->product_category_name }}</option>
                            @endforeach
                        </select>
                        @error('product_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input 
                            type="text" 
                            class="form-control @error('title') is-invalid @enderror" 
                            name="title" 
                            placeholder="Masukkan Judul Product"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SUPPLIER --}}
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label fw-semibold">Supplier</label>
                        <select 
                            class="form-select @error('supplier_id') is-invalid @enderror" 
                            id="supplier_id" 
                            name="supplier_id"
                        >
                            <option value="">-- Select Supplier --</option>
                            @foreach($data['supplier'] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea 
                            class="form-control @error('description') is-invalid @enderror" 
                            name="description" 
                            rows="5" 
                            placeholder="Masukkan Description Product"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRICE & STOCK --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price</label>
                            <input 
                                type="number" 
                                class="form-control @error('price') is-invalid @enderror" 
                                name="price" 
                                placeholder="Masukkan Harga Product"
                            >
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stock</label>
                            <input 
                                type="number" 
                                class="form-control @error('stock') is-invalid @enderror" 
                                name="stock" 
                                placeholder="Masukkan Stock Product"
                            >
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary me-3 px-4">💾 Save</button>
                        <button type="button" id="resetBtn" onclick="resetForm()" class="btn btn-warning me-3 px-4">🔄 Reset</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');

        function resetForm() {
            document.getElementById('productForm').reset();
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setData('');
            }
        }
    </script>
@endpush
