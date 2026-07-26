@extends('front.feb.layouts.master')

@section('title', 'Checkout')

@section('content')
    <style>
        .theme-checkout-page {
            min-height: 70vh;
            padding: 42px 16px 70px;
            background: #f5f6f8;
            color: #172033;
        }

        .theme-checkout-container {
            width: min(1160px, 100%);
            margin: 0 auto;
        }

        .theme-checkout-heading {
            margin: 0 0 24px;
            font-size: 28px;
            font-weight: 700;
        }

        .theme-checkout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 400px;
            gap: 26px;
            align-items: start;
        }

        .theme-checkout-card {
            background: #fff;
            border: 1px solid #e2e6eb;
            box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        }

        .theme-checkout-card-title {
            margin: 0;
            padding: 20px 24px;
            border-bottom: 1px solid #e8ebef;
            font-size: 17px;
            font-weight: 700;
        }

        .theme-checkout-form {
            padding: 24px;
        }

        .checkout-field {
            margin-bottom: 18px;
        }

        .checkout-field:last-of-type {
            margin-bottom: 0;
        }

        .checkout-field label {
            display: block;
            margin-bottom: 7px;
            color: #3e485b;
            font-size: 13px;
            font-weight: 600;
        }

        .checkout-required {
            color: #e11d48;
        }

        .checkout-input {
            display: block;
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid #cfd5dd;
            border-radius: 3px;
            background: #fff;
            color: #172033;
            font: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        textarea.checkout-input {
            min-height: 104px;
            resize: vertical;
        }

        .checkout-input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(22, 119, 255, .12);
        }

        .checkout-error {
            display: block;
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
        }

        .checkout-alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }

        .checkout-products {
            padding: 8px 22px;
        }

        .checkout-product {
            display: grid;
            grid-template-columns: 64px minmax(0, 1fr) auto;
            gap: 13px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #edf0f3;
        }

        .checkout-product:last-child {
            border-bottom: 0;
        }

        .checkout-product-image {
            width: 64px;
            height: 72px;
            border: 1px solid #e5e7eb;
            object-fit: cover;
        }

        .checkout-product-name {
            margin-bottom: 5px;
            color: #172033;
            font-size: 14px;
            font-weight: 650;
            line-height: 1.35;
        }

        .checkout-product-meta {
            color: #737d8f;
            font-size: 12px;
            line-height: 1.5;
        }

        .checkout-product-price {
            color: #111827;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .checkout-totals {
            padding: 18px 22px 22px;
            border-top: 1px solid #e8ebef;
        }

        .checkout-total-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 11px;
            color: #596477;
            font-size: 14px;
        }

        .checkout-total-row.is-total {
            margin: 16px 0 0;
            padding-top: 16px;
            border-top: 1px solid #e3e7ec;
            color: #111827;
            font-size: 18px;
            font-weight: 750;
        }

        .checkout-payment-methods {
            margin: 20px 0 16px;
        }

        .checkout-payment-title {
            margin-bottom: 9px;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        .checkout-payment-option {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 9px;
            padding: 12px 13px;
            border: 1px solid #dce1e7;
            border-radius: 4px;
            background: #fff;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }

        .checkout-payment-option.is-selected {
            border-color: #000000;
            background: #f2f7ff;
        }

        .checkout-payment-option input {
            margin: 0;
            accent-color: #000000;
        }

        .checkout-payment-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            color: #166534;
            font-size: 20px;
        }

        .checkout-payment-icon img {
            max-width: 30px;
            max-height: 30px;
            object-fit: contain;
        }

        .checkout-payment-copy strong,
        .checkout-payment-copy small {
            display: block;
        }

        .checkout-payment-copy strong {
            color: #172033;
            font-size: 13px;
        }

        .checkout-payment-copy small {
            margin-top: 2px;
            color: #737d8f;
            font-size: 11px;
        }

        .checkout-submit {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 3px;
            background: #000000;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            transition: background .2s;
        }

        .checkout-submit:hover {
            background: #0d1219;
        }

        .checkout-submit:disabled {
            cursor: wait;
            opacity: .7;
        }

        .checkout-back {
            display: block;
            margin-top: 13px;
            color: #556176;
            text-align: center;
            text-decoration: none;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .theme-checkout-grid {
                grid-template-columns: 1fr;
            }

            .theme-checkout-summary {
                order: -1;
            }
        }

        @media (max-width: 575px) {
            .theme-checkout-page {
                padding: 22px 10px 90px;
            }

            .theme-checkout-heading {
                margin-bottom: 16px;
                font-size: 22px;
            }

            .theme-checkout-card-title,
            .theme-checkout-form {
                padding: 17px;
            }

            .checkout-products,
            .checkout-totals {
                padding-left: 15px;
                padding-right: 15px;
            }

            .checkout-product {
                grid-template-columns: 54px minmax(0, 1fr);
            }

            .checkout-product-image {
                width: 54px;
                height: 62px;
            }

            .checkout-product-price {
                grid-column: 2;
            }
        }
    </style>

    <main class="theme-checkout-page">
        <div class="theme-checkout-container">
            <h1 class="theme-checkout-heading">Checkout</h1>

            @if(session('error'))
                <div class="checkout-alert">{{ session('error') }}</div>
            @endif

            <div class="theme-checkout-grid">
                <section class="theme-checkout-card">
                    <h2 class="theme-checkout-card-title">Delivery Information</h2>

                    <form method="POST" action="{{ route('checkout-store') }}" class="theme-checkout-form" id="themeCheckoutForm">
                        @csrf

                        <div class="checkout-field">
                            <label for="checkoutFullName">Full Name <span class="checkout-required">*</span></label>
                            <input id="checkoutFullName" class="checkout-input" type="text" name="first_name"
                                value="{{ old('first_name', Auth::user()->name ?? '') }}" placeholder="Enter your full name" required>
                            @error('first_name')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="checkout-field">
                            <label for="checkoutPhone">Phone <span class="checkout-required">*</span></label>
                            <input id="checkoutPhone" class="checkout-input" type="tel" name="mobile"
                                value="{{ old('mobile', Auth::user()->phone_number ?? '') }}" placeholder="01XXXXXXXXX"
                                inputmode="numeric" pattern="01[0-9]{9}" maxlength="11" required>
                            @error('mobile')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="checkout-field">
                            <label for="checkoutAddress">Address <span class="checkout-required">*</span></label>
                            <textarea id="checkoutAddress" class="checkout-input" name="shipping_address"
                                placeholder="House, road, area, district" required>{{ old('shipping_address', Auth::user()->address ?? '') }}</textarea>
                            @error('shipping_address')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="checkout-field">
                            <label for="checkoutShippingMethod">Shipping <span class="checkout-required">*</span></label>
                            <select id="checkoutShippingMethod" class="checkout-input" name="shipping_method_id" required>
                                @foreach($shippingMethods as $shippingMethod)
                                    <option value="{{ $shippingMethod->id }}" data-name="{{ $shippingMethod->name }}"
                                        data-price="{{ (float) $shippingMethod->price }}"
                                        {{ (string) old('shipping_method_id', $selectedShippingMethod->id) === (string) $shippingMethod->id ? 'selected' : '' }}>
                                        {{ $shippingMethod->name }} — {{ $febCurrency->format($shippingMethod->price) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shipping_method_id')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="checkout-field">
                            <label for="checkoutNote">Note</label>
                            <textarea id="checkoutNote" class="checkout-input" name="order_note"
                                placeholder="Special instructions for your order (optional)">{{ old('order_note') }}</textarea>
                            @error('order_note')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </section>

                <aside class="theme-checkout-card theme-checkout-summary">
                    <h2 class="theme-checkout-card-title">Order Summary ({{ $cartQuantity }} items)</h2>

                    <div class="checkout-products">
                        @foreach($carts as $cart)
                            <article class="checkout-product">
                                <img class="checkout-product-image"
                                    src="{{ \App\Support\MediaStorage::url($cart->product_image, 'products') }}"
                                    alt="{{ $cart->product_name }}">
                                <div>
                                    <div class="checkout-product-name">{{ $cart->product_name }}</div>
                                    <div class="checkout-product-meta">
                                        Qty: {{ $cart->quantity }}
                                        @if($cart->product_color) &bull; Color: {{ $cart->product_color }} @endif
                                        @if($cart->product_size) &bull; Size: {{ $cart->product_size }} @endif
                                    </div>
                                </div>
                                <div class="checkout-product-price">{{ $febCurrency->format($cart->total_price) }}</div>
                            </article>
                        @endforeach
                    </div>

                    <div class="checkout-totals">
                        <div class="checkout-total-row">
                            <span>Subtotal</span>
                            <strong>{{ $febCurrency->format($cartSubtotal) }}</strong>
                        </div>
                        <div class="checkout-total-row">
                            <span id="checkoutShippingLabel">Shipping ({{ $selectedShippingMethod->name }})</span>
                            <strong id="checkoutShippingAmount">{{ $febCurrency->format($shippingCharge) }}</strong>
                        </div>
                        <div class="checkout-total-row is-total">
                            <span>Total</span>
                            <span id="checkoutGrandTotal">{{ $febCurrency->format($cartTotal) }}</span>
                        </div>

                        <div class="checkout-payment-methods">
                            <div class="checkout-payment-title">Payment Method</div>

                            <label class="checkout-payment-option{{ old('payment_method', 'cod') === 'cod' ? ' is-selected' : '' }}">
                                <input type="radio" name="payment_method" value="cod" form="themeCheckoutForm"
                                    {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} required>
                                <span class="checkout-payment-icon"><i class="fa fa-money"></i></span>
                                <span class="checkout-payment-copy">
                                    <strong>Cash on Delivery</strong>
                                    <small>Pay when you receive your order</small>
                                </span>
                            </label>

                            <label class="checkout-payment-option{{ old('payment_method') === 'bkash' ? ' is-selected' : '' }}">
                                <input type="radio" name="payment_method" value="bkash" form="themeCheckoutForm"
                                    {{ old('payment_method') === 'bkash' ? 'checked' : '' }} required>
                                <span class="checkout-payment-icon">
                                    <img src="{{ asset('feb/img/payment-gateway/bkash-icon.png') }}" alt="bKash">
                                </span>
                                <span class="checkout-payment-copy">
                                    <strong>bKash</strong>
                                    <small>Place your order with bKash payment</small>
                                </span>
                            </label>

                            @error('payment_method')<span class="checkout-error">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" form="themeCheckoutForm" class="checkout-submit" id="checkoutSubmitButton">
                            Confirm Order
                        </button>
                        <a class="checkout-back" href="{{ route('theme-carts') }}">Back to cart</a>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('themeCheckoutForm');
            var button = document.getElementById('checkoutSubmitButton');
            var shippingSelect = document.getElementById('checkoutShippingMethod');
            var cartSubtotal = {{ json_encode((float) $cartSubtotal) }};

            if (!form || !button) {
                return;
            }

            var paymentOptions = document.querySelectorAll('.checkout-payment-option');
            paymentOptions.forEach(function (option) {
                var input = option.querySelector('input[type="radio"]');
                option.classList.toggle('is-selected', input.checked);

                input.addEventListener('change', function () {
                    paymentOptions.forEach(function (item) {
                        item.classList.toggle('is-selected', item.querySelector('input').checked);
                    });
                });
            });

            function formatMoney(value) {
                return Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateShippingTotal() {
                var selected = shippingSelect.options[shippingSelect.selectedIndex];
                var shippingPrice = Number(selected.getAttribute('data-price')) || 0;
                document.getElementById('checkoutShippingLabel').textContent = 'Shipping (' + selected.getAttribute('data-name') + ')';
                document.getElementById('checkoutShippingAmount').textContent = window.formatStoreCurrency(shippingPrice);
                document.getElementById('checkoutGrandTotal').textContent = window.formatStoreCurrency(cartSubtotal + shippingPrice);
            }

            shippingSelect.addEventListener('change', updateShippingTotal);
            updateShippingTotal();

            form.addEventListener('submit', function () {
                if (!form.checkValidity()) {
                    return;
                }

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Placing Order...';
            });
        });
    </script>
@endsection
