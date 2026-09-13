<x-layout>
    <x-slot:title>easybuy · Add Product</x-slot:title>

    <div class="w-full max-w-xl mx-auto px-4 py-8 sm:py-12 flex-1 flex flex-col justify-center">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
            <a href="{{ route('products.product') }}" class="text-xs font-semibold text-gray-500 hover:text-black transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Inventory</span>
            </a>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">New Product</span>
        </div>

        <div class="bg-white border border-gray-200 p-8 sm:p-10 shadow-sm rounded-sm">
            <div class="text-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gray-950">
                    Add New Product
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Fill in the product details to add it to your store catalog.
                </p>
            </div>

            <form action="{{ route('products.addProduct') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Product Name
                    </label>
                    <input type="text" 
                           name="productname" 
                           required
                           placeholder="e.g. Leather Minimalist Watch"
                           value="{{ old('productname') }}"
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              required
                              placeholder="Brief description of the product material, design, and features..."
                              class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition resize-none">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Category
                    </label>
                    <select name="category_id" 
                            class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                        @foreach(App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->categoryname }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Price (&#8358;)
                    </label>
                    <input type="number" 
                           name="productprice" 
                           required
                           placeholder="15000"
                           value="{{ old('productprice') }}"
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Item Quantity (&#8358;)
                    </label>
                    <input type="number" 
                           name="productquantity" 
                           required
                           placeholder="50"
                           value="{{ old('productquantity') }}"
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs uppercase tracking-widest rounded-sm transition shadow-sm flex items-center justify-center gap-2 cursor-pointer mt-4">
                    <span>Submit Product for Approval</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</x-layout>