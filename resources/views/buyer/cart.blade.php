<x-layout>
    <x-slot:title>easybuy · Your Bag</x-slot:title>

    @php
        $catalogImages = [
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=600&q=80',
        ];

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->products->productprice ?? 0;
            $subtotal += $itemPrice * $item->quantity;
        }
        $total = $subtotal;
        $totalCount = $cartItems->sum('quantity');
        $tags = ['SALE', 'POPULAR', 'NEW'];
    @endphp

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Header Navigation Bar (Image 2) -->
        <div class="flex items-center justify-end pb-4 mb-6 border-b border-gray-200">
            <a href="{{ route('buyer.browse') }}"
                class="text-sm font-medium text-gray-600 hover:text-black transition flex items-center gap-1.5">
                <span>&larr;</span>
                <span>Continue Shopping</span>
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md flex items-center gap-2 text-sm font-medium shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <!-- Empty Bag State -->
            <div class="bg-white border border-gray-200 p-16 text-center my-8 rounded-none">
                <i class="fa-solid fa-bag-shopping text-5xl text-gray-300 mb-4"></i>
                <h2 class="text-3xl font-serif text-gray-900 mb-2">Your Bag is Empty</h2>
                <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">
                    Looks like you haven't added any items to your bag yet. Explore our latest arrivals to get started.
                </p>
                <a href="{{ route('buyer.browse') }}"
                    class="inline-block px-8 py-3 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-sm rounded transition shadow-sm">
                    Start Shopping &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">

                <!-- Left Column: Items List (approx 7 cols) -->
                <div class="lg:col-span-7">
                    <!-- Heading -->
                    <div class="mb-8">
                        <p class="text-xs font-semibold tracking-widest uppercase text-gray-500 mb-1">
                            {{ $totalCount }} {{ Str::plural('ITEM', $totalCount) }} SELECTED
                        </p>
                        <h1 class="text-4xl sm:text-5xl font-serif text-[#111111] font-normal tracking-tight">
                            Your Bag
                        </h1>
                    </div>

                    <!-- Cart Items -->
                    <div class="divide-y divide-gray-200 border-t border-b border-gray-200">
                        @foreach ($cartItems as $item)
                            @php
                                $product = $item->products;
                                $itemPrice = $product->productprice ?? 0;
                                $lineTotal = $itemPrice * $item->quantity;
                                $itemImage = !empty($product->image_url)
                                    ? $product->image_url
                                    : $catalogImages[$loop->index % count($catalogImages)];
                                $itemTag = $tags[$loop->index % count($tags)];
                            @endphp

                            <div class="py-6 sm:py-8 flex gap-4 sm:gap-6 items-start justify-between group">
                                <!-- Image Thumbnail -->
                                <div
                                    class="w-24 h-24 sm:w-28 sm:h-28 bg-[#f5f5f5] flex-shrink-0 flex items-center justify-center p-2 overflow-hidden">
                                    <img src="{{ $itemImage }}" alt="{{ $product->productname }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>

                                <!-- Middle Info & Controls -->
                                <div class="flex-1 min-w-0 pr-2">
                                    <h3
                                        class="text-base sm:text-lg font-bold text-gray-900 tracking-tight line-clamp-1">
                                        {{ $product->productname }}
                                    </h3>
                                    <p class="text-xs text-gray-500 italic mt-0.5 line-clamp-1">
                                        {{ $product->description }}
                                    </p>
                                    <span
                                        class="inline-block text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">
                                        {{ $product->category->categoryname ?? $itemTag }}
                                    </span>

                                    <!-- Controls Row: Quantity Stepper & Remove -->
                                    <div class="mt-4 flex items-center gap-4">
                                        <!-- Quantity Stepper Form -->
                                        <form action="{{ route('buyer.updateCart') }}" method="POST"
                                            class="inline-flex items-center border border-gray-300 rounded-sm bg-white overflow-hidden">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                            <button type="button"
                                                onclick="let inp = this.parentNode.querySelector('input[name=\'quantity\']'); if(parseInt(inp.value) > 1){ inp.value = parseInt(inp.value) - 1; this.form.submit(); } else if(confirm('Remove this item from your bag?')){ this.form.action='{{ route('buyer.removeFromCart') }}'; this.form.submit(); }"
                                                class="w-7 h-7 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100 hover:text-black transition">
                                                -
                                            </button>
                                            <input type="number" name="quantity" min="1"
                                                value="{{ $item->quantity }}" onchange="this.form.submit()"
                                                class="w-9 text-center text-xs font-semibold text-gray-900 border-x border-gray-200 focus:outline-none py-1">
                                            <button type="button"
                                                onclick="let inp = this.previousElementSibling; inp.value = parseInt(inp.value) + 1; this.form.submit();"
                                                class="w-7 h-7 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100 hover:text-black transition">
                                                +
                                            </button>
                                        </form>

                                        <!-- Remove Link Form -->
                                        <form action="{{ route('buyer.removeFromCart') }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                            <button type="submit"
                                                class="text-xs text-gray-400 hover:text-red-600 transition cursor-pointer">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Right: Price -->
                                <div class="text-right flex-shrink-0">
                                    <span class="text-base sm:text-lg font-bold text-gray-900 whitespace-nowrap">
                                        &#8358;{{ number_format($lineTotal) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Order Summary (approx 5 cols) -->
                <div class="lg:col-span-5 sticky top-24">
                    <div class="bg-[#fafafa] border border-gray-200/80 p-6 sm:p-8">
                        <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">
                            Order Summary
                        </h2>

                        <!-- Line Items -->
                        <div class="space-y-3.5 pb-6 border-b border-gray-200 text-sm text-gray-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">&#8358;{{ number_format($subtotal) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping</span>
                                <span class="italic text-gray-500">Free</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Protection</span>
                                <span class="italic text-gray-500">Included</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="py-6 flex items-baseline justify-between border-b border-gray-200">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-2xl sm:text-3xl font-serif font-bold text-gray-950">
                                &#8358;{{ number_format($total) }}
                            </span>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('buyer.checkout') }}"
                                class="w-full py-3.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-sm rounded-sm transition flex items-center justify-center gap-2 shadow-sm text-center">
                                <span>Place Order</span>
                                <span>&rarr;</span>
                            </a>

                            <a href="{{ route('buyer.browse') }}"
                                class="w-full py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium text-sm rounded-sm transition flex items-center justify-center gap-2 text-center">
                                <span>&larr;</span>
                                <span>Continue Shopping</span>
                            </a>
                        </div>

                        <!-- Trust Bullet Points (Matching Image 2) -->
                        <div class="mt-8 pt-6 border-t border-gray-200 space-y-2 text-xs text-gray-500">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">&bull;</span>
                                <span>Secure checkout</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">&bull;</span>
                                <span>Free delivery on all orders</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">&bull;</span>
                                <span>30-day easy returns</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-layout>
