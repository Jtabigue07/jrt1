<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // ✅ Correct import

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::where('status', true)->get(); // Fetch active products
        return view('home', compact('products')); // Pass to the view
    }
}
