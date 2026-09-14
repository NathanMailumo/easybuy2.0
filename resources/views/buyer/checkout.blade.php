<x-layout>
    <x-slot:title>easybuy · Checkout</x-slot:title>

    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Header -->
        <div class="flex items-center justify-between pb-6 mb-8 border-b border-gray-200">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-1">CHECKOUT</p>
                <h1 class="text-3xl sm:text-4xl font-serif text-gray-950 font-normal">Complete your order</h1>
            </div>
            <a href="{{ route('buyer.cart') }}" class="text-sm font-medium text-gray-600 hover:text-black transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Return to Bag</span>
            </a>
        </div>

        @if($errors->any())
        <div class="mb-8 border border-red-200 bg-red-50 p-4 rounded-sm text-red-800" role="alert">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600"></i>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider">Please fix the following issues:</p>
                    <ul class="mt-1.5 space-y-1 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if($cartItems->isEmpty())
        <div class="bg-white border border-gray-200 p-16 text-center rounded-sm">
            <i class="fa-solid fa-bag-shopping text-4xl text-gray-300 mb-3"></i>
            <p class="text-lg text-gray-600 font-serif">Your bag is empty.</p>
            <a href="{{ route('buyer.browse') }}" class="inline-block mt-4 px-6 py-2.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs rounded transition shadow-sm">
                Browse Products
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <!-- Left Column: Delivery & Payment Details Form (7 cols) -->
            <form id="checkout-form" method="POST" action="{{ route('payment.initialize') }}" class="lg:col-span-7 space-y-8">
                @csrf

                <!-- Delivery Details Card -->
                <section class="bg-white border border-gray-200 p-6 sm:p-8 rounded-sm shadow-sm">
                    <div class="flex items-center gap-2.5 pb-4 mb-6 border-b border-gray-200">
                        <i class="fa-solid fa-location-dot text-gray-900"></i>
                        <h2 class="text-xl font-serif font-bold text-gray-900">Delivery Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" placeholder="John" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" placeholder="Doe" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Delivery Address</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required autocomplete="street-address" placeholder="House number, street name, area" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">{{ old('shipping_address', Auth::user()->buyer->shipping_address ?? '') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone_number" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Mobile Number</label>
                            <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number', Auth::user()->buyer->phone_number ?? '') }}" required autocomplete="tel" placeholder="0800 000 0000" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                        </div>

                        <!-- Side-by-Side State & City Select Inputs -->
                        <div>
                            <label for="delivery_state" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">State</label>
                            <select id="delivery_state" name="delivery_state" required class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition cursor-pointer">
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div>
                            <label for="delivery_city" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">City</label>
                            <select id="delivery_city" name="delivery_city" required disabled class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">Select State First</option>
                            </select>
                        </div>

                        <div>
                            <label for="zip_code" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Postal Code</label>
                            <input id="zip_code" type="text" name="zip_code" value="{{ old('zip_code') }}" autocomplete="postal-code" placeholder="100001" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-black transition">
                        </div>
                        <div>
                            <label for="country" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Country</label>
                            <select id="country" name="country" class="w-full bg-[#faf9f6] border border-gray-300 rounded-sm px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-black transition">
                                <option value="Nigeria" selected>Nigeria</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Payment Method Section -->
                <section class="bg-white border border-gray-200 p-6 sm:p-8 rounded-sm shadow-sm">
                    <div class="flex items-center gap-2.5 pb-4 mb-6 border-b border-gray-200">
                        <i class="fa-solid fa-credit-card text-gray-900"></i>
                        <h2 class="text-xl font-serif font-bold text-gray-900">Payment Method</h2>
                    </div>

                    <div class="space-y-3" id="payment-options">
                        <label class="payment-option flex items-start gap-3 border-2 border-black bg-amber-50/50 p-4 rounded-sm cursor-pointer transition">
                            <input type="radio" name="payment_method" value="paystack" checked class="mt-1 accent-black">
                            <div>
                                <span class="block text-sm font-bold text-gray-900"><i class="fa-solid fa-credit-card mr-2 text-gray-700"></i>Paystack (Instant Card / Bank / USSD)</span>
                                <span class="block text-xs text-gray-600 mt-0.5">Pay securely with Debit Card, Bank Transfer, or USSD via Paystack.</span>
                            </div>
                        </label>

                        <label class="payment-option flex items-start gap-3 border border-gray-200 bg-white p-4 rounded-sm cursor-pointer hover:border-black transition">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 accent-black">
                            <div>
                                <span class="block text-sm font-bold text-gray-900"><i class="fa-solid fa-building-columns mr-2 text-gray-700"></i>Direct Bank Transfer</span>
                                <span class="block text-xs text-gray-600 mt-0.5">Receive account transfer details upon placing your order.</span>
                            </div>
                        </label>

                        <label class="payment-option flex items-start gap-3 border border-gray-200 bg-white p-4 rounded-sm cursor-pointer hover:border-black transition">
                            <input type="radio" name="payment_method" value="cash_on_delivery" class="mt-1 accent-black">
                            <div>
                                <span class="block text-sm font-bold text-gray-900"><i class="fa-solid fa-money-bill-wave mr-2 text-gray-700"></i>Cash on Delivery</span>
                                <span class="block text-xs text-gray-600 mt-0.5">Pay cash when your order arrives at your doorstep.</span>
                            </div>
                        </label>
                    </div>

                    <p id="payment-note" class="mt-4 text-xs text-gray-500 italic">
                        <i class="fa-solid fa-lock mr-1"></i> You will be redirected to Paystack's encrypted gateway to complete payment.
                    </p>
                </section>

            </form>


            <!-- Right Column: Order Summary (5 cols) -->
            <aside class="lg:col-span-5 sticky top-24">
                <div class="bg-white border border-gray-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-xl font-serif font-bold text-gray-900 pb-4 mb-4 border-b border-gray-200">
                        Order Summary
                    </h2>

                    <!-- Items list -->
                    <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto pr-1">
                        @foreach($cartItems as $item)
                        <div class="py-3 flex items-center justify-between gap-3 text-sm">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">{{ $item->products->productname }}</p>
                                <span class="text-xs text-gray-500">Qty: {{ $item->quantity }}</span>
                            </div>
                            <span class="font-semibold text-gray-900 whitespace-nowrap">
                                &#8358;{{ number_format(($item->products->productprice ?? 0) * $item->quantity) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Breakdown Calculations -->
                    <div class="border-t border-gray-200 pt-4 mt-4 space-y-2.5 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-900">&#8358;{{ number_format($subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Fee</span>
                            <span class="font-semibold text-gray-900">&#8358;{{ number_format($shippingFee) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>VAT (7.5%)</span>
                            <span class="font-semibold text-gray-900">&#8358;{{ number_format($vat) }}</span>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4 flex items-baseline justify-between">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-2xl font-serif font-bold text-gray-950">&#8358;{{ number_format($total) }}</span>
                    </div>

                    <!-- Terms and Conditions Section -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" id="terms-checkbox" name="terms" form="checkout-form" class="mt-0.5 rounded border-gray-300 text-black focus:ring-black accent-black">
                            <span class="text-xs text-gray-600 leading-tight">
                                I have read and agree to the
                                <button type="button" id="open-terms-modal" class="underline font-semibold text-gray-900 hover:text-black">
                                    Terms & Conditions
                                </button>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button (Disabled until checkbox is checked) -->
                    <button id="checkout-submit" form="checkout-form" type="submit" disabled
                        class="w-full mt-4 py-3.5 bg-gray-300 text-gray-500 font-semibold text-sm rounded-sm transition flex items-center justify-center gap-2 cursor-not-allowed opacity-60">
                        <span>Proceed to Payment</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                    <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-gray-400 space-y-1">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-gray-500"></i>
                            <span>256-bit SSL encrypted checkout</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-truck-fast text-gray-500"></i>
                            <span>Fast dispatch across Nigeria</span>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
        @endif
    </div>

    <!-- Terms Modal Overlay -->
    <div id="terms-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full rounded-sm shadow-xl border border-gray-200 p-6 sm:p-8 relative">
            <h3 class="text-xl font-serif font-bold text-gray-950 pb-3 border-b border-gray-200 flex items-center justify-between">
                <span>Terms & Conditions</span>
                <i class="fa-solid fa-file-contract text-gray-400"></i>
            </h3>

            <div class="mt-4 space-y-3.5 text-xs text-gray-700 leading-relaxed max-h-72 overflow-y-auto pr-2">
                <div class="flex gap-2.5">
                    <span class="font-bold text-gray-900">1.</span>
                    <p><strong>Order Finality:</strong> All orders submitted through EasyBuy are binding upon payment confirmation.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="font-bold text-gray-900">2.</span>
                    <p><strong>Shipping & Delivery:</strong> Delivery timelines depend on state location. Shipping fees are non-refundable once dispatched.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="font-bold text-gray-900">3.</span>
                    <p><strong>Returns & Refunds:</strong> Damaged or incorrect items must be reported within 48 hours of receipt for return processing.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="font-bold text-gray-900">4.</span>
                    <p><strong>Payment Security:</strong> Card payments are securely processed via Paystack. EasyBuy does not store raw payment card data.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="font-bold text-gray-900">5.</span>
                    <p><strong>Account Responsibility:</strong> Buyers must provide accurate delivery addresses and contact information to avoid failed dispatches.</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                <button type="button" id="accept-terms-btn" class="px-5 py-2.5 bg-[#f5ce42] hover:bg-[#e6c035] text-black font-semibold text-xs rounded transition shadow-sm">
                    I Understand & Accept
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('delivery_state');
            const citySelect = document.getElementById('delivery_city');

            const oldState = "{{ old('delivery_state') }}";
            const oldCity = "{{ old('delivery_city') }}";
            let stateCityData = {};

            // Fetch state & city data from your public directory
            fetch("{{ asset('js/city.json') }}")
                .then(response => response.json())
                .then(data => {
                    stateCityData = data;

                    // Populate States Dropdown
                    Object.keys(stateCityData).forEach(state => {
                        const opt = document.createElement('option');
                        opt.value = state;
                        opt.textContent = state;
                        if (oldState === state) opt.selected = true;
                        stateSelect.appendChild(opt);
                    });

                    // Trigger city population if an old state selection exists
                    if (oldState) {
                        populateCities(oldState, oldCity);
                    }
                })
                .catch(err => console.error('Error loading states-cities JSON:', err));

            // Populate Cities Helper
            function populateCities(selectedState, preselectCity = '') {
                citySelect.innerHTML = '<option value="">Select City</option>';

                if (selectedState && stateCityData[selectedState]) {
                    stateCityData[selectedState].forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city;
                        opt.textContent = city;
                        if (preselectCity === city) opt.selected = true;
                        citySelect.appendChild(opt);
                    });
                    citySelect.disabled = false;
                } else {
                    citySelect.disabled = true;
                }
            }

            // State selection change listener
            stateSelect.addEventListener('change', function() {
                populateCities(this.value);
            });

            // Payment radio buttons toggle logic
            document.querySelectorAll('input[name="payment_method"]').forEach(function(option) {
                option.addEventListener('change', function() {
                    document.querySelectorAll('.payment-option').forEach(el => {
                        el.classList.remove('border-2', 'border-black', 'bg-amber-50/50');
                        el.classList.add('border', 'border-gray-200', 'bg-white');
                    });
                    this.closest('.payment-option').classList.remove('border', 'border-gray-200', 'bg-white');
                    this.closest('.payment-option').classList.add('border-2', 'border-black', 'bg-amber-50/50');

                    const submit = document.getElementById('checkout-submit');
                    const note = document.getElementById('payment-note');
                    const isCash = this.value === 'cash_on_delivery';

                    submit.querySelector('span').textContent = isCash ? 'Place Order' : 'Proceed to Payment';
                    note.innerHTML = isCash ?
                        '<i class="fa-solid fa-truck mr-1"></i> Pay cash when your order is delivered to your door.' :
                        '<i class="fa-solid fa-lock mr-1"></i> You will be redirected to Paystack\'s encrypted gateway to complete payment.';
                });
            });
        });

        // t/c logic
        const termsModal = document.getElementById('terms-modal');
        const openTermsBtn = document.getElementById('open-terms-modal');
        const acceptTermsBtn = document.getElementById('accept-terms-btn');
        const termsCheckbox = document.getElementById('terms-checkbox');
        const checkoutSubmit = document.getElementById('checkout-submit');

        // 1. Open modal optionally if they click the link
        openTermsBtn.addEventListener('click', function() {
            termsModal.classList.remove('hidden');
            termsModal.classList.add('flex');
        });

        // 2. Close modal & check box when "I Understand & Accept" is clicked inside modal
        acceptTermsBtn.addEventListener('click', function() {
            termsModal.classList.add('hidden');
            termsModal.classList.remove('flex');

            termsCheckbox.checked = true;
            toggleSubmitButton(true);
        });

        // 3. Allow manual checking/unchecking at any time
        termsCheckbox.addEventListener('change', function() {
            toggleSubmitButton(this.checked);
        });

        // Helper to toggle submit button state
        function toggleSubmitButton(isEnabled) {
            checkoutSubmit.disabled = !isEnabled;

            if (isEnabled) {
                checkoutSubmit.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'opacity-60');
                checkoutSubmit.classList.add('bg-[#f5ce42]', 'hover:bg-[#e6c035]', 'text-black', 'cursor-pointer');
            } else {
                checkoutSubmit.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'opacity-60');
                checkoutSubmit.classList.remove('bg-[#f5ce42]', 'hover:bg-[#e6c035]', 'text-black', 'cursor-pointer');
            }
        }
    </script>
</x-layout>