<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
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
