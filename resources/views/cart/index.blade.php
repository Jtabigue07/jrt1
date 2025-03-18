@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Shopping Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach(session('cart') as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <tr>
                        <td>
                            @if(isset($item['image']) && !empty($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}" width="50" alt="Product">
                            @else
                                <img src="{{ asset('images/default-product.png') }}" width="50" alt="Default Image">
                            @endif
                        </td>
                        <td>{{ $item['name'] }}</td>
                        <td>₱{{ number_format($item['price'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control d-inline w-50">
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                        <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td>₱{{ number_format($total, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <a href="#" class="btn btn-primary">Proceed to Checkout</a>
    @else
        <p class="text-center">Your cart is empty.</p>
    @endif
</div>
@endsection
