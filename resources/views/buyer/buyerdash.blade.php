<x-layout>
    <x-slot:title>easybuy · Buyer Dashboard</x-slot:title>

    @php
        $existingCategories = \App\Models\Category::withCount('products')->get();
        $catalogImages = [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
        ];
        $badges = ['New', 'Popular', 'Sale'];
    @endphp

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Welcome Banner -->
        <div class="bg-white border border-gray-200 p-6 sm:p-10 mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <span class="text-[11px] font-bold tracking-widest uppercase text-gray-500 block mb-1">
                    BUYER PORTAL
                </span>
                <h1 class="text-3xl sm:text-4xl font-serif text-gray-950 font-normal">
                    Welcome back, {{ Auth::user()->name ?? 'Member' }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Browse the latest trending arrivals, manage your orders, or check your bag.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('buyer.browse') }}" class="px-5 py-2.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs rounded transition shadow-sm">
                    Browse Catalog &rarr;
                </a>
                <a href="{{ route('buyer.cart') }}" class="px-5 py-2.5 bg-black hover:bg-neutral-800 text-white font-semibold text-xs rounded transition">
                    View Cart 
                </a>
            </div>
        </div>

        <!-- Success Toast -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <a href="{{ route('buyer.cart') }}" class="text-xs font-bold uppercase tracking-wider text-emerald-900 hover:underline">
                    View Cart  &rarr;
                </a>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Category Pills -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-serif font-bold text-gray-900">Explore by Category</h2>
                <a href="{{ route('buyer.browse') }}" class="text-xs font-semibold text-gray-600 hover:text-black">View all &rarr;</a>
            </div>
            <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('buyer.browse') }}" class="px-4 py-2 text-xs font-semibold rounded-full bg-[#111111] text-white whitespace-nowrap">
                    All Categories
                </a>
                @foreach($existingCategories as $cat)
                    <a href="{{ route('buyer.browse', ['category' => $cat->id]) }}" 
                       class="px-4 py-2 text-xs font-medium rounded-full bg-white text-gray-700 border border-gray-200 hover:border-black transition whitespace-nowrap">
                        {{ $cat->categoryname }} ({{ $cat->products_count }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recommended Products Grid -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-[11px] font-bold tracking-widest uppercase text-gray-500 block">HAND-PICKED</span>
                    <h2 class="text-2xl sm:text-3xl font-serif text-gray-900 font-bold">New & Trending Arrivals</h2>
                </div>
                <a href="{{ route('buyer.browse') }}" class="text-xs font-semibold text-gray-700 hover:text-black">
                    See full catalog &rarr;
                </a>
            </div>

            @if($products->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        @php
                            $imageFallback = $catalogImages[$loop->index % count($catalogImages)];
                            $badgeText = $badges[$loop->index % count($badges)];
                        @endphp

                        <div class="bg-white border border-gray-100 flex flex-col justify-between group hover:shadow-lg transition-all duration-300">
                            <div>
                                <div class="relative bg-[#f4f4f4] aspect-square overflow-hidden flex items-center justify-center p-6">
                                    <span class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm border border-gray-200/60 text-gray-700 text-[11px] font-medium px-3 py-1 rounded-sm shadow-sm">
                                        {{ $badgeText }}
                                    </span>
                                    <img src="{{ !empty($product->image_url) ? $product->image_url : $imageFallback }}" 
                                         alt="{{ $product->productname }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                </div>

                                <div class="p-5 pb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-base font-bold text-gray-900 tracking-tight line-clamp-1">
                                            {{ $product->productname }}
                                        </h3>
                                        <span class="text-base font-bold text-gray-900 whitespace-nowrap">
                                            &#8358;{{ number_format($product->productprice) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 italic mt-1 line-clamp-1">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-5 pt-3 flex items-center justify-between border-t border-gray-100">
                                <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>

                                @if(($product->productquantity ?? 0) > 0)
                                    <form action="{{ route('buyer.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" 
                                                class="bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs px-4 py-2 rounded-sm shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                            <span>+ Add to Cart</span>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" 
                                            disabled 
                                            class="bg-gray-200 text-gray-500 font-semibold text-xs px-4 py-2 rounded-sm cursor-not-allowed opacity-75">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-200 p-12 text-center">
                    <p class="text-gray-500 text-sm">No approved products available right now.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>