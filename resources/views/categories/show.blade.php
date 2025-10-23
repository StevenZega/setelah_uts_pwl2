@extends('layouts.main')

@section('title', 'Show Category')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4 text-center fw-bold text-dark">📦 Category Details</h3>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <h4 class="fw-semibold mb-3 text-primary">Category Information</h4>
                    <hr>
                    <p><strong>ID:</strong> {{ $category->id ?? '-' }}</p>
                    <hr>
                    <p><strong>Category:</strong> {{ $category->product_category_name ?? '-' }}</p>
                    <hr>

                    {{-- Back Button --}}
                    <div class="text-end mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary px-4">⬅️ Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
