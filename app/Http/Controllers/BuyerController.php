<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;
use App\Models\cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function buyerdash()
    {
        $products = Products::where('status', 'approved')->latest()->get();
        return view('buyer.buyerdash', compact('products'));
    }

    public function viewproducts()
    {
        $products = Products::where('status', 'approved')->latest()->get();
        return view('buyer.products', compact('products'));
    }

    public function buyerCategoryDash(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $selectedCategory = null;
        $products = collect();

        if ($request->filled('category')) {
            $selectedCategory = Category::find($request->category);
            if ($selectedCategory) {
                $products = Products::where('category_id', $selectedCategory->id)->where('status', 'approved')->latest()->get();
            }
        }

        return view('buyer.buyercategorydash', compact('categories', 'selectedCategory', 'products'));
    }

    public function showCart()
    {
        $cartItems = cart::where('buyer_id', Auth::id())->with('products.category')->get();
        return view('buyer.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Products::findOrFail($request->product_id);

        // Safeguard check for available inventory stock
        if ($product->productquantity <= 0) {
            return back()->with('error', 'This product is currently out of stock.');
        }

        $buyer = Auth::user();

        $cartItem = cart::where('buyer_id', $buyer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            cart::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()
            ->with('success', 'Product added to cart.')
            ->with('added_product_id', $product->id);
    }

    public function updateCart(Request $request)
    {
        $cartId = $request->cart_id ?? $request->cart_item_id;

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = cart::where('id', $cartId)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Cart updated successfully.');
    }

    public function removeFromCart(Request $request)
    {
        $cartId = $request->cart_id ?? $request->cart_item_id;

        $cartItem = cart::where('id', $cartId)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart.');
    }

    public function showOrder()
    {
        $order = Order::where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->with('items.product')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('buyer.buyerdash')->with('error', 'No order record found.');
        }

        $estimatedDate = $order->created_at->addDays(4)->format('l, F j, Y');

        return view('buyer.order', compact('order', 'estimatedDate'));
    }

    public function showCheckout()
    {
        $cartItems = cart::where('buyer_id', Auth::id())->with('products.category')->get();

        $subtotal = 0;

        $shippingFee = 2500;
        $vatRate = 0.075;

        foreach ($cartItems as $item) {
            $subtotal += ($item->products->productprice ?? 0) * $item->quantity;
        }

        $vat = $subtotal * $vatRate;
        $total = $subtotal + $shippingFee + $vat;

        return view('buyer.checkout', compact('cartItems', 'subtotal', 'total', 'vat', 'shippingFee'));
    }
}
