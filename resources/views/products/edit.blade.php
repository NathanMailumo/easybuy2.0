<x-layout>
    <x-slot:title>easybuy · Edit Product</x-slot:title>

    <div class="w-full max-w-xl mx-auto px-4 py-8 sm:py-12 flex-1 flex flex-col justify-center">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
            <a href="{{ route('products.product') }}" class="text-xs font-semibold text-gray-500 hover:text-black transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Inventory</span>
            </a>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Modify Record</span>
        </div>

        <div class="bg-white border border-gray-200 p-8 sm:p-10 shadow-sm rounded-sm">
            <div class="text-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gray-950">
                    Edit Product
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Update the details for {{ $product->productname }}.
                </p>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Product Name
                    </label>
                    <input type="text" 
                           name="productname" 
                           value="{{ old('productname', $product->productname) }}"
                           required
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                    @error('productname')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              required
                              class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition resize-none">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Category
                    </label>
                    <select name="category_id" 
                            class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                        @foreach(App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->categoryname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Price (&#8358;)
                    </label>
                    <input type="number" 
                           name="productprice" 
                           value="{{ old('productprice', $product->productprice) }}"
                           required
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                    @error('productprice')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Item Quantity (&#8358;)
                    </label>
                    <input type="number" 
                           name="productquantity" 
                           value="{{ old('productquantity', $product->productquantity) }}"
                           required
                           class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                    @error('productprice')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs uppercase tracking-widest rounded-sm transition shadow-sm flex items-center justify-center gap-2 cursor-pointer mt-4">
                    <span>Save Changes</span>
                    <i class="fa-solid fa-check text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</x-layout>