@extends('layouts.master')

@section('content')
@php
    $orderNumber = $order->custom_order_id ?: ($order->invoice_no ?: '#'.$order_id);
    $status = $order->delivery_status ?: ($order->order_status ?: 'Pending');
    $customerName = trim(($order->first_name ?? '').' '.($order->last_name ?? '')) ?: ($order->customer_name ?? 'Guest Customer');
    $subtotal = (float) $orderDetails->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_price);
    $shipping = (float) ($order->delivery_charge ?? $order->shipping_charge ?? 0);
    $payable = (float) ($order->final_price ?? $order->payable_amount ?? 0);
    if ($payable <= 0) $payable = max(0, $subtotal + $shipping);
    $discountAmount = max(0, $subtotal + $shipping - $payable);
    $paid = (float) ($order->pay_amount ?? 0);
    $due = max(0, $payable - $paid);
    $orderDate = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : now();
    $addressParts = array_filter([$order->shipping_address ?? null, $order->shipping_address_2 ?? null, $order->city ?? null, $order->state ?? null, $order->zip ?? null]);
    $websiteSettings = \App\Helpers\Helper::settings();
    $websiteLogoUrl = $websiteSettings && $websiteSettings->site_logo
        ? \App\Support\MediaStorage::url($websiteSettings->site_logo, 'settings', '')
        : asset('feb/img/fabrilife.svg');
@endphp

<style>
    .order-preview { --ink:#17274d; --muted:#8299bf; --line:#dce4ef; --primary:#6246ea; color:var(--ink); padding:18px 22px 38px; }
    .order-preview * { box-sizing:border-box; }
    .order-preview .page-shell { background:#fff; border:1px solid #d9e1ec; border-radius:3px; }
    .order-preview .page-heading { padding:16px 20px; font-size:25px; font-weight:500; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; gap:15px; }
    .order-preview .print-image-btn { border:1px solid var(--primary); color:var(--primary); padding:8px 14px; border-radius:4px; font-size:12px; text-decoration:none; white-space:nowrap; }
    .order-preview .page-body { padding:18px; background:#fbfcfe; }
    .order-preview .op-card { background:#fff; border:0; border-radius:3px; box-shadow:0 2px 8px rgba(20,39,75,.08); margin-bottom:20px; }
    .order-preview .hero { min-height:185px; padding:26px 22px; display:flex; align-items:center; justify-content:space-between; gap:25px; }
    .order-preview .hero-main { display:flex; align-items:center; gap:26px; min-width:0; }
    .order-preview .brand-mark { width:82px; max-height:46px; object-fit:contain; }
    .order-preview .order-title { font-size:24px; font-weight:500; margin:0 0 5px; color:var(--ink); }
    .order-preview .placed { color:var(--muted); font-size:13px; margin-bottom:17px; }
    .order-preview .pills { display:flex; flex-wrap:wrap; gap:10px; }
    .order-preview .pill { padding:7px 15px; border-radius:4px; background:#f0f3f8; font-size:11px; font-weight:600; color:#273249; }
    .order-preview .pill.primary { color:#fff; background:var(--primary); }
    .order-preview .amount-box { text-align:right; flex:0 0 145px; }
    .order-preview .amount-label,.order-preview .field-label { color:#7c95bd; text-transform:uppercase; font-size:10px; letter-spacing:.45px; font-weight:600; }
    .order-preview .hero-amount { font-size:20px; margin:3px 0 12px; }
    .order-preview .qr { display:block; width:104px; height:104px; margin-left:auto; padding:5px; background:#fff; object-fit:contain; }
    .order-preview .controls { display:grid; grid-template-columns:1fr 1fr 1fr; gap:26px; margin:2px 0 24px; }
    .order-preview .field-label { display:block; margin-bottom:8px; }
    .order-preview .form-control { height:38px; border:1px solid #d5deeb; border-radius:0; color:#4b5569; background-color:#fff; }
    .order-preview .field-help { color:var(--muted); font-size:11px; margin-top:7px; }
    .order-preview .money-input { display:flex; border:1px solid #d5deeb; height:38px; background:#fff; }
    .order-preview .money-input span { width:38px; display:grid; place-items:center; border-right:1px solid #e4e9f1; color:#6078a5; }
    .order-preview .money-input input { border:0; width:100%; padding:0 12px; color:#50658d; font-weight:600; outline:0; }
    .order-preview .special { padding-top:25px; font-weight:600; font-size:13px; }
    .order-preview .switch-dot { display:inline-block; width:25px; height:14px; border:1px solid #9eb2d3; border-radius:10px; vertical-align:-2px; margin-right:7px; position:relative; }
    .order-preview .switch-dot:after { content:""; width:10px; height:10px; background:#a9bfdc; border-radius:50%; position:absolute; left:1px; top:1px; }
    .order-preview .save-row { grid-column:1/-1; display:flex; justify-content:flex-end; margin-top:-9px; }
    .order-preview .save-btn,.order-preview .edit-btn { border:1px solid var(--primary); color:var(--primary); background:#fff; padding:7px 13px; border-radius:4px; font-size:12px; cursor:pointer; }
    .order-preview .save-btn { background:var(--primary); color:#fff; padding:8px 20px; }
    .order-preview .content-grid { display:grid; grid-template-columns:minmax(0,2.05fr) minmax(300px,1fr); gap:26px; align-items:start; }
    .order-preview .card-head { height:48px; padding:0 18px; border-bottom:1px solid var(--line); display:flex; align-items:center; justify-content:space-between; font-size:13px; font-weight:500; }
    .order-preview .customer-body { display:grid; grid-template-columns:1.25fr .85fr; gap:30px; padding:18px; min-height:210px; }
    .order-preview .customer-name { font-size:17px; font-weight:500; margin-bottom:4px; }
    .order-preview .customer-phone { color:var(--muted); font-size:13px; margin-bottom:8px; }
    .order-preview .customer-address { font-size:13px; line-height:1.8; max-width:460px; }
    .order-preview .detail-row,.order-preview .summary-row { display:flex; justify-content:space-between; gap:16px; border-top:1px solid #e3e8f0; padding:8px 5px; font-size:12px; }
    .order-preview .detail-row:first-child { border-top:0; }
    .order-preview .detail-row label,.order-preview .summary-row label { color:#7c91b3; text-transform:uppercase; letter-spacing:.45px; font-size:9px; font-weight:600; }
    .order-preview .detail-row span { text-align:right; color:#7189b0; }
    .order-preview .detail-row .accent { color:var(--primary); font-weight:700; }
    .order-preview .table-responsive { overflow-x:auto; }
    .order-preview .items-table { width:100%; border-collapse:collapse; min-width:720px; }
    .order-preview .items-table th { background:#eef2f8; color:#52698f; text-transform:uppercase; letter-spacing:.4px; font-size:9px; padding:13px 11px; text-align:left; }
    .order-preview .items-table td { padding:12px 11px; border-bottom:1px solid var(--line); font-size:12px; vertical-align:middle; }
    .order-preview .items-table tbody tr:last-child td { border-bottom:0; }
    .order-preview .product-cell { display:flex; align-items:center; gap:14px; min-width:300px; font-weight:600; }
    .order-preview .product-cell img { width:53px; height:53px; border-radius:10px; object-fit:cover; background:#f2f3f5; }
    .order-preview .qty { width:72px; height:35px; border:1px solid #d5deeb; border-radius:3px; text-align:center; color:#50658d; }
    .order-preview .status-badge { background:var(--primary); color:#fff; padding:3px 9px; border-radius:12px; font-size:9px; font-weight:700; }
    .order-preview .summary-body { padding:19px; }
    .order-preview .summary-row { padding:11px 0; }
    .order-preview .summary-row strong { font-weight:600; }
    .order-preview .green { color:#18b883; }
    .order-preview .due-box { margin-top:13px; background:#ffbd09; min-height:65px; border-radius:11px; padding:13px 18px; color:#fff; display:flex; justify-content:space-between; align-items:center; }
    .order-preview .due-box small { display:block; text-transform:uppercase; font-size:9px; font-weight:700; margin-bottom:3px; }
    .order-preview .due-box b { font-size:15px; }
    .order-preview .pending { color:#253450; background:#fff; padding:3px 7px; border-radius:3px; font-size:9px; }
    .order-preview .empty-note { padding:22px 18px; color:var(--muted); font-size:13px; }
    .order-preview .insight { padding:17px 18px; }
    .order-preview .insight-total { color:var(--muted); margin-bottom:20px; font-size:13px; }
    .order-preview .metric { margin-bottom:20px; font-size:12px; }
    .order-preview .metric-line { display:flex; justify-content:space-between; margin-bottom:8px; }
    .order-preview .bar { height:5px; border-radius:5px; background:#f3f5f8; overflow:hidden; }
    .order-preview .bar i { display:block; width:100%; height:100%; background:#ffb900; }
    .product-select-option { display:flex; align-items:center; gap:10px; padding:3px 0; }
    .product-select-option img { width:38px; height:38px; flex:0 0 38px; border-radius:6px; object-fit:cover; background:#f1f3f7; }
    .product-select-option span { display:block; line-height:1.35; }
    .product-select-option small { display:block; color:#8291aa; margin-top:2px; }
    #addProductModal .select2-container { width:100% !important; }
    #addProductModal .select2-selection--single { min-height:44px; padding-top:6px; }
    .order-preview .courier-panel { padding:24px; text-align:center; }
    .order-preview .courier-panel h3 { margin:0 0 8px; font-size:22px; font-weight:500; }
    .order-preview .courier-panel p { margin:0 0 14px; color:#52617e; font-size:14px; }
    .order-preview .courier-check { border:0; border-radius:4px; padding:9px 16px; background:#477bd3; color:#fff; }
    .order-preview .courier-meta { display:flex; justify-content:center; gap:35px; margin-top:16px; font-size:12px; }
    @media(max-width:1100px){ .order-preview .content-grid{grid-template-columns:1fr}.order-preview .controls{grid-template-columns:1fr 1fr}.order-preview .special{padding-top:0}.order-preview .save-row{grid-column:1/-1} }
    @media(max-width:700px){ .order-preview{padding:10px}.order-preview .page-body{padding:10px}.order-preview .hero{align-items:flex-start}.order-preview .hero-main{gap:12px}.order-preview .brand-mark{display:none}.order-preview .order-title{font-size:18px;word-break:break-all}.order-preview .amount-box{flex-basis:105px}.order-preview .qr{width:72px;height:72px}.order-preview .controls,.order-preview .customer-body{grid-template-columns:1fr}.order-preview .customer-body{gap:15px}.order-preview .page-heading{font-size:21px}.order-preview .content-grid{gap:14px} }
    @media(max-width:700px){body.aside-enabled #kt_wrapper,#kt_wrapper{margin-left:0!important;width:100%!important;max-width:100%!important}#kt_content{width:100%!important;max-width:100%!important}.order-preview{width:100%;max-width:100%;padding:8px}.order-preview .page-shell,.order-preview .page-body,.order-preview .hero,.order-preview .order-settings-form{width:100%!important;max-width:none!important;float:none!important}.order-preview .page-body{padding:8px}.order-preview .page-heading{padding:12px;font-size:18px}.order-preview .print-image-btn{padding:7px 9px;font-size:10px}.order-preview .hero{min-height:0;padding:18px 14px}.order-preview .hero-main{gap:8px}.order-preview .order-title{font-size:17px;word-break:break-word}.order-preview .placed{font-size:11px;margin-bottom:12px}.order-preview .pills{gap:6px}.order-preview .pill{padding:5px 8px;font-size:9px}.order-preview .amount-box{flex:0 0 82px}.order-preview .hero-amount{font-size:15px}.order-preview .qr{width:66px;height:66px}.order-preview .controls{display:grid;grid-template-columns:1fr;gap:15px;width:100%}.order-preview .special{padding-top:0}.order-preview .save-row{margin-top:0}.order-preview .content-grid{grid-template-columns:minmax(0,1fr);gap:10px}.order-preview .customer-body{grid-template-columns:1fr;gap:15px;padding:14px}.order-preview .card-head{padding:0 12px}.order-preview .table-responsive{overflow:visible}.order-preview .items-table{display:block;min-width:0}.order-preview .items-table thead{display:none}.order-preview .items-table tbody{display:block;padding:8px}.order-preview .items-table tr[data-item-id]{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:12px 0;border-bottom:1px solid var(--line)}.order-preview .items-table td{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:0;border:0;font-size:11px}.order-preview .items-table td:before{content:attr(data-label);color:#7c91b3;text-transform:uppercase;font-size:8px;font-weight:700}.order-preview .items-table td:nth-child(1){display:none}.order-preview .items-table td:nth-child(2){grid-column:1/-1;display:block}.order-preview .items-table td:nth-child(2):before{display:none}.order-preview .product-cell{min-width:0;gap:10px}.order-preview .product-cell img{width:48px;height:48px}.order-preview .product-cell span{word-break:break-word}.order-preview .qty{width:58px;height:32px}.order-preview .summary-body{padding:14px}.order-preview .courier-panel{padding:18px 12px}.order-preview .courier-panel h3{font-size:17px}.order-preview .courier-meta{flex-direction:column;gap:6px}.modal-dialog{margin:8px}}
    @media(max-width:700px){body.aside-enabled #kt_wrapper,#kt_wrapper{padding-left:0!important;min-width:0!important}#kt_content,.order-preview{min-width:0!important}}
</style>

<div class="order-preview">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="page-shell">
        <div class="page-heading"><span>Order Details</span><a class="print-image-btn" href="{{ route('orders-invoice', $order_id) }}" target="_blank" rel="noopener">▣ Print As Image</a></div>
        <div class="page-body">
            <div class="op-card hero">
                <div class="hero-main">
                    <img class="brand-mark" src="{{ $websiteLogoUrl }}" alt="Website logo">
                    <div>
                        <h2 class="order-title">Order #{{ $orderNumber }}</h2>
                        <div class="placed">Placed on {{ $orderDate->format('d M Y, h:i A') }}</div>
                        <div class="pills">
                            <span class="pill primary">{{ $status }}</span>
                            <span class="pill">▣ Standard Delivery</span>
                            <span class="pill">▣ {{ $order->payment_type ?: 'Cash On Delivery' }}</span>
                        </div>
                    </div>
                </div>
                <div class="amount-box">
                    <div class="amount-label">Total Amount</div>
                    <div class="hero-amount">৳{{ number_format($payable, 2) }}</div>
                    <img
                        class="qr"
                        src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&amp;margin=0&amp;data={{ rawurlencode((string) $order->custom_order_id) }}"
                        alt="QR code for order {{ $order->custom_order_id }}"
                        title="Order: {{ $order->custom_order_id }}"
                        width="104"
                        height="104"
                    >
                </div>
            </div>

            <form class="order-settings-form" action="{{ route('orders-update', $order_id) }}" method="POST">
                @csrf
                <div class="controls">
                    <div>
                        <label class="field-label">Payment Status</label>
                        <select name="payment_status" class="form-control">
                            @foreach(['NOT PAID','PAID','PARTIAL PAID'] as $value)
                                <option value="{{ $value }}" @selected($order->payment_status === $value)>{{ ucwords(strtolower($value)) }}</option>
                            @endforeach
                        </select>
                        <div class="field-help">Current: {{ $order->payment_status ?: 'Not Paid' }}</div>
                    </div>
                    <div>
                        <label class="field-label">Order Status</label>
                        <select name="order_status" class="form-control">
                            @foreach(['Pending','PROCESSING','Confirmed','Cancel'] as $value)
                                <option value="{{ $value }}" @selected($order->order_status === $value)>{{ ucfirst(strtolower($value)) }}</option>
                            @endforeach
                        </select>
                        <div class="field-help">Update the order's current processing state.</div>
                    </div>
                    <div>
                        <label class="field-label">Special Order</label>
                        <div class="special"><span class="switch-dot"></span> Mark as special order</div>
                    </div>
                    <div>
                        <label class="field-label">Payment Method</label>
                        <select name="payment_type" class="form-control">
                            @foreach(['Cash on Delivery','Online Payment','Card','Bkash','Bank Transfer'] as $value)
                                <option value="{{ $value }}" @selected($order->payment_type === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Advance Paid</label>
                        <div class="money-input"><span>৳</span><input type="number" step="0.01" name="pay_amount" value="{{ $paid }}"></div>
                    </div>
                    <div>
                        <label class="field-label">Delivery Status</label>
                        <select name="delivery_status" class="form-control">
                            @foreach(['Pending','Ready For Shipment','Cancel By User','Cancel By Admin','Delivery Done'] as $value)
                                <option value="{{ $value }}" @selected($order->delivery_status === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="delivery_note" value="{{ $order->delivery_note }}">
                    <input type="hidden" name="cancel_reason" value="{{ $order->cancel_reason }}">
                    <input type="hidden" name="delivery_date" value="{{ $order->delivery_date }}">
                    <input type="hidden" name="cancel_date" value="{{ $order->cancel_date }}">
                    <div class="save-row"><button class="save-btn" type="submit">Save Changes</button></div>
                </div>
            </form>

            <div class="content-grid">
                <main>
                    <section class="op-card">
                        <div class="card-head"><span>Customer &amp; Delivery</span><button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#customerDeliveryModal">✎ Edit</button></div>
                        <div class="customer-body">
                            <div>
                                <div class="customer-name">{{ $customerName }}</div>
                                <div class="customer-phone">{{ $order->mobile ?: $order->order_phone_number }}</div>
                                <div class="customer-address">Address - {{ implode(', ', $addressParts) ?: 'No delivery address provided' }}</div>
                            </div>
                            <div>
                                <div class="detail-row"><label>Order #</label><span class="accent">{{ $orderNumber }}</span></div>
                                <div class="detail-row"><label>Order Date</label><span>{{ $orderDate->format('d M Y, h:i A') }}</span></div>
                                <div class="detail-row"><label>Payment Method</label><span>{{ $order->payment_type ?: 'Cash On Delivery' }}</span></div>
                                <div class="detail-row"><label>Order Status</label><span>{{ $order->order_status ?: 'Processing' }}</span></div>
                                <div class="detail-row"><label>Delivery Provider</label><span>Standard Delivery</span></div>
                            </div>
                        </div>
                    </section>

                    <section class="op-card">
                        <div class="card-head"><span>Order Items <small class="text-muted ms-2" id="itemCount">{{ $orderDetails->count() }} product(s)</small></span><button type="button" class="save-btn" data-bs-toggle="modal" data-bs-target="#addProductModal">＋ Add product</button></div>
                        <div class="table-responsive">
                            <table class="items-table">
                                <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Attributes</th><th>Price</th><th>Total</th><th>Action</th></tr></thead>
                                <tbody id="orderItemRows">@include('orders.partials.item-rows', ['orderDetails' => $orderDetails])</tbody>
                            </table>
                        </div>
                    </section>
                </main>

                <aside>
                    <section class="op-card">
                        <div class="card-head"><span>Payment Summary</span><span class="status-badge">{{ $status }}</span></div>
                        <div class="summary-body">
                            <div class="summary-row"><label>Sub Total</label><span id="summarySubtotal">৳{{ number_format($subtotal, 2) }}</span></div>
                            <div class="summary-row"><label>Shipping</label><span>৳{{ number_format($shipping, 2) }}</span></div>
                            <div class="summary-row"><label>Discount</label><span class="green" id="summaryDiscount">-৳{{ number_format($discountAmount, 2) }}</span></div>
                            <div class="summary-row"><label>Total Payable</label><strong id="summaryPayable">৳{{ number_format($payable, 2) }}</strong></div>
                            <div class="summary-row"><label>Paid</label><span class="green">-৳{{ number_format($paid, 2) }}</span></div>
                            <div class="due-box"><div><small>Amount Due</small><b id="summaryDue">৳{{ number_format($due, 2) }}</b></div><span class="pending" id="summaryDueStatus">{{ $due > 0 ? 'Pending' : 'Paid' }}</span></div>
                        </div>
                    </section>

                    <section class="op-card">
                        <div class="card-head"><span>Order Notes</span></div>
                        <div class="empty-note">{{ $order->order_note ?: $order->delivery_note ?: 'No notes have been added to this order yet.' }}</div>
                    </section>

                    <section class="op-card">
                        <div class="card-head"><span>Customer Insight</span></div>
                        <div class="insight">
                            <div class="insight-total">Current order overview</div>
                            <div class="metric"><div class="metric-line"><span>Order Items ({{ $orderDetails->count() }})</span><b>100%</b></div><div class="bar"><i></i></div></div>
                            <div class="metric"><div class="metric-line"><span>Payment Status</span><b>{{ $due > 0 ? 'Pending' : 'Paid' }}</b></div><div class="bar"><i style="width:{{ $payable > 0 ? min(100, ($paid/$payable)*100) : 0 }}%;background:#42c49a"></i></div></div>
                        </div>
                    </section>
                </aside>
            </div>
            <section class="op-card courier-panel" id="steadfastCourierStatus" data-order-id="{{ $orderNumber }}">
                <h3 id="steadfastHeading">{{ $order->steadfast_consignment_id ? 'This order is uploaded in SteadFast courier.' : 'This order is not uploaded in SteadFast courier yet.' }}</h3>
                <p>Send the order or see the latest delivery status using the buttons below.</p>
                <div class="d-flex justify-content-center flex-wrap gap-2"><button type="button" class="courier-check" id="sendToSteadfast">Send to SteadFast</button><button type="button" class="courier-check" id="checkCourierStatus">Check Status</button></div>
                <div class="courier-meta"><span>Shipping Status: <strong id="shippingStatus">{{ $order->steadfast_status ?: 'Not submitted' }}</strong></span><span>Consignment ID: <strong id="steadfastConsignment">{{ $order->steadfast_consignment_id ?: '—' }}</strong></span></div>
            </section>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addProductForm">
                <div class="modal-header"><h5 class="modal-title">Add Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div id="itemFormError" class="alert alert-danger d-none"></div>
                    <div class="mb-5">
                        <label class="form-label required">Product</label>
                        <select name="product_id" id="orderProductSelect" class="form-select" required>
                            <option value="">Select product</option>
                            @foreach($products as $product)
                                @php
                                    $productPrice = (float) ($product->discount_price > 0 ? $product->discount_price : $product->product_value);
                                    $productImage = $product->img_path
                                        ? \App\Support\MediaStorage::url($product->img_path, 'products')
                                        : url('/uploads/noimage.jpg');
                                @endphp
                                <option value="{{ $product->id }}" data-image="{{ $productImage }}" data-price="{{ number_format($productPrice, 2) }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="form-label required">Quantity</label><input type="number" name="quantity" class="form-control" min="1" max="9999" value="1" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add Product</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="customerDeliveryModal" tabindex="-1" aria-labelledby="customerDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('orders-customer-delivery-update', $order_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="customerDeliveryModalLabel">Edit Customer &amp; Delivery</h5>
                        <div class="text-muted fs-7 mt-1">Order #{{ $orderNumber }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-7">
                    @if($errors->any())
                        <div class="alert alert-danger mb-5">
                            <ul class="mb-0 ps-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label required">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $order->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $order->last_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Phone Number</label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $order->mobile ?: $order->order_phone_number) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $order->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $order->company_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $order->city) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State / Area</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $order->state) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="zip" class="form-control" value="{{ old('zip', $order->zip) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Delivery Address</label>
                            <textarea name="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="shipping_address_2" class="form-control" value="{{ old('shipping_address_2', $order->shipping_address_2) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Order Note</label>
                            <textarea name="order_note" class="form-control" rows="4" maxlength="2000" placeholder="Add a note for this order">{{ old('order_note', $order->order_note) }}</textarea>
                            <div class="form-text">This note will appear in the Order Notes section and invoice.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('endScript')
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = @json(csrf_token());
    const addUrl = @json(route('orders-items-add', $order_id));
    const itemBaseUrl = @json(url('/orders/'.$order_id.'/items'));
    const rows = document.getElementById('orderItemRows');
    const addForm = document.getElementById('addProductForm');
    const money = value => '৳' + Number(value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['Accept'] = 'application/json';

    if (window.jQuery && jQuery.fn.select2) {
        const formatProduct = function (option) {
            if (!option.id) return option.text;
            const element = option.element;
            const image = element.dataset.image;
            const price = element.dataset.price;
            return jQuery('<div class="product-select-option"><img><span></span></div>')
                .find('img').attr('src', image).attr('alt', '').end()
                .find('span').text(option.text).append(jQuery('<small>').text('৳' + price)).end();
        };

        jQuery('#orderProductSelect').select2({
            dropdownParent: jQuery('#addProductModal'),
            placeholder: 'Search and select product',
            allowClear: true,
            width: '100%',
            templateResult: formatProduct,
            templateSelection: formatProduct
        });
    }

    function showMessage(message, icon = 'success') {
        if (window.Swal) Swal.fire({icon, title: icon === 'success' ? 'Success' : 'Error', text: message, timer: 1800, showConfirmButton: false});
        else alert(message);
    }

    function errorMessage(error) {
        const errors = error.response?.data?.errors;
        return errors ? Object.values(errors).flat()[0] : (error.response?.data?.message || 'Something went wrong.');
    }

    function applyState(data) {
        rows.innerHTML = data.rows_html;
        document.getElementById('itemCount').textContent = data.count + ' product(s)';
        document.getElementById('summarySubtotal').textContent = money(data.totals.subtotal);
        document.getElementById('summaryDiscount').textContent = '-' + money(data.totals.discount);
        document.getElementById('summaryPayable').textContent = money(data.totals.payable);
        document.getElementById('summaryDue').textContent = money(data.totals.due);
        document.getElementById('summaryDueStatus').textContent = Number(data.totals.due) > 0 ? 'Pending' : 'Paid';
    }

    async function steadfastRequest(button, url) {
        const original = button.textContent;
        button.disabled = true;
        button.textContent = 'Please wait…';
        try {
            const response = await axios.post(url);
            const payload = response.data.data?.consignment || response.data.data || {};
            document.getElementById('shippingStatus').textContent = response.data.status || payload.status || 'Submitted';
            if (payload.consignment_id) document.getElementById('steadfastConsignment').textContent = payload.consignment_id;
            if (button.id === 'sendToSteadfast') document.getElementById('steadfastHeading').textContent = 'This order is uploaded in SteadFast courier.';
            showMessage(response.data.message);
        } catch (error) {
            showMessage(errorMessage(error), 'error');
        } finally {
            button.disabled = false;
            button.textContent = original;
        }
    }

    document.getElementById('sendToSteadfast').addEventListener('click', function () {
        if (confirm('Send this order to SteadFast courier?')) steadfastRequest(this, @json(route('orders-steadfast-place', $order_id)));
    });
    document.getElementById('checkCourierStatus').addEventListener('click', function () {
        steadfastRequest(this, @json(route('orders-steadfast-status', $order_id)));
    });

    addForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const submit = addForm.querySelector('[type="submit"]');
        submit.disabled = true;
        try {
            const response = await axios.post(addUrl, Object.fromEntries(new FormData(addForm)));
            applyState(response.data);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('addProductModal')).hide();
            addForm.reset();
            if (window.jQuery && jQuery.fn.select2) jQuery('#orderProductSelect').val(null).trigger('change');
            addForm.querySelector('[name="quantity"]').value = 1;
            showMessage(response.data.message);
        } catch (error) {
            const box = document.getElementById('itemFormError');
            box.textContent = errorMessage(error);
            box.classList.remove('d-none');
        } finally { submit.disabled = false; }
    });

    rows.addEventListener('click', async function (event) {
        const row = event.target.closest('tr[data-item-id]');
        if (!row) return;
        const detailId = row.dataset.itemId;

        if (event.target.closest('.update-item')) {
            const button = event.target.closest('.update-item');
            button.disabled = true;
            try {
                const response = await axios.patch(itemBaseUrl + '/' + detailId, {quantity: row.querySelector('.item-quantity').value});
                applyState(response.data); showMessage(response.data.message);
            } catch (error) { showMessage(errorMessage(error), 'error'); }
            finally { if (button.isConnected) button.disabled = false; }
        }

        if (event.target.closest('.delete-item')) {
            if (!confirm('Remove this product from the order?')) return;
            const button = event.target.closest('.delete-item');
            button.disabled = true;
            try {
                const response = await axios.delete(itemBaseUrl + '/' + detailId);
                applyState(response.data); showMessage(response.data.message);
            } catch (error) { showMessage(errorMessage(error), 'error'); button.disabled = false; }
        }
    });
});
</script>
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalElement = document.getElementById('customerDeliveryModal');
        if (modalElement && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
    });
</script>
@endif
@endsection
