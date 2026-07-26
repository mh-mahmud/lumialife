@extends('front.feb.layouts.master')

@section('title', 'Order Details')

@section('content')
<style>
    .order-details-page { min-height: 72vh; padding: 34px 16px 75px; background: #f4f6f9; color: #182033; }
    .order-details-container { width: min(1050px, 100%); margin: 0 auto; }
    .order-page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 22px; }
    .order-page-header h1 { margin: 0 0 6px; font-size: 26px; font-weight: 700; }
    .order-page-header p { margin: 0; color: #6b7483; font-size: 13px; }
    .order-back { color: #253248; font-size: 13px; font-weight: 600; text-decoration: none; }
    .order-overview { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
    .overview-item { padding: 17px; border: 1px solid #dde2e8; border-radius: 9px; background: #fff; }
    .overview-item small { display: block; margin-bottom: 6px; color: #778194; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .overview-item strong { display: block; color: #172033; font-size: 14px; }
    .order-status { display: inline-block; padding: 5px 9px; border-radius: 15px; background: #fff3cd; color: #765900; font-size: 11px; text-transform: capitalize; }
    .order-content-grid { display: grid; grid-template-columns: minmax(0, 1fr) 310px; gap: 18px; align-items: start; }
    .order-card { overflow: hidden; border: 1px solid #dfe3e8; border-radius: 10px; background: #fff; box-shadow: 0 3px 12px rgba(23,38,58,.045); }
    .order-card + .order-card { margin-top: 18px; }
    .order-card-title { margin: 0; padding: 17px 20px; border-bottom: 1px solid #e6e9ed; font-size: 16px; font-weight: 700; }
    .order-products { padding: 0 20px; }
    .order-product { display: grid; grid-template-columns: 76px minmax(0, 1fr) auto; gap: 14px; align-items: center; padding: 17px 0; border-bottom: 1px solid #eaedf0; }
    .order-product:last-child { border-bottom: 0; }
    .order-product-image { width: 76px; height: 76px; overflow: hidden; border-radius: 6px; background: #f1f2f4; }
    .order-product-image img { width: 100%; height: 100%; object-fit: cover; }
    .order-product-name { margin: 0 0 7px; color: #182033; font-size: 14px; font-weight: 600; }
    .order-product-meta { margin: 0; color: #758094; font-size: 11px; }
    .order-product-total { color: #111827; font-size: 14px; font-weight: 700; white-space: nowrap; }
    .address-content { padding: 19px 20px; color: #596477; font-size: 13px; line-height: 1.65; }
    .address-name { margin-bottom: 4px; color: #172033; font-size: 14px; font-weight: 700; }
    .address-content a { color: #596477; text-decoration: none; }
    .summary-body { padding: 17px 20px; }
    .summary-row { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 12px; color: #637084; font-size: 13px; }
    .summary-row strong { color: #172033; }
    .summary-row.total { margin: 16px 0 0; padding-top: 16px; border-top: 1px solid #dfe3e8; color: #172033; font-size: 15px; font-weight: 700; }
    .payment-info { padding: 17px 20px; }
    .payment-info-row { margin-bottom: 13px; }
    .payment-info-row:last-child { margin-bottom: 0; }
    .payment-info-row small { display: block; margin-bottom: 4px; color: #7b8492; font-size: 10px; text-transform: uppercase; }
    .payment-info-row strong { font-size: 13px; text-transform: capitalize; }
    .order-note { margin-top: 18px; padding: 15px 18px; border-radius: 8px; background: #fff9e8; color: #64552a; font-size: 12px; line-height: 1.55; }
    @media (max-width: 850px) { .order-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); } .order-content-grid { grid-template-columns: 1fr; } }
    @media (max-width: 575px) { .order-details-page { padding: 23px 10px 60px; } .order-page-header { flex-direction: column; } .order-overview { gap: 9px; } .overview-item { padding: 13px; } .order-products { padding: 0 13px; } .order-product { grid-template-columns: 62px minmax(0, 1fr); gap: 10px; } .order-product-image { width: 62px; height: 62px; } .order-product-total { grid-column: 2; } }
</style>

@php
    $address = $order->billingAddress;
    $shippingCharge = (float) ($order->delivery_charge ?? 0);
    $subtotal = (float) ($order->total_price ?? 0);
    $grandTotal = (float) ($order->final_price ?? ($subtotal + $shippingCharge));
@endphp

<main class="order-details-page">
    <div class="order-details-container">
        <header class="order-page-header">
            <div><h1>Order Details</h1><p>Review your order information and purchased products</p></div>
            <a class="order-back" href="{{ route('customer-dashboard') }}"><i class="fa fa-arrow-left"></i> Dashboard</a>
        </header>

        <section class="order-overview">
            <div class="overview-item"><small>Order Number</small><strong>#{{ $order->custom_order_id }}</strong></div>
            <div class="overview-item"><small>Order Date</small><strong>{{ optional($order->created_at)->format('d M Y, h:i A') }}</strong></div>
            <div class="overview-item"><small>Payment</small><strong>{{ ucwords(strtolower($order->payment_status ?: 'Pending')) }}</strong></div>
            <div class="overview-item"><small>Order Status</small><span class="order-status">{{ strtolower($order->order_status ?: 'Processing') }}</span></div>
        </section>

        <div class="order-content-grid">
            <div>
                <section class="order-card">
                    <h2 class="order-card-title">Products</h2>
                    <div class="order-products">
                        @forelse($orderDetails as $detail)
                            @php($product = $detail->product)
                            <article class="order-product">
                                <div class="order-product-image"><img src="{{ \App\Support\MediaStorage::url($product?->img_path, 'products') }}" alt="{{ $product?->name ?: 'Product' }}"></div>
                                <div>
                                    <h3 class="order-product-name">{{ $product?->name ?: 'Product unavailable' }}</h3>
                                    <p class="order-product-meta">Qty: {{ $detail->quantity }} &times; {{ $febCurrency->format($detail->unit_price) }}</p>
                                </div>
                                <div class="order-product-total">{{ $febCurrency->format($detail->total ?: $detail->quantity * $detail->unit_price) }}</div>
                            </article>
                        @empty
                            <p style="padding:25px 0;color:#748095">No product details are available.</p>
                        @endforelse
                    </div>
                </section>

                <section class="order-card">
                    <h2 class="order-card-title">Delivery Address</h2>
                    <div class="address-content">
                        <div class="address-name">{{ trim(($address?->first_name ?? '') . ' ' . ($address?->last_name ?? '')) }}</div>
                        <div>{{ $address?->shipping_address }}</div>
                        @if($address?->shipping_address_2)<div>{{ $address->shipping_address_2 }}</div>@endif
                        <div>{{ collect([$address?->city, $address?->state, $address?->zip])->filter()->implode(', ') }}</div>
                        @if($address?->mobile)<div><a href="tel:{{ preg_replace('/[^0-9+]/', '', $address->mobile) }}">{{ $address->mobile }}</a></div>@endif
                    </div>
                </section>

                @if($order->order_note)<div class="order-note"><strong>Order Note:</strong> {{ $order->order_note }}</div>@endif
            </div>

            <aside>
                <section class="order-card">
                    <h2 class="order-card-title">Order Summary</h2>
                    <div class="summary-body">
                        <div class="summary-row"><span>Subtotal</span><strong>{{ $febCurrency->format($subtotal) }}</strong></div>
                        @if((float) $order->discount > 0)<div class="summary-row"><span>Discount</span><strong>-{{ $febCurrency->format($order->discount) }}</strong></div>@endif
                        <div class="summary-row"><span>Shipping{{ $order->shipping_method ? ' (' . $order->shipping_method . ')' : '' }}</span><strong>{{ $febCurrency->format($shippingCharge) }}</strong></div>
                        <div class="summary-row total"><span>Total</span><span>{{ $febCurrency->format($grandTotal) }}</span></div>
                    </div>
                </section>

                <section class="order-card">
                    <h2 class="order-card-title">Payment Information</h2>
                    <div class="payment-info">
                        <div class="payment-info-row"><small>Method</small><strong>{{ $order->payment_type ?: 'Cash on Delivery' }}</strong></div>
                        <div class="payment-info-row"><small>Status</small><strong>{{ $order->payment_status ?: 'Not Paid' }}</strong></div>
                        <div class="payment-info-row"><small>Delivery Status</small><strong>{{ $order->delivery_status ?: 'Pending' }}</strong></div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</main>
@endsection
