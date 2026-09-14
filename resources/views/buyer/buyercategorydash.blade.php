<x-layout>
    <x-slot:title>easybuy · {{ $selectedCategory ? $selectedCategory->categoryname : 'All Products' }}</x-slot:title>

    @php
    // If no category is selected, display all approved products; otherwise display category products
    $displayProducts = $selectedCategory
    ? $products
    : \App\Models\Products::where('status', 'approved')->latest()->get();

    if (request()->filled('search')) {
    $query = strtolower(request('search'));
    $displayProducts = $displayProducts->filter(function($p) use ($query) {
    return str_contains(strtolower($p->productname), $query) || str_contains(strtolower($p->description), $query);
    });
    }

    // High quality ecommerce product images
    $catalogImages = [
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
    ];

    $badges = ['New', 'Popular', 'Sale'];
    @endphp

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Header Notification -->
        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('buyer.cart') }}" class="text-xs font-bold uppercase tracking-wider text-emerald-900 hover:underline flex items-center gap-1">
                <span>View Cart </span> &rarr;
            </a>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8 sm:mb-10">
            <p class="text-xs font-semibold tracking-widest uppercase text-gray-500 mb-2">
                SHOWING {{ $displayProducts->count() }} {{ Str::plural('ITEM', $displayProducts->count()) }}
            </p>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif text-[#111111] font-normal tracking-tight">
                {{ $selectedCategory ? $selectedCategory->categoryname : 'All Products' }}
            </h1>
        </div>

        <!-- Filter Pills Bar -->
        <div class="flex items-center justify-between gap-4 pb-6 mb-8 overflow-x-auto border-b border-gray-200 scrollbar-none">
            <div class="flex items-center gap-2.5 flex-nowrap">
                <a href="{{ route('buyer.browse') }}"
                    class="px-5 py-2 text-sm font-medium rounded-full transition whitespace-nowrap {{ !$selectedCategory ? 'bg-[#111111] text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-black' }}">
                    All
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('buyer.browse', ['category' => $cat->id]) }}"
                    class="px-5 py-2 text-sm font-medium rounded-full transition whitespace-nowrap {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'bg-[#111111] text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-black' }}">
                    {{ $cat->categoryname }}
                </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('buyer.browse') }}" method="GET" class="hidden sm:flex items-center relative">
                @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory->id }}">
                @endif
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-48 lg:w-60 bg-white border border-gray-200 rounded-full pl-9 pr-4 py-1.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-black transition">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs absolute left-3 pointer-events-none"></i>
            </form>
        </div>

        <!-- 3-Column Products Grid -->
        @if($displayProducts->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($displayProducts as $product)
            @php
            $imageFallback = $catalogImages[$loop->index % count($catalogImages)];
            $badgeText = $badges[$loop->index % count($badges)];
            @endphp

            <div class="bg-white border border-gray-100 flex flex-col justify-between group hover:shadow-lg transition-all duration-300">

                <div>
                    <!-- Product Image Area -->
                    <a href="{{ route('buyer.product.show', $product->id) }}" class="block">
                        <div class="relative bg-[#f4f4f4] aspect-square overflow-hidden flex items-center justify-center p-6">
                            <span class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm border border-gray-200/60 text-gray-700 text-[11px] font-medium px-3 py-1 rounded-sm shadow-sm">
                                {{ $badgeText }}
                            </span>

                            <img src="{{ !empty($product->image_url) ? $product->image_url : $imageFallback }}"
                                alt="{{ $product->productname }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                        </div>
                    </a>

                    <!-- Product Info -->
                    <div class="p-5 pb-2">
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('buyer.product.show', $product->id) }}" class="hover:underline">
                                <h3 class="text-base font-bold text-gray-900 tracking-tight line-clamp-1">
                                    {{ $product->productname }}
                                </h3>
                            </a>
                            <span class="text-base font-bold text-gray-900 whitespace-nowrap">
                                &#8358;{{ number_format($product->productprice) }}
                            </span>
                        </div>

                        <p class="text-xs text-gray-500 italic mt-1 line-clamp-1">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                <!-- Row 3: Ratings & Add to Cart Button -->
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
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 p-16 text-center my-8 rounded-none">
            <i class="fa-solid fa-bag-shopping text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-serif text-gray-900 mb-2">No products found</h3>
            <p class="text-sm text-gray-500 mb-6">We couldn't find any items in this category right now.</p>
            <a href="{{ route('buyer.browse') }}" class="inline-block px-6 py-2.5 bg-black text-white text-xs font-semibold uppercase tracking-wider rounded">
                View All Products
            </a>
        </div>
        @endif

    </div>
</x-layout>