<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subProducts'])
            ->where('status', 'active')
            ->where('type', 'product');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Category slug filter
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Featured filter
        if ($request->has('featured')) {
            $query->where('is_featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }

        // Type filter
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Ordering
        $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');

        $products = $query->paginate($request->per_page ?? 15);

        return $this->successResponse($products, 'Products retrieved successfully');
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'subProducts'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return $this->successResponse($product, 'Product retrieved successfully');
    }

    public function denominations($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $subProducts = $product->subProducts()
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->get();

        return $this->successResponse($subProducts, 'Denominations retrieved successfully');
    }

    public function featured()
    {
        $products = Product::with(['category', 'subProducts'])
            ->where('status', 'active')
            ->where('type', 'product')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->successResponse($products, 'Featured products retrieved successfully');
    }

    public function joki(Request $request)
    {
        $query = Product::with(['category', 'subProducts'])
            ->where('status', 'active')
            ->where('type', 'joki');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Featured filter
        if ($request->has('featured')) {
            $query->where('is_featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }

        $products = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->successResponse($products, 'Joki services retrieved successfully');
    }
}
