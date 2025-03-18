@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    {{ __('Dashboard') }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="text-center text-success fw-bold">{{ __('You are logged in!') }}</h5>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-user-circle"></i> {{ __('View Profile') }}
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-lg">
                                <i class="fas fa-users-cog"></i> {{ __('Manage Users') }}
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-box-open"></i> {{ __('Manage Products') }}
                            </a>
                        @endif
                    </div>

                    <!-- Available Products -->
                    @if(!Auth::user()->isAdmin())
                        <h3 class="mt-5 text-center fw-bold">Available Products</h3>
                        <div class="row mt-4">
                            @foreach ($products as $product)
                                <div class="col-md-4">
                                    <div class="card product-card shadow-sm border-0">
                                        <div class="position-relative">
                                            @if ($product->image)
                                                <img src="{{ Storage::url($product->image) }}" class="card-img-top product-img" alt="Product Image">
                                            @else
                                                <img src="{{ asset('images/default.png') }}" class="card-img-top product-img" alt="Default Image">
                                            @endif
                                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                                {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                                            
                                            <!-- Display Category -->
                                            <p class="text-primary fw-semibold">{{ $product->category }}</p>

                                            <p class="text-muted">{{ $product->description }}</p>
                                            <p class="fw-bold text-success">₱{{ number_format($product->price, 2) }}</p>
                                            <p class="small text-secondary">Stock: {{ $product->stock }}</p>

                                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap & Custom Styles -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .product-card {
        transition: transform 0.3s ease-in-out;
        border-radius: 10px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
    .product-img {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
</style>
@endpush
@endsection
