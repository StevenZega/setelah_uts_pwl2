@extends('layouts.app')

@section('title', 'Data Products')

@section('content')
    <h2 class="fw-bold mb-1 page-title">Data Products</h2>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <a href="{{ route('products.create') }}" class="btn btn-success mb-3">+ Add Product</a>

            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Title</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th style="width: 20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="text-center">
                                <img src="{{ asset('storage/images/' . $product->image) }}" 
                                     alt="{{ $product->title }}" 
                                     class="img-thumbnail"
                                     style="max-height: 80px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->supplier_name }}</td>
                            <td>{{ $product->product_category_name }}</td>
                            <td>{{ 'Rp' . number_format($product->price, 2, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-center">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-lg btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-lg btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      class="d-inline form-delete" data-title="{{ $product->title }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-lg btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No Products Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection