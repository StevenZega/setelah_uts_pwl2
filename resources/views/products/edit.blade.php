@extends('layouts.main')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h3 class="mb-4 text-center">✏️ Edit Product</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body p-4">
                <form 
                    action="{{ route('products.update', $data['product']->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    {{-- IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image</label>
                        @if($data['product']->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/images/' . $data['product']->image) }}" 
                                     alt="Current Image" 
                                     class="img-thumbnail rounded" 
                                     style="max-width: 120px;">
                            </div>
                            <input type="hidden" name="old_image" value="{{ $data['product']->image }}">
                        @endif
                        <input 
                            type="file" 
                            class="form-control @error('image') is-invalid @enderror" 
                            name="image"
                            accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRODUCT CATEGORY --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Category</label>
                        <select 
                            class="form-select @error('product_category') is-invalid @enderror" 
                            name="product_category">
                            <option value="">-- Select Category Product --</option>
                            @foreach($data['categories'] as $category)
                                <option 
                                    value="{{ $category->id }}" 
                                    {{ old('product_category', $data['product']->product_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->product_category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SUPPLIER --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Supplier</label>
                        <select 
                            class="form-select @error('supplier_id') is-invalid @enderror" 
                            name="supplier_id">
                            <option value="">-- Select Supplier --</option>
                            @foreach($data['suppliers_'] as $supplier)
                                <option 
                                    value="{{ $supplier->id }}" 
                                    {{ old('supplier_id', $data['product']->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
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
                            value="{{ old('title', $data['product']->title) }}"
                            placeholder="Masukkan Judul Product">
                        @error('title')
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
                            placeholder="Masukkan Description Product">{{ old('description', $data['product']->description) }}</textarea>
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
                                value="{{ old('price', $data['product']->price) }}" 
                                placeholder="Masukkan Harga Product">
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
                                value="{{ old('stock', $data['product']->stock) }}" 
                                placeholder="Masukkan Stock Product">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary me-3">⬅️ Back</a>
                        <button type="reset" class="btn btn-warning me-3">🔄 Reset</button>
                        <button type="submit" class="btn btn-primary">💾 Update</button>
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
</script>
@endpush
