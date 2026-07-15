<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return $this->successResponse($categories, 'Categories retrieved successfully');
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->with(['products' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        return $this->successResponse($category, 'Category retrieved successfully');
    }

    public function products($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = $category->products()
            ->with(['subProducts'])
            ->where('status', 'active');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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

        $products = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->successResponse($products, 'Category products retrieved successfully');
    }
}
