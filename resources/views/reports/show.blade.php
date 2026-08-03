@extends('layouts.master')

@section('content')
<style>
    .product-report-table{min-width:1120px;color:#26344f}.product-report-table thead th{padding:15px 12px;border-bottom:1px solid #dfe6ef;color:#8da0bd;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;white-space:nowrap}.product-report-table tbody td{padding:13px 12px;border-bottom:1px solid #e8edf4;font-size:11px;vertical-align:middle}.product-report-table tbody tr:hover{background:#f7faff}.product-report-name{display:flex;align-items:center;gap:11px;min-width:235px}.product-report-avatar{display:grid;width:34px;height:34px;flex:0 0 34px;place-items:center;border-radius:50%;background:#e9f1ff;color:#3473f4;font-size:11px;font-weight:800}.product-report-name strong{display:block;color:#1f2d47;font-size:12px}.product-report-name small{display:block;margin-top:3px;color:#91a0b7;font-size:9px}.product-report-status{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:12px;background:#e9faf3;color:#17ae7b;font-size:9px;font-weight:700}.product-report-status:before{content:"";width:5px;height:5px;border-radius:50%;background:currentColor}.product-report-status.inactive{background:#f0f3f7;color:#8998ac}.product-report-meta{color:#617694;font-family:monospace;font-size:10px}.product-report-check{width:15px;height:15px;border:1px solid #aebdd2;border-radius:4px}.product-report-pagination .pagination{justify-content:flex-end;margin-bottom:0}
    .product-report-image{width:42px;height:42px;flex:0 0 42px;border:1px solid #e0e7f0;border-radius:8px;background:#f3f6fa;object-fit:cover}.sales-report-products{display:flex;min-width:220px;flex-direction:column;gap:7px}.sales-report-product{display:flex;align-items:center;gap:8px}.sales-report-product img{width:34px;height:34px;border-radius:6px;background:#f3f6fa;object-fit:cover}.sales-report-product strong,.sales-report-product small{display:block}.sales-report-product small{margin-top:2px;color:#8494ab;font-size:9px}
    .order-report-table{min-width:2100px;color:#26344f}.order-report-table thead th{padding:15px 12px;border-bottom:1px solid #dfe6ef;color:#8da0bd;font-size:9px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;white-space:nowrap}.order-report-table tbody td{padding:13px 12px;border-bottom:1px solid #e8edf4;font-size:11px;vertical-align:middle}.order-report-table tbody tr:hover{background:#f7faff}.order-report-note{display:block;max-width:210px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.order-report-status{display:inline-flex;padding:5px 9px;border-radius:12px;background:#e9f1ff;color:#3473f4;font-size:9px;font-weight:700;white-space:nowrap}
    .sales-report-table{min-width:1120px;color:#26344f}.sales-report-table thead th{padding:15px 12px;border-bottom:1px solid #dfe6ef;color:#8da0bd;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;white-space:nowrap}.sales-report-table tbody td{padding:13px 12px;border-bottom:1px solid #e8edf4;font-size:11px;vertical-align:middle}.sales-report-table tbody tr:hover{background:#f7faff}.sales-report-order{color:#1f2d47;font-size:12px;font-weight:800}.sales-report-customer{display:flex;align-items:center;gap:10px;min-width:180px}.sales-report-customer-avatar{display:grid;width:34px;height:34px;flex:0 0 34px;place-items:center;border-radius:50%;background:#e9f1ff;color:#3473f4;font-size:10px;font-weight:800}.sales-report-customer strong,.sales-report-customer small{display:block}.sales-report-customer small{margin-top:2px;color:#8494ab;font-size:9px}.sales-report-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:12px;background:#e9faf3;color:#17ae7b;font-size:9px;font-weight:700;white-space:nowrap}.sales-report-badge:before{content:"";width:5px;height:5px;border-radius:50%;background:currentColor}.sales-report-badge.pending{background:#fff5df;color:#d99416}.sales-report-badge.cancelled{background:#fff0f1;color:#df5963}.sales-report-date{color:#617694;font-family:monospace;font-size:10px;white-space:nowrap}
    .customer-report-table{min-width:1050px;color:#26344f}.customer-report-table thead th{padding:15px 12px;border-bottom:1px solid #dfe6ef;color:#8da0bd;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;white-space:nowrap}.customer-report-table tbody td{padding:13px 12px;border-bottom:1px solid #e8edf4;font-size:11px;vertical-align:middle}.customer-report-table tbody tr:hover{background:#f7faff}.customer-report-person{display:flex;align-items:center;gap:10px;min-width:210px}.customer-report-person strong,.customer-report-person small{display:block}.customer-report-person small{margin-top:2px;color:#8494ab;font-size:9px}
</style>
<div class="container-fluid py-6">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
        <div>
            <div class="text-muted text-uppercase fs-8 fw-bold mb-2">Reports</div>
            <h1 class="fw-bolder text-dark mb-2">{{ $report['title'] }}</h1>
            <div class="text-muted">{{ $report['description'] }}</div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-light">Back to Dashboard</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header border-0">
            <div class="card-title"><h3 class="fw-bolder m-0">Report Filters</h3></div>
        </div>
        <div class="card-body pt-0">
            <form method="GET" action="{{ route('reports.show', $reportKey) }}" class="row g-4 align-items-end">
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label fw-bold" for="report-start-date">Start Date</label>
                    <input id="report-start-date" type="date" name="start_date" class="form-control"
                        value="{{ $filters['start_date'] }}" max="{{ now()->toDateString() }}" required>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label fw-bold" for="report-end-date">End Date</label>
                    <input id="report-end-date" type="date" name="end_date" class="form-control"
                        value="{{ $filters['end_date'] }}" max="{{ now()->toDateString() }}" required>
                </div>
                @if($reportKey === 'profit-loss')
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-order-status">Order Status</label>
                        <select id="report-order-status" name="order_status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(['PROCESSING', 'PENDING', 'CONFIRMED', 'COMPLETED', 'CANCELLED'] as $status)
                                <option value="{{ $status }}" @selected(request('order_status') === $status)>{{ ucwords(strtolower($status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-customer-search">Search</label>
                        <input id="report-customer-search" name="customer_search" type="search" class="form-control" value="{{ request('customer_search') }}" placeholder="Order, Customer or Phone">
                    </div>
                @elseif($reportKey === 'payments')
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-payment-type">Payment Method</label>
                        <select id="report-payment-type" name="payment_type" class="form-select">
                            <option value="">All Methods</option>
                            @foreach(['Cash on Delivery', 'Bkash'] as $method)
                                <option value="{{ $method }}" @selected(request('payment_type') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-payment-status">Payment Status</label>
                        <select id="report-payment-status" name="payment_status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(['PAID', 'NOT PAID', 'PARTIAL'] as $status)
                                <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ ucwords(strtolower($status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportKey === 'delivery')
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-delivery-status">Delivery Status</label>
                        <select id="report-delivery-status" name="delivery_status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'] as $status)
                                <option value="{{ $status }}" @selected(request('delivery_status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-customer-search">Search</label>
                        <input id="report-customer-search" name="customer_search" type="search" class="form-control" value="{{ request('customer_search') }}" placeholder="Customer Name or Phone">
                    </div>
                @elseif($reportKey === 'orders')
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-agent">Agent/Staff</label>
                        <select id="report-agent" name="agent_id" class="form-select" data-control="select2" data-placeholder="All Staff" data-allow-clear="true">
                            <option value="">All Staff</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->agent_id }}" @selected((string) request('agent_id') === (string) $agent->agent_id)>{{ trim($agent->first_name.' '.$agent->last_name) ?: $agent->agent_id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-customer-search">Search</label>
                        <input id="report-customer-search" name="customer_search" type="search" class="form-control" value="{{ request('customer_search') }}" placeholder="Customer Name or Phone">
                    </div>
                @elseif($reportKey === 'customers')
                    <div class="col-12 col-md-6 col-xl-4">
                        <label class="form-label fw-bold" for="report-customer-search">Search</label>
                        <input id="report-customer-search" name="customer_search" type="search" class="form-control" value="{{ request('customer_search') }}" placeholder="Customer Name or Phone">
                    </div>
                @elseif(in_array($reportKey, ['product-sales', 'best-selling-products', 'inventory'], true))
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-category">Category Name</label>
                        <select id="report-category" name="category_id" class="form-select" data-control="select2" data-placeholder="All Categories" data-allow-clear="true">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-product-name">Search</label>
                        <input id="report-product-name" name="product_name" type="search" class="form-control" value="{{ request('product_name') }}" placeholder="Search by Product Name">
                    </div>
                @else
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-static-type">Report Type</label>
                        <select id="report-static-type" class="form-select">
                            <option>All Records</option>
                            <option>Completed</option>
                            <option>Pending</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label fw-bold" for="report-static-search">Search</label>
                        <input id="report-static-search" type="text" class="form-control" placeholder="Type to search...">
                    </div>
                @endif
                <div class="col-12 col-md-6 col-xl-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Apply Filter</button>
                        <a href="{{ route('reports.show', $reportKey) }}" class="btn btn-light">Reset</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <button type="submit" name="export" value="xlsx" class="btn btn-success w-100">
                        <i class="fa fa-download me-2"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(in_array($reportKey, ['orders', 'delivery', 'sales', 'product-sales', 'customers', 'best-selling-products', 'inventory', 'payments', 'profit-loss'], true))
        <div class="row g-4 mt-2">
            @foreach($summary as $label => $value)
                <div class="col-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted text-uppercase fs-8 fw-bold">{{ str_replace('_', ' ', $label) }}</div>
                            <div class="fs-2 fw-bolder mt-2">
                                @if($label === 'margin')
                                    {{ number_format((float) $value, 2) }}%
                                @else
                                    {{ in_array($label, ['sales', 'paid', 'total_price', 'discount', 'delivery_charge', 'avg_order', 'amount', 'payable', 'due', 'revenue', 'cogs', 'profit'], true) ? '৳'.number_format((float) $value, 2) : number_format((int) $value) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mt-6">
            <div class="card-header border-0"><div class="card-title"><h3 class="fw-bolder m-0">{{ $report['title'] }} Data</h3></div></div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle {{ in_array($reportKey, ['product-sales', 'best-selling-products', 'inventory'], true) ? 'product-report-table' : (in_array($reportKey, ['orders', 'delivery'], true) ? 'order-report-table' : (in_array($reportKey, ['sales', 'payments', 'profit-loss'], true) ? 'sales-report-table' : ($reportKey === 'customers' ? 'customer-report-table' : ''))) }}">
                        @if($reportKey === 'profit-loss')
                            <thead><tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Order Status</th><th class="text-end">Revenue</th><th class="text-end">COGS</th><th class="text-end">Profit</th><th class="text-end">Margin</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    @php
                                        $revenue = (float) $row->final_price;
                                        $cost = (float) $row->cost_total;
                                        $profit = $revenue - $cost;
                                        $margin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td><a href="{{ route('orders-show', $row->id) }}" class="sales-report-order">{{ $row->custom_order_id ?: '#'.$row->id }}</a></td>
                                        <td><span class="sales-report-date">{{ optional($row->created_at)->format('d M Y · H:i') }}</span></td>
                                        <td class="fw-bold">{{ trim(optional($row->billingAddress)->first_name.' '.optional($row->billingAddress)->last_name) ?: 'Guest Customer' }}</td>
                                        <td><span class="sales-report-badge pending">{{ $row->order_status ?: 'Pending' }}</span></td>
                                        <td class="text-end">৳{{ number_format($revenue, 2) }}</td>
                                        <td class="text-end">৳{{ number_format($cost, 2) }}</td>
                                        <td class="text-end fw-bold {{ $profit < 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($profit, 2) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($margin, 2) }}%</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-8 text-center text-muted">No profit/loss data found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'payments')
                            <thead><tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Phone</th><th>Payment Method</th><th>Payment Status</th><th class="text-end">Payable</th><th class="text-end">Paid</th><th class="text-end">Due</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    @php
                                        $payable = (float) $row->final_price;
                                        $paidAmount = (float) $row->pay_amount;
                                        $paymentStatus = strtolower($row->payment_status ?: 'not paid');
                                    @endphp
                                    <tr>
                                        <td><a href="{{ route('orders-show', $row->id) }}" class="sales-report-order">{{ $row->custom_order_id ?: '#'.$row->id }}</a></td>
                                        <td><span class="sales-report-date">{{ optional($row->created_at)->format('d M Y · H:i') }}</span></td>
                                        <td class="fw-bold">{{ trim(optional($row->billingAddress)->first_name.' '.optional($row->billingAddress)->last_name) ?: 'Guest Customer' }}</td>
                                        <td>{{ optional($row->billingAddress)->mobile ?: $row->order_phone_number }}</td>
                                        <td>{{ $row->payment_type ?: 'Cash on Delivery' }}</td>
                                        <td><span class="sales-report-badge {{ $paymentStatus === 'paid' ? '' : 'pending' }}">{{ $row->payment_status ?: 'Not Paid' }}</span></td>
                                        <td class="text-end">৳{{ number_format($payable, 2) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format($paidAmount, 2) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format(max(0, $payable - $paidAmount), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-8 text-center text-muted">No payments found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'inventory')
                            <thead><tr><th>Product Name</th><th>SKU</th><th class="text-end">Stock</th><th class="text-end">Price</th><th>Stock Status</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td><div class="product-report-name"><img class="product-report-image" src="{{ $row->img_path ? \App\Support\MediaStorage::url($row->img_path, 'products') : asset('uploads/blank.png') }}" alt="{{ $row->name }}"><div><strong>{{ $row->name }}</strong><small>{{ optional($row->category)->category_name ?: 'Uncategorized' }}</small></div></div></td>
                                        <td><span class="product-report-meta">{{ $row->product_code ?: '—' }}</span></td>
                                        <td class="text-end fw-bold">{{ number_format((float) $row->stock_quantity, 0) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->product_value, 2) }}</td>
                                        <td><span class="product-report-status {{ (float) $row->stock_quantity > 0 ? '' : 'inactive' }}">{{ (float) $row->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-8 text-center text-muted">No inventory products found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'delivery')
                            <thead><tr><th>Order ID</th><th>Customer</th><th>Phone</th><th>Agent</th><th>Order Status</th><th>Delivery Status</th><th class="text-end">Amount</th><th>Possible Delivery Date</th><th>Delivery Date</th><th>Delivery Note</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    @php $deliveryStatus = strtolower($row->delivery_status ?: 'pending'); @endphp
                                    <tr>
                                        <td><a href="{{ route('orders-show', $row->id) }}" class="sales-report-order">{{ $row->custom_order_id ?: '#'.$row->id }}</a></td>
                                        <td class="fw-bold">{{ trim(optional($row->billingAddress)->first_name.' '.optional($row->billingAddress)->last_name) ?: 'Guest Customer' }}</td>
                                        <td>{{ optional($row->billingAddress)->mobile ?: $row->order_phone_number }}</td>
                                        <td>{{ trim(optional($row->assignedAgent)->first_name.' '.optional($row->assignedAgent)->last_name) ?: 'Unassigned' }}</td>
                                        <td><span class="sales-report-badge pending">{{ $row->order_status ?: 'Pending' }}</span></td>
                                        <td><span class="sales-report-badge {{ str_contains($deliveryStatus, 'cancel') ? 'cancelled' : (str_contains($deliveryStatus, 'deliver') ? '' : 'pending') }}">{{ $row->delivery_status ?: 'Pending' }}</span></td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->final_price, 2) }}</td>
                                        <td><span class="sales-report-date">{{ $row->possible_delivery_date ? \Carbon\Carbon::parse($row->possible_delivery_date)->format('d M Y · H:i') : '—' }}</span></td>
                                        <td><span class="sales-report-date">{{ $row->delivery_date ? \Carbon\Carbon::parse($row->delivery_date)->format('d M Y · H:i') : '—' }}</span></td>
                                        <td><span class="order-report-note" title="{{ $row->delivery_note }}">{{ $row->delivery_note ?: '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="py-8 text-center text-muted">No deliveries found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'best-selling-products')
                            <thead><tr><th>Rank</th><th>Product</th><th>Category Name</th><th>Brand Name</th><th class="text-end">Orders</th><th class="text-end">Units Sold</th><th class="text-end">Sales Total</th><th>Status</th><th>Created At</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td class="fw-bolder">#{{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}</td>
                                        <td><div class="product-report-name"><img class="product-report-image" src="{{ $row->product_image ? \App\Support\MediaStorage::url($row->product_image, 'products') : asset('uploads/blank.png') }}" alt="{{ $row->product_name }}"><div><strong>{{ $row->product_name }}</strong></div></div></td>
                                        <td>{{ $row->category_name }}</td><td>{{ $row->brand_name }}</td>
                                        <td class="text-end">{{ number_format((int) $row->order_count) }}</td>
                                        <td class="text-end fw-bold">{{ number_format((int) $row->units_sold) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->sales_total, 2) }}</td>
                                        <td><span class="product-report-status {{ (int) $row->product_status === 1 ? '' : 'inactive' }}">{{ (int) $row->product_status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                        <td><span class="product-report-meta">{{ $row->product_created_at ? \Carbon\Carbon::parse($row->product_created_at)->format('d M Y · H:i') : '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-8 text-center text-muted">No best-selling products found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'customers')
                            <thead><tr><th>Customer</th><th>Phone</th><th class="text-end">Orders</th><th class="text-end">Total Spent</th><th>Customer Type</th><th>First Order</th><th>Last Order</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    @php
                                        $customerName = $row->customer_name ?: 'Guest Customer';
                                        $customerInitials = collect(explode(' ', $customerName))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                                    @endphp
                                    <tr>
                                        <td><div class="customer-report-person"><span class="sales-report-customer-avatar">{{ $customerInitials ?: 'GC' }}</span><div><strong>{{ $customerName }}</strong><small>{{ $row->customer_email ?: 'No email' }}</small></div></div></td>
                                        <td>{{ $row->customer_phone ?: '—' }}</td>
                                        <td class="text-end">{{ number_format((int) $row->order_count) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->total_spent, 2) }}</td>
                                        <td><span class="sales-report-badge {{ (int) $row->order_count > 1 ? '' : 'pending' }}">{{ (int) $row->order_count > 1 ? 'Repeat Customer' : 'New Customer' }}</span></td>
                                        <td><span class="sales-report-date">{{ $row->first_order_at ? \Carbon\Carbon::parse($row->first_order_at)->format('d M Y · H:i') : '—' }}</span></td>
                                        <td><span class="sales-report-date">{{ $row->last_order_at ? \Carbon\Carbon::parse($row->last_order_at)->format('d M Y · H:i') : '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="py-8 text-center text-muted">No customers found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'orders')
                            <thead><tr><th>Order ID</th><th>Agent</th><th>Customer Name</th><th>Phone</th><th class="text-end">Total Price</th><th class="text-end">Final Price</th><th class="text-end">Discount</th><th class="text-end">Delivery Charge</th><th>Coupon</th><th>Order Status</th><th>Delivery Status</th><th>Order Note</th><th>Delivery Note</th><th>Possible Delivery Date</th><th>Delivery Date</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td><a href="{{ route('orders-show', $row->id) }}" class="fw-bold">{{ $row->custom_order_id ?: '#'.$row->id }}</a></td>
                                        <td>{{ trim(optional($row->assignedAgent)->first_name.' '.optional($row->assignedAgent)->last_name) ?: 'Unassigned' }}</td>
                                        <td class="fw-bold">{{ trim(optional($row->billingAddress)->first_name.' '.optional($row->billingAddress)->last_name) ?: 'Guest Customer' }}</td>
                                        <td>{{ optional($row->billingAddress)->mobile ?: $row->order_phone_number }}</td>
                                        <td class="text-end">৳{{ number_format((float) $row->total_price, 2) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->final_price, 2) }}</td>
                                        <td class="text-end">৳{{ number_format((float) $row->discount, 2) }}</td>
                                        <td class="text-end">৳{{ number_format((float) $row->delivery_charge, 2) }}</td>
                                        <td>{{ $row->coupon ?: '—' }}</td>
                                        <td><span class="order-report-status">{{ $row->order_status ?: 'Pending' }}</span></td>
                                        <td><span class="order-report-status">{{ $row->delivery_status ?: 'Pending' }}</span></td>
                                        <td><span class="order-report-note" title="{{ $row->order_note }}">{{ $row->order_note ?: '—' }}</span></td>
                                        <td><span class="order-report-note" title="{{ $row->delivery_note }}">{{ $row->delivery_note ?: '—' }}</span></td>
                                        <td>{{ $row->possible_delivery_date ? \Carbon\Carbon::parse($row->possible_delivery_date)->format('d M Y · H:i') : '—' }}</td>
                                        <td>{{ $row->delivery_date ? \Carbon\Carbon::parse($row->delivery_date)->format('d M Y · H:i') : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="15" class="py-8 text-center text-muted">No orders found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        @elseif($reportKey === 'sales')
                            <thead><tr><th><input id="sales-report-select-all" type="checkbox" class="product-report-check" aria-label="Select all sales"></th><th>Order</th><th>Date</th><th>Customer</th><th>Products</th><th>Status</th><th>Payment</th><th class="text-end">Total</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    @php
                                        $customerName = trim(optional($row->billingAddress)->first_name.' '.optional($row->billingAddress)->last_name) ?: 'Guest Customer';
                                        $customerInitials = collect(explode(' ', $customerName))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                                        $salesStatus = strtolower($row->delivery_status ?: ($row->order_status ?: 'Pending'));
                                    @endphp
                                    <tr>
                                        <td><input type="checkbox" class="product-report-check sales-report-row-check" aria-label="Select {{ $row->custom_order_id ?: 'order '.$row->id }}"></td>
                                        <td><a href="{{ route('orders-show', $row->id) }}" class="sales-report-order">{{ $row->custom_order_id ?: '#'.$row->id }}</a></td>
                                        <td><span class="sales-report-date">{{ optional($row->created_at)->format('d M Y · H:i') }}</span></td>
                                        <td><div class="sales-report-customer"><span class="sales-report-customer-avatar">{{ $customerInitials ?: 'GC' }}</span><div><strong>{{ $customerName }}</strong><small>{{ optional($row->billingAddress)->mobile ?: $row->order_phone_number }}</small></div></div></td>
                                        <td><div class="sales-report-products">
                                            @forelse($row->orderDetails as $detail)
                                                @php
                                                    $detailName = optional($detail->product)->name ?: 'Product unavailable';
                                                    $detailImage = optional($detail->product)->img_path ? \App\Support\MediaStorage::url($detail->product->img_path, 'products') : asset('uploads/blank.png');
                                                @endphp
                                                <div class="sales-report-product"><img src="{{ $detailImage }}" alt="{{ $detailName }}"><div><strong>{{ $detailName }} × {{ $detail->quantity }}</strong><small>@if($detail->product_color)Color: {{ $detail->product_color }}@endif @if($detail->product_size){{ $detail->product_color ? ' · ' : '' }}Size: {{ $detail->product_size }}@endif</small></div></div>
                                            @empty<span class="text-muted">No products</span>@endforelse
                                        </div></td>
                                        <td><span class="sales-report-badge {{ str_contains($salesStatus, 'cancel') ? 'cancelled' : (str_contains($salesStatus, 'pending') || str_contains($salesStatus, 'process') ? 'pending' : '') }}">{{ $row->delivery_status ?: ($row->order_status ?: 'Pending') }}</span></td>
                                        <td><span class="sales-report-badge {{ strtolower($row->payment_status ?: '') === 'paid' ? '' : 'pending' }}">{{ $row->payment_status ?: 'Not Paid' }}</span></td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->final_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-8 text-center text-muted">No sales found in this date range.</td></tr>
                                @endforelse
                            </tbody>
                        @else
                            <thead><tr><th><input id="product-report-select-all" type="checkbox" class="product-report-check" aria-label="Select all products"></th><th>Product</th><th>Color</th><th>Size</th><th>Category Name</th><th>Brand Name</th><th class="text-end">Orders</th><th class="text-end">Units Sold</th><th class="text-end">Sales Total</th><th>Status</th><th>Created At</th></tr></thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td><input type="checkbox" class="product-report-check product-report-row-check" aria-label="Select {{ $row->product_name }}"></td>
                                        <td>
                                            <div class="product-report-name">
                                                <img class="product-report-image" src="{{ $row->product_image ? \App\Support\MediaStorage::url($row->product_image, 'products') : asset('uploads/blank.png') }}" alt="{{ $row->product_name }}">
                                                <div><strong>{{ $row->product_name }}</strong><small>Product ID: {{ $row->product_id }}</small></div>
                                            </div>
                                        </td>
                                        <td>{{ $row->product_color ?: '—' }}</td>
                                        <td>{{ $row->product_size ?: '—' }}</td>
                                        <td>{{ $row->category_name }}</td>
                                        <td>{{ $row->brand_name }}</td>
                                        <td class="text-end">{{ number_format((int) $row->order_count) }}</td>
                                        <td class="text-end">{{ number_format((int) $row->units_sold) }}</td>
                                        <td class="text-end fw-bold">৳{{ number_format((float) $row->sales_total, 2) }}</td>
                                        <td><span class="product-report-status {{ (int) $row->product_status === 1 ? '' : 'inactive' }}">{{ (int) $row->product_status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                        <td><span class="product-report-meta">{{ $row->product_created_at ? \Carbon\Carbon::parse($row->product_created_at)->format('d M Y · H:i') : '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="11" class="py-8 text-center text-muted">No product sales found in this date range.</td></tr>
                                @endforelse
                            </tbody>
                        @endif
                    </table>
                </div>
                @if($rows->hasPages())
                    <div class="mt-5 product-report-pagination">{{ $rows->links() }}</div>
                @endif
            </div>
        </div>
    @else
    <div class="card border-0 shadow-sm mt-6">
        <div class="card-body py-10 text-center">
            <span class="svg-icon svg-icon-3x svg-icon-primary mb-4">
                <svg viewBox="0 0 24 24" fill="none"><path opacity=".3" d="M4 3h16v18H4V3Z" fill="currentColor"/><path d="M7 17h2v-5H7v5Zm4 0h2V7h-2v10Zm4 0h2v-8h-2v8Z" fill="currentColor"/></svg>
            </span>
            <h3 class="fw-bolder">{{ $report['title'] }}</h3>
            <p class="text-muted mb-0">
                Showing report period: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }}
                to {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }}.
            </p>
        </div>
    </div>
    @endif
</div>
<script>
    (function () {
        const start = document.getElementById('report-start-date');
        const end = document.getElementById('report-end-date');
        if (!start || !end) return;
        const syncRange = () => {
            end.min = start.value;
            if (end.value && end.value < start.value) end.value = start.value;
        };
        start.addEventListener('change', syncRange);
        syncRange();

        const category = document.getElementById('report-category');
        if (category && window.jQuery && jQuery.fn.select2 && !category.classList.contains('select2-hidden-accessible')) {
            jQuery(category).select2({
                placeholder: 'All Categories',
                allowClear: true,
                width: '100%'
            });
        }

        const agent = document.getElementById('report-agent');
        if (agent && window.jQuery && jQuery.fn.select2 && !agent.classList.contains('select2-hidden-accessible')) {
            jQuery(agent).select2({
                placeholder: 'All Staff',
                allowClear: true,
                width: '100%'
            });
        }

        const selectAll = document.getElementById('product-report-select-all');
        const rowChecks = Array.from(document.querySelectorAll('.product-report-row-check'));
        if (selectAll) {
            selectAll.addEventListener('change', () => rowChecks.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            }));
            rowChecks.forEach((checkbox) => checkbox.addEventListener('change', () => {
                selectAll.checked = rowChecks.length > 0 && rowChecks.every((item) => item.checked);
                selectAll.indeterminate = rowChecks.some((item) => item.checked) && !selectAll.checked;
            }));
        }

        const salesSelectAll = document.getElementById('sales-report-select-all');
        const salesRowChecks = Array.from(document.querySelectorAll('.sales-report-row-check'));
        if (salesSelectAll) {
            salesSelectAll.addEventListener('change', () => salesRowChecks.forEach((checkbox) => {
                checkbox.checked = salesSelectAll.checked;
            }));
            salesRowChecks.forEach((checkbox) => checkbox.addEventListener('change', () => {
                salesSelectAll.checked = salesRowChecks.length > 0 && salesRowChecks.every((item) => item.checked);
                salesSelectAll.indeterminate = salesRowChecks.some((item) => item.checked) && !salesSelectAll.checked;
            }));
        }
    })();
</script>
@endsection
