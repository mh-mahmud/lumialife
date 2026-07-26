@extends('front.feb.layouts.master')

@section('title', 'Customer Dashboard')

@section('content')
<style>
    .customer-dashboard { min-height: 72vh; padding: 18px 16px 78px; background: #f4f6f9; color: #141a24; }
    .customer-dashboard-container { width: min(1045px, 100%); margin: 0 auto; }
    .customer-welcome { margin-bottom: 28px; }
    .customer-welcome h1 { margin: 0 0 7px; color: #050505; font-size: 23px; font-weight: 700; line-height: 1.3; }
    .customer-welcome p { margin: 0; color: #2d3035; font-size: 15px; }

    .affiliate-card {
        position: relative;
        min-height: 313px;
        overflow: hidden;
        padding: 29px 36px;
        border-radius: 18px;
        background: linear-gradient(115deg, #101010 0%, #1d1d1d 100%);
        color: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
    }
    .affiliate-content { position: relative; z-index: 2; max-width: 610px; }
    .affiliate-tag { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; padding: 6px 13px; border-radius: 20px; background: #2b2b2b; font-size: 11px; font-weight: 700; letter-spacing: 1px; }
    .affiliate-tag::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #20b486; }
    .affiliate-card h2 { margin: 0 0 7px; font-size: 29px; font-weight: 700; }
    .affiliate-description { max-width: 390px; margin: 0 0 20px; color: #d6dbe4; font-size: 15px; line-height: 1.5; }
    .affiliate-benefits { display: flex; flex-wrap: wrap; gap: 28px; margin-bottom: 24px; }
    .affiliate-benefit { display: flex; align-items: center; gap: 10px; }
    .affiliate-benefit-icon { display: grid; place-items: center; width: 39px; height: 39px; border-radius: 9px; background: #292929; color: #ffbd12; font-size: 15px; }
    .affiliate-benefit strong { display: block; margin-bottom: 2px; font-size: 15px; }
    .affiliate-benefit small { display: block; color: #aeb5c0; font-size: 11px; }
    .affiliate-cta { display: inline-flex; align-items: center; justify-content: center; gap: 11px; min-width: 204px; padding: 14px 19px; border-radius: 10px; background: #fff; color: #101010; font-size: 13px; font-weight: 700; text-decoration: none; }
    .affiliate-cta:hover { color: #101010; background: #f3f3f3; text-decoration: none; }
    .affiliate-visual { position: absolute; top: 50%; right: 72px; width: 145px; height: 145px; transform: translateY(-50%); border: 1px solid rgba(255,255,255,.12); border-radius: 50%; display: grid; place-items: center; }
    .affiliate-visual::before { content: ''; position: absolute; inset: 18px; border: 1px solid rgba(255,255,255,.12); border-radius: 50%; }
    .affiliate-rocket { position: relative; z-index: 1; display: grid; place-items: center; width: 72px; height: 72px; border-radius: 16px; background: #ffb713; color: #131313; font-size: 28px; box-shadow: 0 10px 25px rgba(255,183,19,.22); }

    .dashboard-actions { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 15px; margin: 27px 0 16px; }
    .dashboard-action { display: flex; align-items: center; justify-content: center; gap: 12px; min-height: 44px; padding: 10px 14px; border: 1px solid #dfe3e8; border-radius: 10px; background: #fff; color: #17263a; font-size: 13px; text-decoration: none; box-shadow: 0 3px 9px rgba(23,38,58,.07); }
    .dashboard-action:hover { color: #17263a; border-color: #c5ccd5; text-decoration: none; transform: translateY(-1px); }
    .dashboard-action i { color: #354c66; font-size: 22px; }

    .recent-orders-card { padding: 20px 22px 21px; border: 1px solid #dde2e8; border-radius: 10px; background: #fff; box-shadow: 0 3px 10px rgba(23,38,58,.06); }
    .recent-orders-header { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 21px; }
    .recent-orders-header h2 { position: relative; margin: 0; padding-bottom: 10px; font-size: 19px; font-weight: 700; }
    .recent-orders-header h2::after { content: ''; position: absolute; bottom: 0; left: 0; width: 124px; height: 1px; background: #e5e7eb; }
    .recent-orders-header a { color: #161c26; font-size: 12px; font-weight: 600; text-decoration: none; }
    .orders-empty { min-height: 290px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 35px 20px; border: 1px solid #dce1e7; border-radius: 11px; text-align: center; }
    .orders-empty i { margin-bottom: 17px; color: #ccd1d9; font-size: 47px; }
    .orders-empty h3 { margin: 0 0 7px; color: #172033; font-size: 18px; font-weight: 700; }
    .orders-empty p { margin: 0 0 22px; color: #657189; font-size: 13px; }
    .orders-shop-button { display: inline-block; padding: 12px 22px; border-radius: 6px; background: #303030; color: #fff; font-size: 12px; font-weight: 600; text-decoration: none; }
    .orders-shop-button:hover { color: #fff; background: #181818; text-decoration: none; }
    .recent-orders-table-wrap { overflow-x: auto; border: 1px solid #e0e4e9; border-radius: 9px; }
    .recent-orders-table { width: 100%; min-width: 700px; border-collapse: collapse; }
    .recent-orders-table th { padding: 13px 15px; background: #f6f7f9; color: #596579; font-size: 11px; font-weight: 700; text-align: left; text-transform: uppercase; }
    .recent-orders-table td { padding: 15px; border-top: 1px solid #e7eaee; color: #263248; font-size: 13px; }
    .order-number { color: #172033; font-weight: 700; }
    .order-status { display: inline-block; padding: 5px 9px; border-radius: 15px; background: #fff3cd; color: #775b00; font-size: 10px; font-weight: 700; text-transform: capitalize; }
    .order-details-link { color: #ec315f; font-weight: 600; text-decoration: none; }

    @media (max-width: 850px) {
        .affiliate-visual { right: 35px; opacity: .55; }
        .affiliate-content { max-width: 75%; }
        .dashboard-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575px) {
        .customer-dashboard { padding: 16px 11px 58px; }
        .customer-welcome { margin-bottom: 20px; }
        .customer-welcome h1 { font-size: 20px; }
        .customer-welcome p { font-size: 13px; }
        .affiliate-card { min-height: auto; padding: 25px 20px; border-radius: 14px; }
        .affiliate-content { max-width: 100%; }
        .affiliate-card h2 { font-size: 25px; }
        .affiliate-description { font-size: 13px; }
        .affiliate-benefits { gap: 16px; }
        .affiliate-benefit { width: calc(50% - 8px); }
        .affiliate-visual { display: none; }
        .dashboard-actions { gap: 10px; margin-top: 17px; }
        .dashboard-action { flex-direction: column; gap: 5px; min-height: 69px; padding: 8px; text-align: center; }
        .dashboard-action i { font-size: 19px; }
        .recent-orders-card { padding: 17px 12px; }
        .orders-empty { min-height: 245px; }
    }
</style>

@php
    $customerName = trim(Auth::user()->first_name . ' ' . Auth::user()->last_name);
@endphp

<main class="customer-dashboard">
    <div class="customer-dashboard-container">
        <header class="customer-welcome">
            <h1>Welcome back, {{ ucwords($customerName) }}</h1>
            <p>Manage your orders, account, and preferences</p>
        </header>

        {{-- <section class="affiliate-card">
            <div class="affiliate-content">
                <span class="affiliate-tag">AFFILIATE PROGRAM</span>
                <h2>Join FabriSquad</h2>
                <p class="affiliate-description">Turn your influence into income. Share products you love and earn on every sale.</p>
                <div class="affiliate-benefits">
                    <div class="affiliate-benefit">
                        <span class="affiliate-benefit-icon"><i class="fa fa-money"></i></span>
                        <span><strong>5-10%</strong><small>Commission</small></span>
                    </div>
                    <div class="affiliate-benefit">
                        <span class="affiliate-benefit-icon"><i class="fa fa-calendar-check-o"></i></span>
                        <span><strong>Monthly</strong><small>Payouts</small></span>
                    </div>
                    <div class="affiliate-benefit">
                        <span class="affiliate-benefit-icon"><i class="fa fa-link"></i></span>
                        <span><strong>Unique</strong><small>Coupon Code</small></span>
                    </div>
                </div>
                <a class="affiliate-cta" href="{{ route('shop-new') }}">Start Earning Today <i class="fa fa-arrow-right"></i></a>
            </div>
            <div class="affiliate-visual"><span class="affiliate-rocket"><i class="fa fa-rocket"></i></span></div>
        </section> --}}

        <nav class="dashboard-actions" aria-label="Customer account shortcuts">
            <a class="dashboard-action" href="{{ route('customer-profile') }}"><i class="fa fa-user"></i><span>Edit Profile</span></a>
            {{-- <a class="dashboard-action" href="{{ route('customer-shipping-address') }}"><i class="fa fa-map-marker"></i><span>Manage Addresses</span></a> --}}
            <a class="dashboard-action" href="{{ route('customer-profile') }}"><i class="fa fa-cog"></i><span>Settings</span></a>
            <a class="dashboard-action" href="{{ route('order-tracking') }}"><i class="fa fa-truck"></i><span>Track Order</span></a>
        </nav>

        <section class="recent-orders-card">
            <header class="recent-orders-header">
                <h2>Recent Orders</h2>
                @if($recentOrders->isNotEmpty())
                    <a href="{{ route('customer-order-history') }}">View All <i class="fa fa-arrow-right"></i></a>
                @endif
            </header>

            @if($recentOrders->isEmpty())
                <div class="orders-empty">
                    <i class="fa fa-shopping-cart"></i>
                    <h3>No Orders Yet</h3>
                    <p>Start shopping to see your orders here</p>
                    <a class="orders-shop-button" href="{{ route('shop-new') }}">Start Shopping</a>
                </div>
            @else
                <div class="recent-orders-table-wrap">
                    <table class="recent-orders-table">
                        <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="order-number">#{{ $order->custom_order_id }}</td>
                                    <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                    <td>{{ $febCurrency->format($order->total_price) }}</td>
                                    <td>{{ ucwords(strtolower($order->payment_status ?: 'Pending')) }}</td>
                                    <td><span class="order-status">{{ strtolower($order->order_status ?: 'Processing') }}</span></td>
                                    <td><a class="order-details-link" href="{{ route('customer-order-details', $order->custom_order_id) }}">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</main>
@endsection
