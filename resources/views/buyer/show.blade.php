<x-layout>
    <x-slot:title>easybuy · {{ $product->productname }}</x-slot:title>

    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Breadcrumb & Back Navigation -->
        <nav class="flex items-center justify-between pb-6 mb-8 border-b border-gray-200 text-xs">
            <a href="{{ route('buyer.browse') }}" class="font-medium text-gray-600 hover:text-black transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Shop</span>
            </a>
            <div class="text-gray-400 flex items-center gap-1.5">
                <span>{{ $product->category->name ?? 'General' }}</span>
                <span>/</span>
                <span class="text-gray-900 font-semibold truncate max-w-[150px] sm:max-w-xs">{{ $product->productname }}</span>
            </div>
        </nav>

        <!-- Product View Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- Left Column: Large Product Image -->
            <div class="lg:col-span-7 bg-white border border-gray-200 p-4 sm:p-6 rounded-sm shadow-sm">
                <div class="aspect-square w-full bg-[#faf9f6] rounded-sm overflow-hidden flex items-center justify-center relative">
                    @if(!empty($product->image))
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->productname }}"
                        class="w-full h-full object-contain hover:scale-105 transition duration-500">
                    @elseif(!empty($product->image_url))
                    <img src="{{ $product->image_url }}"
                        alt="{{ $product->productname }}"
                        class="w-full h-full object-contain hover:scale-105 transition duration-500">
                    @else
                    <img src="{{ $fallbackImage }}"
                        alt="{{ $product->productname }}"
                        class="w-full h-full object-contain hover:scale-105 transition duration-500">
                    @endif
                </div>
            </div>

            <!-- Right Column: Product Info & Actions (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <div class="bg-white border border-gray-200 p-6 sm:p-8 rounded-sm shadow-sm space-y-6">

                    <!-- Category & Title -->
                    <div>
                        <span class="text-[10px] font-bold tracking-widest uppercase bg-amber-100 text-amber-900 px-2.5 py-1 rounded-sm inline-block mb-3">
                            {{ $product->category->name ?? 'General Category' }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gray-950 leading-tight">
                            {{ $product->productname }}
                        </h1>
                    </div>

                    <!-- Price & Stock Status -->
                    <div class="flex items-baseline justify-between border-y border-gray-100 py-4">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block mb-0.5">Price</span>
                            <span class="text-3xl font-serif font-bold text-gray-950">&#8358;{{ number_format($product->productprice) }}</span>
                        </div>

                        <div class="text-right">
                            @if(($product->stock ?? 1) > 0)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                In Stock
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                Out of Stock
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Product Description</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $product->description ?? 'No detailed description available for this item.' }}
                        </p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form method="POST" action="{{ route('buyer.addToCart') }}" class="pt-4 border-t border-gray-100 space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex items-center gap-3">
                            <label for="quantity" class="text-xs font-bold uppercase tracking-wider text-gray-700 whitespace-nowrap">Quantity</label>
                            <select id="quantity" name="quantity" class="bg-[#faf9f6] border border-gray-300 rounded-sm px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-black transition cursor-pointer">
                                @for ($i = 1; $i <= min($product->stock ?? 5, 10); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>

                        <button type="submit"
                            @if(($product->stock ?? 1) <= 0) disabled @endif
                                class="w-full py-3.5 bg-[#f5ce42] hover:bg-[#e6c035] disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-black font-semibold text-sm rounded-sm transition flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                                <i class="fa-solid fa-bag-shopping text-xs"></i>
                                <span>Add to Cart</span>
                        </button>
                    </form>

                    <!-- Extra Buying Confidence Features -->
                    <div class="pt-4 border-t border-gray-100 text-[11px] text-gray-400 space-y-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-truck-fast text-gray-600"></i>
                            <span>Standard dispatch across Nigeria</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-gray-600"></i>
                            <span>Verified authentic product listing</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</x-layout>