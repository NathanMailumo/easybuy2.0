<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Products::with('category')->findOrFail($id);
        $catalogImages = [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
        ];

        // Pick a deterministic fallback image based on product ID
        $fallbackImage = $catalogImages[$product->id % count($catalogImages)];

        return view('buyer.show', compact('product', 'fallbackImage'));
    }

    public function sellerdash()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'seller') {
            return redirect()->route('buyer.dashboard');
        }

        return view('seller.sellerdash');
    }

    public function showAddProduct()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'seller') {
            return redirect()->route('buyer.dashboard');
        }

        return view('products.addproduct');
    }

    public function addProduct(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'seller' || !Auth::user()->seller) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'productname' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productprice' => 'required|integer|min:0',
            'productquantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['seller_id'] = Auth::user()->seller->id;
        $validated['status'] = 'waiting';

        Products::create($validated);


        return redirect()->route("products.product")
            ->with('success', 'Product Created Successfully!');
    }

    public function showProduct()
    {
        if (!Auth::check() || Auth::user()->role !== 'seller' || !Auth::user()->seller) {
            return redirect()->route('login');
        }

        $sellerId = Auth::user()->seller->id;

        $products = Products::where('seller_id', $sellerId)
            ->where('status', '!=', 'rejected')
            ->with('category')
            ->latest()
            ->get();

        return view('products.product', compact('products'));
    }

    // public function product()
    // {
    //     $products = Products::all();

    //     return view('products.product', compact('products'));
    // }
    public function destroy(Products $product)
    {
        abort_unless(Auth::check() && Auth::user()->seller && $product->seller_id === Auth::user()->seller->id, 403);

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    public function edit(Products $product)
    {
        abort_unless(Auth::check() && Auth::user()->seller && $product->seller_id === Auth::user()->seller->id, 403);

        return view('products.edit', compact('product'));
    }

    // Update the product in the database
    public function update(Request $request, Products $product)
    {
        abort_unless(Auth::check() && Auth::user()->seller && $product->seller_id === Auth::user()->seller->id, 403);

        $validated = $request->validate([
            'productname' => 'required|string|max:255',
            'description' => 'required|string',
            'productprice' => 'required|numeric',
            'productquantity' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['status'] = 'waiting';
        $validated['is_available'] = true;

        // $isAvailable = $validated['productquantity'] > 0;

        $product->update($validated);

        return redirect()->route('products.product')->with('success', 'Product updated successfully!');
    }
}
