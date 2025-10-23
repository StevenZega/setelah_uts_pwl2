@extends('layouts.app')

@section('title', 'Data Categories')

@section('content')
    <h2 class="fw-bold mb-1 page-title">Data Categories</h2>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">+ Add Category</a>

            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category Products</th>
                        <th scope="col" style="width: 20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->product_category_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-lg btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-lg btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- form-delete biar SweetAlert di layouts.app yang handle --}}
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                      class="d-inline form-delete" data-title="{{ $category->product_category_name }}">
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
                            <td colspan="3" class="text-center text-muted">Data Category Belum Tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $categories->links() }}
        </div>
    </div>
@endsection
