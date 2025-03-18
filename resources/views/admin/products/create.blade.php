@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    <i class="fas fa-plus-circle"></i> Add New Product
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label"><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label"><i class="fas fa-list-alt"></i> Category</label>
                            <select id="category" class="form-select" name="category" required>
                                <option value="">Select a Category</option>
                                <option value="Prescription">Prescription</option>
                                <option value="OTC">OTC (Over-the-Counter)</option>
                                <option value="Supplements">Supplements</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label"><i class="fas fa-dollar-sign"></i> Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="₱0.00" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label"><i class="fas fa-boxes"></i> Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter stock quantity" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label"><i class="fas fa-image"></i> Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <div class="mt-2">
                                <img id="imagePreview" src="{{ asset('images/default.png') }}" class="rounded border" style="max-width: 100px; max-height: 100px; display: none;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-save"></i> Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
