<?php

namespace App\Http\Controllers;
use App\Models\OrderLine; // Import OrderLine
use App\Models\OrderInfo;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);

        // Add product to cart
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + 1
        ];

        Session::put('cart', $cart);

        return redirect()->back()->with('status', 'Product added to cart!');
    }
 
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }


    public function checkout(Request $request)
{
    // Get the cart items from the session
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    // Calculate the total amount
    $totalAmount = 0;
    foreach ($cart as $id => $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Create a new order
    $order = OrderLine::create([
        'user_id' => auth()->id(),
        'total_amount' => $totalAmount,
    ]);

    // Add items to the order
    foreach ($cart as $id => $item) {
        OrderInfo::create([
            'order_id' => $order->order_id,
            'item_id' => $id,
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
            'total_price' => $item['price'] * $item['quantity'],
        ]);
    }

    // Clear the cart
    session()->forget('cart');

    return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
}

public function orders()
{
    $orders = OrderLine::where('user_id', auth()->id())->with('items.product')->get();
    return view('cart.orders', compact('orders'));
}
}
    
    



