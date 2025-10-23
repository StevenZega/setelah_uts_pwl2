@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
    <h2 class="fw-bold mb-1 page-title">Data Supplier</h2>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <a href="{{ route('suppliers.create') }}" class="btn btn-success mb-3">+ Add Supplier</a>

            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col" style="width: 70px;">ID</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">PIC Supplier</th>
                        <th scope="col" style="width: 20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="text-center">{{ $supplier->id }}</td>
                            <td>{{ $supplier->supplier_name }}</td>
                            <td>{{ $supplier->pic_supplier }}</td>
                            <td class="text-center">
                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-lg btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-lg btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                      class="d-inline form-delete" data-title="{{ $supplier->supplier_name }}">
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
                            <td colspan="4" class="text-center text-muted">Data Supplier Belum Tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
@endsection
