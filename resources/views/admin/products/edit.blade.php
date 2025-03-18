@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    <i class="fas fa-edit"></i> Edit Product
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

                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Image Preview -->
                        <div class="text-center mb-3">
                            <img id="imagePreview" 
                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default.png') }}" 
                                class="rounded border" 
                                style="max-width: 150px; max-height: 150px;">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label"><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label"><i class="fas fa-list-alt"></i> Category</label>
                            <select id="category" class="form-select" name="category" required>
                                <option value="">Select a Category</option>
                                <option value="Prescription" {{ old('category', $product->category) == 'Prescription' ? 'selected' : '' }}>Prescription</option>
                                <option value="OTC" {{ old('category', $product->category) == 'OTC' ? 'selected' : '' }}>OTC (Over-the-Counter)</option>
                                <option value="Supplements" {{ old('category', $product->category) == 'Supplements' ? 'selected' : '' }}>Supplements</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label"><i class="fas fa-dollar-sign"></i> Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                    value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label"><i class="fas fa-boxes"></i> Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" 
                                    value="{{ old('stock', $product->stock) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label"><i class="fas fa-image"></i> Update Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted">Leave empty to keep current image</small>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-2 w-100">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
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
        }
    }
</script>
@endpush
@endsection
