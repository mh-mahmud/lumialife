@extends('front.feb.layouts.master')

@section('title', 'Order Confirmed')

@section('content')
    <style>
        .thankyou-page {
            min-height: 70vh;
            padding: 48px 16px 72px;
            background: #f5f6f8;
            color: #172033;
        }

        .thankyou-container {
            width: min(1040px, 100%);
            margin: 0 auto;
        }

        .thankyou-success {
            margin-bottom: 26px;
            padding: 32px 24px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            text-align: center;
        }

        .thankyou-success-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            margin-bottom: 14px;
            border-radius: 50%;
            background: #16a34a;
            color: #fff;
            font-size: 30px;
        }

        .thankyou-success h1 {
            margin: 0 0 8px;
            color: #14532d;
            font-size: 28px;
            font-weight: 750;
        }

        .thankyou-success p {
            margin: 0;
            color: #3f624a;
            font-size: 14px;
        }

        .thankyou-reference {
            display: inline-block;
            margin-top: 14px;
            padding: 8px 14px;
            border: 1px dashed #4ade80;
            background: #fff;
            color: #14532d;
            font-size: 14px;
            font-weight: 700;
        }

        .thankyou-grid {
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .thankyou-card {
            border: 1px solid #e2e6eb;
            background: #fff;
            box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        }

        .thankyou-card-title {
            margin: 0;
            padding: 18px 20px;
            border-bottom: 1px solid #e8ebef;
            font-size: 16px;
            font-weight: 700;
        }

        .thankyou-info {
            padding: 18px 20px;
        }

        .thankyou-info-row {
            display: grid;
            grid-template-columns: 110px minmax(0, 1fr);
            gap: 12px;
            margin-bottom: 13px;
            color: #172033;
            font-size: 13px;
            line-height: 1.5;
        }

        .thankyou-info-row:last-child {
            margin-bottom: 0;
        }

        .thankyou-info-label {
            color: #768094;
        }

        .thankyou-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #fff7ed;
            color: #c2410c;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .thankyou-products {
            padding: 4px 20px;
        }

        .thankyou-product {
            display: grid;
            grid-template-columns: 62px minmax(0, 1fr) auto;
            gap: 13px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #edf0f3;
        }

        .thankyou-product:last-child {
            border-bottom: 0;
        }

        .thankyou-product-image {
            width: 62px;
            height: 68px;
            border: 1px solid #e5e7eb;
            object-fit: cover;
        }

        .thankyou-product-name {
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 650;
            line-height: 1.4;
        }

        .thankyou-product-meta {
            color: #768094;
            font-size: 12px;
        }

        .thankyou-product-total {
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .thankyou-totals {
            padding: 17px 20px 20px;
            border-top: 1px solid #e8ebef;
        }

        .thankyou-total-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 10px;
            color: #5c6678;
            font-size: 13px;
        }

        .thankyou-total-row.is-total {
            margin: 15px 0 0;
            padding-top: 15px;
            border-top: 1px solid #e2e6eb;
            color: #111827;
            font-size: 17px;
            font-weight: 750;
        }

        .thankyou-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .thankyou-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 18px;
            border: 1px solid #1677ff;
            border-radius: 3px;
            background: #1677ff;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .thankyou-action.is-secondary {
            background: #fff;
            color: #1677ff;
        }

        @media (max-width: 800px) {
            .thankyou-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .thankyou-page {
                padding: 24px 10px 90px;
            }

            .thankyou-success {
                padding: 25px 15px;
            }

            .thankyou-success h1 {
                font-size: 22px;
            }

            .thankyou-info-row {
                grid-template-columns: 90px minmax(0, 1fr);
            }

            .thankyou-product {
                grid-template-columns: 52px minmax(0, 1fr);
            }

            .thankyou-product-image {
                width: 52px;
                height: 58px;
            }

            .thankyou-product-total {
                grid-column: 2;
            }
        }
    </style>

    <main class="thankyou-page">
        <div class="thankyou-container">
            <section class="thankyou-success">
                <span class="thankyou-success-icon"><i class="fa fa-check"></i></span>
                <h1>Thank you! Your order is confirmed.</h1>
                <p>We have received your order and will contact you if any confirmation is required.</p>
                <div class="thankyou-reference">Order Reference: {{ $order->custom_order_id }}</div>
            </section>

            <div class="thankyou-grid">
                <section class="thankyou-card">
                    <h2 class="thankyou-card-title">Order Information</h2>
                    <div class="thankyou-info">
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Full Name</span>
                            <strong>{{ $order->first_name }}</strong>
                        </div>
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Phone</span>
                            <span>{{ $order->order_phone_number }}</span>
                        </div>
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Address</span>
                            <span>{{ $order->shipping_address }}</span>
                        </div>
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Payment</span>
                            <span>{{ $order->payment_type }}</span>
                        </div>
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Payment Status</span>
                            <span class="thankyou-status">{{ strtolower($order->payment_status) }}</span>
                        </div>
                        <div class="thankyou-info-row">
                            <span class="thankyou-info-label">Order Status</span>
                            <span class="thankyou-status">{{ strtolower($order->order_status) }}</span>
                        </div>
                        @if($order->order_note)
                            <div class="thankyou-info-row">
                                <span class="thankyou-info-label">Note</span>
                                <span>{{ $order->order_note }}</span>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="thankyou-card">
                    <h2 class="thankyou-card-title">Order Summary</h2>
                    <div class="thankyou-products">
                        @forelse($orderDetails as $detail)
                            @php
                                $lineTotal = (float) ($detail->total ?: ($detail->quantity * $detail->unit_price));
                                $productName = $detail->product->name ?? 'Product unavailable';
                                $productImage = $detail->product && $detail->product->img_path
                                    ? \App\Support\MediaStorage::url($detail->product->img_path, 'products')
                                    : asset('uploads/blank.png');
                            @endphp
                            <article class="thankyou-product">
                                <img class="thankyou-product-image" src="{{ $productImage }}" alt="{{ $productName }}">
                                <div>
                                    <div class="thankyou-product-name">{{ $productName }}</div>
                                    <div class="thankyou-product-meta">
                                        {{ $febCurrency->format($detail->unit_price) }} &times; {{ $detail->quantity }}
                                    </div>
                                </div>
                                <div class="thankyou-product-total">{{ $febCurrency->format($lineTotal) }}</div>
                            </article>
                        @empty
                            <p>No product details are available for this order.</p>
                        @endforelse
                    </div>

                    <div class="thankyou-totals">
                        <div class="thankyou-total-row">
                            <span>Subtotal</span>
                            <strong>{{ $febCurrency->format($order->total_price) }}</strong>
                        </div>
                        <div class="thankyou-total-row">
                            <span>Shipping{{ $order->shipping_method ? ' (' . $order->shipping_method . ')' : '' }}</span>
                            <strong>{{ $febCurrency->format($order->delivery_charge) }}</strong>
                        </div>
                        @if((float) $order->discount > 0)
                            <div class="thankyou-total-row">
                                <span>Discount</span>
                                <strong>-{{ $febCurrency->format($order->discount) }}</strong>
                            </div>
                        @endif
                        <div class="thankyou-total-row is-total">
                            <span>Payable Amount</span>
                            <span>{{ $febCurrency->format($order->final_price) }}</span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="thankyou-actions">
                <a class="thankyou-action" href="{{ route('order-tracking') }}">Track Order</a>
                <a class="thankyou-action is-secondary" href="{{ route('shop-new') }}">Continue Shopping</a>
            </div>
        </div>
    </main>
@endsection
