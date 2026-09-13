<x-layout>
    <x-slot:title>easybuy · Order Confirmation</x-slot:title>

    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between pb-4 mb-8 border-b border-gray-200">
            <a href="{{ route('buyer.browse') }}" class="text-sm font-medium text-gray-600 hover:text-black transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Continue Shopping</span>
            </a>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Order Receipt
            </span>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-sm flex items-center gap-2 text-sm font-medium shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 rounded-sm">
                <p class="text-xs font-bold uppercase tracking-wider">Notification:</p>
                <ul class="mt-1 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($order)
            <!-- Receipt Box -->
            <div class="bg-white border border-gray-200 p-6 sm:p-10 shadow-sm rounded-sm">
                
                <!-- Success Status Header -->
                <div class="text-center pb-8 border-b border-gray-200">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-950">
                        Order Confirmed!
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Thank you for your order, {{ Auth::user()->name }}. We're preparing your shipment.
                    </p>
                </div>

                <!-- Receipt Metadata -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 border border-gray-200/80 p-5 my-6 text-xs text-gray-700 rounded-sm">
                    <div>
                        <span class="font-bold uppercase tracking-wider text-gray-400 text-[10px] block">Payment Status</span>
                        <span class="font-semibold text-emerald-700 text-sm mt-0.5 inline-flex items-center gap-1 capitalize">
                            <i class="fa-solid fa-circle-check"></i> {{ $order->payment_status }}
                        </span>
                    </div>
                    <div>
                        <span class="font-bold uppercase tracking-wider text-gray-400 text-[10px] block">Payment Method</span>
                        <span class="font-semibold text-gray-900 text-sm mt-0.5 block capitalize">{{ $order->payment_method }}</span>
                    </div>
                    <div>
                        <span class="font-bold uppercase tracking-wider text-gray-400 text-[10px] block">Order Number</span>
                        <span class="font-mono text-gray-800 text-xs mt-0.5 block break-all">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="font-bold uppercase tracking-wider text-gray-400 text-[10px] block">Estimated Delivery</span>
                        <span class="font-medium text-gray-800 text-xs mt-0.5 block">
                            {{ $estimatedDate }}
                        </span>
                    </div>
                    <div class="sm:col-span-2 border-t border-gray-200 pt-3 mt-1">
                        <span class="font-bold uppercase tracking-wider text-gray-400 text-[10px] block">Delivery Details</span>
                        <p class="font-medium text-gray-900 mt-0.5">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-gray-600 mt-0.5">
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>

                <!-- Order Items Table -->
                @if($order->items->isNotEmpty())
                    <div class="my-6 overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">
                                    <th class="py-3">Product</th>
                                    <th class="py-3 text-right">Price</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                    @php
                                        $unitPrice = $item->price ?? 0;
                                        $lineTotal = $unitPrice * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td class="py-4">
                                            <p class="font-bold text-gray-900">{{ $item->product->productname ?? 'Product Unavailable' }}</p>
                                            @if(!empty($item->product->description))
                                                <p class="text-xs text-gray-500 italic mt-0.5 line-clamp-1">{{ $item->product->description }}</p>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right font-medium text-gray-800">
                                            &#8358;{{ number_format($unitPrice, 2) }}
                                        </td>
                                        <td class="py-4 text-center font-semibold text-gray-800">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="py-4 text-right font-bold text-gray-950">
                                            &#8358;{{ number_format($lineTotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="border-t border-gray-200 pt-5 space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-900">&#8358;{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Standard Shipping</span>
                            <span class="text-emerald-700 font-medium">Free</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-base font-bold text-gray-900">Total Paid</span>
                            <span class="text-2xl font-serif font-bold text-gray-950">&#8358;{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                @endif

                <!-- Bottom Actions -->
                <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('buyer.browse') }}" 
                       class="w-full sm:w-auto px-8 py-3 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs uppercase tracking-wider rounded transition text-center shadow-sm">
                        Browse More Products &rarr;
                    </a>
                    <a href="{{ route('buyer.dashboard') }}" 
                       class="w-full sm:w-auto px-8 py-3 border border-gray-300 hover:bg-gray-50 text-gray-800 font-semibold text-xs uppercase tracking-wider rounded transition text-center">
                        Go to Dashboard
                    </a>
                </div>

            </div>
        @else
            <!-- Fallback if no order is found -->
            <div class="bg-white border border-gray-200 p-12 text-center rounded-sm">
                <i class="fa-solid fa-box-open text-gray-400 text-4xl mb-4"></i>
                <h2 class="text-xl font-bold text-gray-900">No Orders Found</h2>
                <p class="text-gray-500 text-sm mt-1 mb-6">You haven't completed any purchases yet.</p>
                <a href="{{ route('buyer.browse') }}" class="px-6 py-2.5 bg-[#f5ce42] text-black font-semibold text-xs rounded uppercase tracking-wider">
                    Start Shopping
                </a>
            </div>
        @endif

    </div>
</x-layout>