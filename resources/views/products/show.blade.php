@extends('layouts.main')

@section('title', 'Product Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h3 class="mb-4 text-center">📦 Product Details</h3>

        <div class="card border-0 shadow-sm rounded">
            <div class="card-body p-4">
                <div class="row">
                    {{-- IMAGE SECTION --}}
                    <div class="col-md-4 mb-4">
                        <div class="border rounded shadow-sm p-2 text-center bg-light">
                            @if($product->image)
                                <img src="{{ asset('storage/images/' . $product->image) }}" 
                                     alt="Product Image"
                                     class="img-fluid rounded"
                                     style="max-height: 280px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/280x280?text=No+Image" 
                                     alt="No Image"
                                     class="img-fluid rounded">
                            @endif
                        </div>
                    </div>

                    {{-- DETAIL SECTION --}}
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-3 text-primary">{{ $product->title }}</h4>
                        <hr>

                        <p><strong>Category:</strong> {{ $product->product_category_name ?? '-' }}</p>
                        <p><strong>Supplier:</strong> {{ $product->supplier_name ?? '-' }}</p>
                        <p><strong>Price:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <p><strong>Stock:</strong> {{ $product->stock }}</p>

                        <p class="mt-3 mb-1"><strong>Description:</strong></p>
                        <div class="border rounded p-3 bg-light text-secondary" style="white-space: pre-line;">
                            {{ $product->description }}
                        </div>

                        {{-- BACK BUTTON --}}
                        <div class="text-end mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">
                                ⬅️ Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
