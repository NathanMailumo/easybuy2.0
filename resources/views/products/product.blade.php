<x-layout>
    <x-slot:title>easybuy · Manage Products</x-slot:title>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Header Navigation -->
        <div class="flex items-center justify-between pb-6 mb-8 border-b border-gray-200">
            <div>
                <a href="{{ route('seller.dashboard') }}" class="text-xs font-semibold text-gray-500 hover:text-black transition flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Seller Dashboard</span>
                </a>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-950">Store Inventory</h1>
            </div>
            <a href="{{ route('addProduct') }}" class="px-4 py-2.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs rounded transition shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Product</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($products->isNotEmpty())
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3.5 px-5">Product</th>
                            <th class="py-3.5 px-5">Category</th>
                            <th class="py-3.5 px-5 text-right">Price</th>
                            <th class="py-3.5 px-5 text-center">Stock</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                            <th class="py-3.5 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-5">
                                    <p class="font-bold text-gray-900">{{ $product->productname }}</p>
                                    <p class="text-xs text-gray-500 italic mt-0.5 line-clamp-1 max-w-sm">{{ $product->description }}</p>
                                </td>
                                <td class="py-4 px-5 text-xs text-gray-600">
                                    {{ $product->category->categoryname ?? 'General' }}
                                </td>
                                <td class="py-4 px-5 text-right font-semibold text-gray-900">
                                    &#8358;{{ number_format($product->productprice) }}
                                </td>
                                <td class="py-4 px-5 text-center font-semibold text-xs">
                                    @if(($product->productquantity ?? 0) > 0)
                                        <span class="text-gray-900">{{ $product->productquantity }} left</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase">
                                            Out of Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-center">
                                    @if(($product->status ?? 'waiting') === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                                            Waiting Approval
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="inline-flex items-center gap-3 text-xs">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-gray-600 hover:text-black font-semibold transition" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product permanently?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition cursor-pointer" title="Delete">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white border border-gray-200 p-16 text-center rounded-sm">
                <i class="fa-solid fa-boxes-stacked text-4xl text-gray-300 mb-3"></i>
                <h3 class="text-2xl font-serif text-gray-900 mb-2">No Products in Your Inventory</h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-6">Start listing products to make them visible to buyers.</p>
                <a href="{{ route('addProduct') }}" class="px-5 py-2.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs rounded transition shadow-sm">
                    + Add Your First Product
                </a>
            </div>
        @endif

    </div>
</x-layout>