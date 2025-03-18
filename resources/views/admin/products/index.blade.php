@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Products Management') }}</span>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Add New Product</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="products-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th> <!-- Added Category -->
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#products-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{{ route('admin.products.list') }}",
            columns: [
                { data: 'id' },
                { 
                    data: 'image', 
                    render: function(data) {
                        return `<img src="${data ? '/storage/' + data : 'https://via.placeholder.com/50'}" 
                                     class="rounded-circle" alt="Product Image" 
                                     style="width: 50px; height: 50px;">`;
                    } 
                },
                { data: 'name' },
                { data: 'category' }, // Display category
                { 
                    data: 'price',
                    render: function(data) {
                        return '₱' + parseFloat(data).toFixed(2); // Changed to PHP currency
                    }
                },
                { data: 'stock' },
                { 
                    data: 'status',
                    render: function(data) {
                        return data ? 'Active' : 'Inactive';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="/admin/products/${row.id}/edit" class="btn btn-sm btn-info">Edit</a>
                            <button class="btn btn-sm btn-danger delete-product" data-id="${row.id}">Delete</button>
                        `;
                    }
                }
            ]
        });

        // Handle product deletion
        $(document).on('click', '.delete-product', function() {
            const productId = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: `/admin/products/${productId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Product deleted successfully');
                        $('#products-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error deleting product');
                    }
                });
            }
        });
    });
</script>
@endpush
