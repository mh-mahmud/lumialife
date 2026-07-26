@extends('front.feb.layouts.master')

@section('title', 'Track Order')

@section('content')
<style>
    .tracking-page { min-height: 70vh; padding: 42px 16px 78px; background: #f4f6f9; color: #172033; }
    .tracking-container { width: min(1030px, 100%); margin: 0 auto; }
    .tracking-heading { margin-bottom: 27px; text-align: center; }
    .tracking-heading-icon { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 13px; border-radius: 50%; background: #172033; color: #fff; font-size: 22px; }
    .tracking-heading h1 { margin: 0 0 8px; font-size: 28px; font-weight: 700; }
    .tracking-heading p { margin: 0; color: #6c7687; font-size: 14px; }
    .tracking-search-card { max-width: 750px; margin: 0 auto; padding: 23px; border: 1px solid #dce1e7; border-radius: 10px; background: #fff; box-shadow: 0 4px 15px rgba(23,38,58,.055); }
    .tracking-form { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto; gap: 13px; align-items: end; }
    .tracking-field label { display: block; margin-bottom: 7px; font-size: 11px; font-weight: 700; }
    .tracking-field input { width: 100%; height: 46px; padding: 0 13px; border: 1px solid #cdd4dd; border-radius: 6px; background: #fff; color: #172033; font-size: 14px; }
    .tracking-field input:focus { outline: 0; border-color: #26354c; box-shadow: 0 0 0 3px rgba(38,53,76,.08); }
    .tracking-submit { height: 46px; padding: 0 23px; border: 0; border-radius: 6px; background: #202a3a; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; }
    .tracking-submit:hover { background: #111827; }
    .tracking-field-error { display: block; margin-top: 5px; color: #d42d2d; font-size: 11px; }
    .tracking-alert { margin-bottom: 18px; padding: 13px 15px; border: 1px solid #f0c7c7; border-radius: 6px; background: #fff1f1; color: #982929; font-size: 13px; }

    .tracking-result { margin-top: 23px; }
    .tracking-summary { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 20px 22px; border: 1px solid #dce1e7; border-radius: 10px 10px 0 0; background: #fff; }
    .tracking-order-number small { display: block; margin-bottom: 4px; color: #7b8493; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .tracking-order-number strong { font-size: 18px; }
    .tracking-current-status { display: inline-block; padding: 7px 12px; border-radius: 18px; background: #fff3cd; color: #765900; font-size: 11px; font-weight: 700; text-transform: capitalize; }
    .tracking-current-status.cancelled { background: #ffe4e4; color: #a32929; }

    .tracking-progress-card { padding: 31px 27px 27px; border: 1px solid #dce1e7; border-top: 0; background: #fff; }
    .tracking-progress { display: grid; grid-template-columns: repeat(5, 1fr); }
    .tracking-step { position: relative; text-align: center; }
    .tracking-step::before { content: ''; position: absolute; top: 18px; right: 50%; width: 100%; height: 3px; background: #e0e4e9; }
    .tracking-step:first-child::before { display: none; }
    .tracking-step.completed::before, .tracking-step.active::before { background: #20a978; }
    .tracking-step-dot { position: relative; z-index: 1; display: grid; place-items: center; width: 38px; height: 38px; margin: 0 auto 10px; border: 3px solid #e0e4e9; border-radius: 50%; background: #fff; color: #a4abb5; font-size: 13px; }
    .tracking-step.completed .tracking-step-dot, .tracking-step.active .tracking-step-dot { border-color: #20a978; background: #20a978; color: #fff; }
    .tracking-step.active .tracking-step-dot { box-shadow: 0 0 0 5px rgba(32,169,120,.13); }
    .tracking-step strong { display: block; color: #7d8795; font-size: 11px; }
    .tracking-step.completed strong, .tracking-step.active strong { color: #172033; }

    .tracking-info-grid { display: grid; grid-template-columns: minmax(0, 1fr) 310px; gap: 18px; margin-top: 18px; align-items: start; }
    .tracking-card { overflow: hidden; border: 1px solid #dce1e7; border-radius: 9px; background: #fff; }
    .tracking-card + .tracking-card { margin-top: 16px; }
    .tracking-card h2 { margin: 0; padding: 16px 18px; border-bottom: 1px solid #e6e9ed; font-size: 15px; font-weight: 700; }
    .tracking-products { padding: 0 18px; }
    .tracking-product { display: grid; grid-template-columns: 64px minmax(0, 1fr) auto; gap: 12px; align-items: center; padding: 14px 0; border-bottom: 1px solid #e8ebef; }
    .tracking-product:last-child { border-bottom: 0; }
    .tracking-product img { width: 64px; height: 64px; border-radius: 5px; object-fit: cover; background: #f1f2f4; }
    .tracking-product h3 { margin: 0 0 5px; font-size: 13px; font-weight: 600; }
    .tracking-product p { margin: 0; color: #7b8494; font-size: 11px; }
    .tracking-product-price { font-size: 13px; font-weight: 700; white-space: nowrap; }
    .tracking-address { padding: 17px 18px; color: #5f6979; font-size: 12px; line-height: 1.65; }
    .tracking-address strong { display: block; margin-bottom: 4px; color: #172033; font-size: 13px; }
    .tracking-meta { padding: 16px 18px; }
    .tracking-meta-row { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 11px; color: #697486; font-size: 12px; }
    .tracking-meta-row:last-child { margin-bottom: 0; }
    .tracking-meta-row strong { color: #172033; text-align: right; }
    .tracking-total { margin-top: 14px; padding-top: 14px; border-top: 1px solid #e2e6eb; font-size: 14px; font-weight: 700; }
    .tracking-help { margin-top: 18px; padding: 15px; border-radius: 8px; background: #eef2f6; color: #596578; font-size: 12px; text-align: center; }
    .tracking-help i { margin-right: 5px; }

    @media (max-width: 760px) { .tracking-form { grid-template-columns: 1fr; } .tracking-info-grid { grid-template-columns: 1fr; } }
    @media (max-width: 575px) {
        .tracking-page { padding: 28px 10px 60px; }
        .tracking-heading h1 { font-size: 24px; }
        .tracking-search-card { padding: 18px 14px; }
        .tracking-summary { align-items: flex-start; padding: 16px; }
        .tracking-progress-card { padding: 25px 7px 22px; overflow-x: auto; }
        .tracking-progress { min-width: 480px; }
        .tracking-product { grid-template-columns: 55px minmax(0, 1fr); }
        .tracking-product img { width: 55px; height: 55px; }
        .tracking-product-price { grid-column: 2; }
    }
</style>

<main class="tracking-page">
    <div class="tracking-container">
        <header class="tracking-heading">
            <span class="tracking-heading-icon"><i class="fa fa-truck"></i></span>
            <h1>Track Your Order</h1>
            <p>Enter your order ID and the phone number used during checkout.</p>
        </header>

        @error('tracking')<div class="tracking-alert">{{ $message }}</div>@enderror

        <section class="tracking-search-card">
            <form class="tracking-form" action="{{ route('order-tracking.search') }}" method="POST">
                @csrf
                <div class="tracking-field">
                    <label for="tracking-order-id">Order ID</label>
                    <input id="tracking-order-id" type="text" name="order_id" value="{{ old('order_id', $trackedOrder->custom_order_id ?? '') }}" placeholder="e.g. ORD-123456" required>
                    @error('order_id')<span class="tracking-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="tracking-field">
                    <label for="tracking-phone">Phone Number</label>
                    <input id="tracking-phone" type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="01XXXXXXXXX" required>
                    @error('phone_number')<span class="tracking-field-error">{{ $message }}</span>@enderror
                </div>
                <button class="tracking-submit" type="submit"><i class="fa fa-search"></i> Track Order</button>
            </form>
        </section>

        @isset($trackedOrder)
            @php
                $displayStatus = $trackedOrder->delivery_status ?: $trackedOrder->order_status ?: 'Order Placed';
                $rawStatus = strtolower(trim((string) $displayStatus));
                $cancelled = str_contains($rawStatus, 'cancel');
                $statusIndex = match (true) {
                    str_contains($rawStatus, 'deliver'), str_contains($rawStatus, 'complete') => 4,
                    str_contains($rawStatus, 'ship'), str_contains($rawStatus, 'courier'), str_contains($rawStatus, 'out for') => 3,
                    str_contains($rawStatus, 'confirm') => 2,
                    str_contains($rawStatus, 'process') => 1,
                    default => 0,
                };
                $steps = [
                    ['Order Placed', 'fa-shopping-bag'],
                    ['Processing', 'fa-cog'],
                    ['Confirmed', 'fa-check'],
                    ['Shipped', 'fa-truck'],
                    ['Delivered', 'fa-home'],
                ];
                $address = $trackedOrder->billingAddress;
            @endphp

            <section class="tracking-result">
                <div class="tracking-summary">
                    <div class="tracking-order-number"><small>Order Number</small><strong>#{{ $trackedOrder->custom_order_id }}</strong></div>
                    <span class="tracking-current-status {{ $cancelled ? 'cancelled' : '' }}">{{ $displayStatus }}</span>
                </div>

                <div class="tracking-progress-card">
                    @if($cancelled)
                        <div class="tracking-alert" style="margin:0;text-align:center"><strong>This order has been cancelled.</strong>@if($trackedOrder->cancel_reason) {{ $trackedOrder->cancel_reason }}@endif</div>
                    @else
                        <div class="tracking-progress">
                            @foreach($steps as $index => [$label, $icon])
                                <div class="tracking-step {{ $index < $statusIndex ? 'completed' : ($index === $statusIndex ? 'active' : '') }}">
                                    <span class="tracking-step-dot"><i class="fa {{ $index < $statusIndex ? 'fa-check' : $icon }}"></i></span>
                                    <strong>{{ $label }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="tracking-info-grid">
                    <div>
                        <section class="tracking-card">
                            <h2>Order Products</h2>
                            <div class="tracking-products">
                                @forelse($trackedOrder->orderDetails as $detail)
                                    @php($product = $detail->product)
                                    <article class="tracking-product">
                                        <img src="{{ \App\Support\MediaStorage::url($product?->img_path, 'products') }}" alt="{{ $product?->name ?: 'Product' }}">
                                        <div><h3>{{ $product?->name ?: 'Product unavailable' }}</h3><p>Qty: {{ $detail->quantity }} &times; {{ $febCurrency->format($detail->unit_price) }}</p></div>
                                        <span class="tracking-product-price">{{ $febCurrency->format($detail->total ?: $detail->quantity * $detail->unit_price) }}</span>
                                    </article>
                                @empty
                                    <p style="padding:20px 0;color:#727d8e">No product details available.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="tracking-card">
                            <h2>Delivery Address</h2>
                            <div class="tracking-address">
                                <strong>{{ trim(($address?->first_name ?? '') . ' ' . ($address?->last_name ?? '')) }}</strong>
                                <div>{{ $address?->shipping_address }}</div>
                                @if($address?->shipping_address_2)<div>{{ $address->shipping_address_2 }}</div>@endif
                                <div>{{ collect([$address?->city, $address?->state, $address?->zip])->filter()->implode(', ') }}</div>
                                @if($address?->mobile)<div>{{ $address->mobile }}</div>@endif
                            </div>
                        </section>
                    </div>

                    <aside>
                        <section class="tracking-card">
                            <h2>Order Information</h2>
                            <div class="tracking-meta">
                                <div class="tracking-meta-row"><span>Order Date</span><strong>{{ optional($trackedOrder->created_at)->format('d M Y') }}</strong></div>
                                <div class="tracking-meta-row"><span>Payment</span><strong>{{ $trackedOrder->payment_type ?: 'Cash on Delivery' }}</strong></div>
                                <div class="tracking-meta-row"><span>Payment Status</span><strong>{{ $trackedOrder->payment_status ?: 'Not Paid' }}</strong></div>
                                <div class="tracking-meta-row"><span>Shipping</span><strong>{{ $trackedOrder->shipping_method ?: 'Standard' }}</strong></div>
                                <div class="tracking-meta-row tracking-total"><span>Total</span><strong>{{ $febCurrency->format($trackedOrder->final_price ?: $trackedOrder->total_price + $trackedOrder->delivery_charge) }}</strong></div>
                            </div>
                        </section>
                    </aside>
                </div>
            </section>
        @endisset

        <div class="tracking-help"><i class="fa fa-phone"></i> Need help with your order? Contact our customer support.</div>
    </div>
</main>
@endsection
