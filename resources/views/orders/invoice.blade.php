<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->custom_order_id }}</title>
    <style>
        *{box-sizing:border-box} body{margin:0;background:#eef1f6;color:#17274d;font-family:Arial,sans-serif;font-size:13px}.actions{position:sticky;top:0;z-index:5;padding:12px;text-align:center;background:#e7e9ef;border-bottom:1px solid #d4d8e1}.actions button{border:0;border-radius:4px;padding:10px 18px;margin:0 5px;cursor:pointer;font-weight:600}.print{background:#6246ea;color:#fff}.download{background:#fff;color:#6246ea;border:1px solid #6246ea!important}.invoice{width:900px;max-width:calc(100% - 30px);margin:24px auto;background:#fff;padding:38px 42px;box-shadow:0 3px 16px rgba(31,45,72,.12)}.top{display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:25px;border-bottom:2px solid #25365a}.logo{width:125px;max-height:65px;object-fit:contain}.invoice-title{text-align:right}.invoice-title h1{font-size:34px;letter-spacing:3px;margin:0 0 8px;color:#25365a}.invoice-title div{line-height:1.7;color:#667796}.meta{display:grid;grid-template-columns:1fr 1fr;gap:50px;padding:25px 0}.label{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#8798b5;font-weight:700;margin-bottom:8px}.customer h2{font-size:18px;margin:0 0 7px}.customer div{line-height:1.7}.order-meta div{display:flex;justify-content:space-between;border-bottom:1px solid #e3e8ef;padding:7px 0}.order-meta span:first-child{color:#7183a2}.items{width:100%;border-collapse:collapse;margin-top:7px}.items th{padding:12px 9px;background:#25365a;color:#fff;text-align:left;text-transform:uppercase;font-size:10px;letter-spacing:.5px}.items td{padding:12px 9px;border-bottom:1px solid #dfe5ed;vertical-align:middle}.product{display:flex;align-items:center;gap:11px;font-weight:600}.product img{width:48px;height:48px;border-radius:6px;object-fit:cover;background:#f1f3f6}.number{text-align:right;white-space:nowrap}.bottom{display:grid;grid-template-columns:1fr 310px;gap:45px;margin-top:28px}.note{color:#72819b;line-height:1.7}.totals div{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e2e7ee}.totals .grand{background:#25365a;color:#fff;border:0;margin-top:8px;padding:13px 12px;font-size:16px;font-weight:700}.footer{text-align:center;color:#8592a8;border-top:1px solid #e1e6ed;margin-top:35px;padding-top:18px;font-size:11px}.status{display:inline-block;background:#6246ea;color:#fff;padding:4px 9px;border-radius:10px;font-size:10px}.capture-mode{box-shadow:none;margin:0;width:900px;max-width:none}@media(max-width:700px){.invoice{padding:25px 20px}.meta,.bottom{grid-template-columns:1fr;gap:20px}.items{min-width:700px}.table-wrap{overflow:auto}.invoice-title h1{font-size:25px}}@media print{body{background:#fff}.actions{display:none!important}.invoice{box-shadow:none;width:100%;max-width:none;margin:0;padding:20px}.items th{-webkit-print-color-adjust:exact;print-color-adjust:exact}.totals .grand,.status{-webkit-print-color-adjust:exact;print-color-adjust:exact}.footer{page-break-inside:avoid}}
        .invoice{width:794px;min-height:1123px;position:relative;padding-bottom:82px}.top{min-height:95px}.bottom{display:flex!important;align-items:flex-start}.bottom .note{flex:1;min-width:0}.bottom .totals{flex:0 0 310px;margin-left:auto}.footer{position:absolute;left:42px;right:42px;bottom:32px;margin-top:0}.capture-mode{width:794px!important;height:1123px!important;min-height:1123px!important;max-width:none!important;margin:0!important;box-shadow:none!important;overflow:hidden}@page{size:A4 portrait;margin:10mm}@media print{.invoice{width:190mm;min-height:277mm;margin:0 auto!important}.footer{left:20px;right:20px;bottom:15px}}
    </style>
</head>
@php
    $orderNumber = $order->custom_order_id ?: '#'.$order->lukaku;
    $date = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : now();
    $name = trim(($order->first_name ?? '').' '.($order->last_name ?? '')) ?: 'Guest Customer';
    $address = collect([$order->shipping_address, $order->shipping_address_2, $order->city, $order->state, $order->zip])->filter()->implode(', ');
    $subtotal = (float)$orderDetails->sum(fn($item)=>(float)$item->quantity*(float)$item->unit_price);
    $shipping = (float)($order->delivery_charge ?? 0);
    $payable = (float)($order->final_price ?? ($subtotal+$shipping));
    $discount = max(0,$subtotal+$shipping-$payable);
    $paid = (float)($order->pay_amount ?? 0);
    $due = max(0,$payable-$paid);
    $websiteSettings = \App\Helpers\Helper::settings();
    $websiteLogoUrl = $websiteSettings && $websiteSettings->site_logo
        ? \App\Support\MediaStorage::url($websiteSettings->site_logo, 'settings', '')
        : asset('feb/img/fabrilife.svg');
@endphp
<body>
    <div class="actions"><button class="print" onclick="window.print()">Print Invoice</button><button class="download" id="downloadInvoice">Download PNG</button></div>
    <article class="invoice" id="invoiceCapture">
        <header class="top"><img class="logo" src="{{ $websiteLogoUrl }}" alt="Website logo"><div class="invoice-title"><h1>INVOICE</h1><div><strong>#{{ $orderNumber }}</strong><br>{{ $date->format('d M Y, h:i A') }}</div></div></header>
        <section class="meta"><div class="customer"><div class="label">Bill &amp; Ship To</div><h2>{{ $name }}</h2><div>{{ $order->mobile ?: $order->order_phone_number }}@if($order->email)<br>{{ $order->email }}@endif<br>{{ $address ?: 'No delivery address provided' }}</div></div><div class="order-meta"><div><span>Order Status</span><span class="status">{{ $order->order_status ?: 'Pending' }}</span></div><div><span>Payment Method</span><strong>{{ $order->payment_type ?: 'Cash on Delivery' }}</strong></div><div><span>Payment Status</span><strong>{{ $order->payment_status ?: 'Not Paid' }}</strong></div><div><span>Delivery Method</span><strong>{{ $order->shipping_method ?: 'Standard Delivery' }}</strong></div></div></section>
        <div class="table-wrap"><table class="items"><thead><tr><th>#</th><th>Product</th><th>Qty</th><th class="number">Unit Price</th><th class="number">Total</th></tr></thead><tbody>@foreach($orderDetails as $key=>$detail)<tr><td>{{ $key+1 }}</td><td><div class="product"><img src="{{ $detail->product ? \App\Support\MediaStorage::url($detail->product->img_path,'products') : url('/uploads/noimage.jpg') }}" alt=""><span>{{ $detail->product->name ?? 'Product unavailable' }}</span></div></td><td>{{ $detail->quantity }}</td><td class="number">৳{{ number_format((float)$detail->unit_price,2) }}</td><td class="number">৳{{ number_format((float)$detail->quantity*(float)$detail->unit_price,2) }}</td></tr>@endforeach</tbody></table></div>
        <section class="bottom"><div class="note"><div class="label">Order Note</div>{{ $order->order_note ?: 'Thank you for your order. Please keep this invoice for your records.' }}</div><div class="totals"><div><span>Subtotal</span><span>৳{{ number_format($subtotal,2) }}</span></div><div><span>Shipping</span><span>৳{{ number_format($shipping,2) }}</span></div><div><span>Discount</span><span>-৳{{ number_format($discount,2) }}</span></div><div><span>Paid</span><span>-৳{{ number_format($paid,2) }}</span></div><div class="grand"><span>Amount Due</span><span>৳{{ number_format($due,2) }}</span></div></div></section>
        <footer class="footer">This is a computer-generated invoice for order {{ $orderNumber }}.</footer>
    </article>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.getElementById('downloadInvoice').addEventListener('click', async function(){
            const invoice=document.getElementById('invoiceCapture');
            this.disabled=true;
            invoice.classList.add('capture-mode');
            try{
                const source=await html2canvas(invoice,{scale:2,useCORS:true,backgroundColor:'#ffffff'});
                const a4=document.createElement('canvas');
                a4.width=1588;
                a4.height=2246;
                const context=a4.getContext('2d');
                context.fillStyle='#ffffff';
                context.fillRect(0,0,a4.width,a4.height);
                const margin=0;
                const availableWidth=a4.width-(margin*2);
                const availableHeight=a4.height-(margin*2);
                const ratio=Math.min(availableWidth/source.width,availableHeight/source.height);
                const width=source.width*ratio;
                const height=source.height*ratio;
                context.drawImage(source,(a4.width-width)/2,margin,width,height);
                const link=document.createElement('a');
                link.download='invoice-{{ preg_replace('/[^A-Za-z0-9_-]/','-', $orderNumber) }}-A4.png';
                link.href=a4.toDataURL('image/png',1);
                link.click();
            }finally{
                invoice.classList.remove('capture-mode');
                this.disabled=false;
            }
        });
        window.addEventListener('load',function(){setTimeout(function(){window.print()},500)});
    </script>
</body>
</html>
