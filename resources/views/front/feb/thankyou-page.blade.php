@extends('front.feb.layouts.master')

@section('title', 'Order Confirmed')

@section('content')
    @php
        $customerName = trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? ''));
        $orderSku = $orderDetails->pluck('product.product_code')->filter()->unique()->implode(', ');
        $orderStatus = $order->order_status ?: 'Pending';
    @endphp

    <style>
        .order-thankyou-page{min-height:70vh;padding:38px 26px 70px;background:#fff;color:#263244;font-family:Arial,Helvetica,sans-serif}
        .order-thankyou-page__inner{width:100%;margin:0 auto}
        .order-thankyou-success{margin:0 0 31px;color:#009510;font-size:18px;font-weight:500;line-height:1.5}
        .order-thankyou-table-wrap{width:100%;margin-bottom:16px;overflow-x:auto}
        .order-thankyou-table{width:100%;min-width:760px;border-collapse:collapse;border-spacing:0;background:#fff;color:#344054;font-size:14px}
        .order-thankyou-table th,.order-thankyou-table td{border:1px solid #d8dee6;padding:15px 12px;line-height:1.45;vertical-align:top}
        .order-thankyou-info th{width:30%;font-weight:400;text-align:left}
        .order-thankyou-info td{font-weight:400;text-align:left}
        .order-products-table thead th{height:49px;font-weight:400;text-align:center;vertical-align:middle}
        .order-products-table tbody td{text-align:center}
        .order-products-table .product-description{text-align:left}
        .order-product-image{display:block;width:152px;height:210px;margin:0 auto;border:1px solid #d7dce3;border-radius:3px;object-fit:contain}
        .order-product-name{display:block;margin:1px 0 7px;color:#344054;font-weight:500;text-transform:uppercase}
        .order-product-data{display:block;margin-bottom:5px}
        .order-product-data:last-child{margin-bottom:0}
        .order-price{white-space:nowrap}
        .order-thankyou-actions{display:flex;justify-content:center;gap:12px;margin-top:28px;flex-wrap:wrap}
        .order-thankyou-action{display:inline-flex;min-width:150px;min-height:43px;align-items:center;justify-content:center;border:1px solid #151515;background:#151515;padding:10px 18px;color:#fff!important;font-size:12px;font-weight:700;text-decoration:none!important;text-transform:uppercase}
        .order-thankyou-action.secondary{background:#fff;color:#151515!important}
        @media(max-width:767px){.order-thankyou-page{padding:28px 12px 88px}.order-thankyou-success{margin-bottom:22px;font-size:15px}.order-thankyou-table{font-size:12px}.order-thankyou-table th,.order-thankyou-table td{padding:11px 9px}.order-thankyou-info{min-width:600px}.order-product-image{width:100px;height:138px}}
    </style>

    <main class="order-thankyou-page">
        <div class="order-thankyou-page__inner">
            <p class="order-thankyou-success">Your order has been placed successfully, Please wait for confirmation.</p>

            <div class="order-thankyou-table-wrap">
                <table class="order-thankyou-table order-thankyou-info">
                    <tbody>
                        <tr><th>Order Id</th><td>{{ $order->custom_order_id }}</td></tr>
                        <tr><th>Name</th><td>{{ $customerName ?: 'N/A' }}</td></tr>
                        <tr><th>Mobile No.</th><td>{{ $order->order_phone_number ?: ($order->mobile ?? 'N/A') }}</td></tr>
                        <tr><th>E-Mail</th><td>{{ $order->email ?: 'N/A' }}</td></tr>
                        <tr><th>Address</th><td>{{ $order->shipping_address ?: 'N/A' }}</td></tr>
                        <tr><th>SKU</th><td>{{ $orderSku ?: 'N/A' }}</td></tr>
                        <tr>
                            <th>Order Amount</th>
                            <td>{{ $febCurrency->format($order->final_price) }} (Delivery Cost {{ $febCurrency->format($order->delivery_charge) }})</td>
                        </tr>
                        <tr><th>Status</th><td>{{ ucfirst(strtolower($orderStatus)) }}</td></tr>
                        <tr><th>Customer's Comment</th><td>{{ $order->order_note ?: 'N/A' }}</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="order-thankyou-table-wrap">
                <table class="order-thankyou-table order-products-table">
                    <thead>
                        <tr>
                            <th style="width:5%">SL</th>
                            <th style="width:10%">Image</th>
                            <th>Product Name</th>
                            <th style="width:10%">Quantity</th>
                            <th style="width:10%">Unit Price</th>
                            <th style="width:10%">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderDetails as $detail)
                            @php
                                $lineTotal = (float) ($detail->total ?: ($detail->quantity * $detail->unit_price));
                                $detailProduct = $detail->product;
                                $productName = $detailProduct->name ?? 'Product unavailable';
                                $productImage = $detailProduct && $detailProduct->img_path
                                    ? \App\Support\MediaStorage::url($detailProduct->img_path, 'products')
                                    : asset('uploads/blank.png');
                                $productColor = data_get($detail, 'product_color');
                                $productSize = data_get($detail, 'product_size');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img class="order-product-image" src="{{ $productImage }}" alt="{{ $productName }}"></td>
                                <td class="product-description">
                                    <span class="order-product-name">{{ $productName }}</span>
                                    <span class="order-product-data">SKU: {{ $detailProduct->product_code ?? 'N/A' }}</span>
                                    <span class="order-product-data">PID: {{ $detailProduct->id ?? $detail->product_id }}</span>
                                    @if($productColor)
                                        <span class="order-product-data">Color: {{ $productColor }}</span>
                                    @endif
                                    @if($productSize)
                                        <span class="order-product-data">Size: {{ $productSize }}</span>
                                    @endif
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td class="order-price">{{ $febCurrency->format($detail->unit_price) }}</td>
                                <td class="order-price">{{ $febCurrency->format($lineTotal) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No product details are available for this order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="order-thankyou-actions">
                <a class="order-thankyou-action" href="{{ route('order-tracking') }}">Track Order</a>
                <a class="order-thankyou-action secondary" href="{{ route('shop-new') }}">Continue Shopping</a>
            </div>
        </div>
    </main>
@endsection
