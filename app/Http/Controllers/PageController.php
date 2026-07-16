<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'featuredProducts' => [
                ['id' => 1, 'name' => 'Mobile Legends Diamonds', 'price' => 50000],
                ['id' => 2, 'name' => 'PUBG Mobile UC', 'price' => 75000],
            ]
        ]);
    }

    public function products()
    {
        return Inertia::render('Products/Index', [
            'products' => [
                ['id' => 1, 'name' => 'Mobile Legends Diamonds', 'price' => 50000],
                ['id' => 2, 'name' => 'PUBG Mobile UC', 'price' => 75000],
                ['id' => 3, 'name' => 'Free Fire Diamonds', 'price' => 25000],
            ]
        ]);
    }

    public function productDetail($id)
    {
        return Inertia::render('Products/Show', [
            'product' => ['id' => $id, 'name' => 'Dummy Product', 'price' => 100000]
        ]);
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard/Index', [
            'recentTransactions' => []
        ]);
    }

    public function checkout()
    {
        return Inertia::render('Checkout/Index', [
            'cart' => []
        ]);
    }
}
