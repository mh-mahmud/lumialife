@extends('front.html.master')
@section('content')

<div class="free">
   <section class="breadcrumb__area pt-60 pb-60 tp-breadcrumb__bg" data-background="{{url('/')}}/assets/theme/assets/img/banner/breadcrumb-01.jpg">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-7 col-lg-12 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <div class="tp-breadcrumb__link mb-10">
                     <span class="breadcrumb-item-active"><a href="{{ route('index') }}">Home</a></span>
                     <span>Thank You</span>
                  </div>
                  <h2 class="tp-breadcrumb__title">Thank You</h2>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="checkout-area pt-80 pb-80 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
      <div class="container">
         @if (session('success'))
         <div class="alert alert-success">
            {{ session('success') }}
         </div>
         @endif

         <div class="row">
            <div class="col-lg-5 col-md-12">
               <div class="your-order mb-30">
                  <h3>Order Summary</h3>
                  <div class="your-order-table table-responsive">
                     <table>
                        <tbody>
                           <tr>
                              <th>Order No</th>
                              <td>{{ $order->custom_order_id }}</td>
                           </tr>
                           <tr>
                              <th>Name</th>
                              <td>{{ $order->first_name }}</td>
                           </tr>
                           <tr>
                              <th>Phone</th>
                              <td>{{ $order->order_phone_number }}</td>
                           </tr>
                           <tr>
                              <th>Address</th>
                              <td>{{ $order->shipping_address }}</td>
                           </tr>
                           <tr>
                              <th>Payment Type</th>
                              <td>{{ $order->payment_type }}</td>
                           </tr>
                           <tr>
                              <th>Payment Status</th>
                              <td>{{ $order->payment_status }}</td>
                           </tr>
                           <tr>
                              <th>Order Status</th>
                              <td>{{ $order->order_status }}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>

            <div class="col-lg-7 col-md-12">
               <div class="your-order mb-30">
                  <h3>Product Details</h3>
                  <div class="your-order-table table-responsive">
                     <table>
                        <thead>
                           <tr>
                              <th class="product-name"><b>Product</b></th>
                              <th class="product-total"><b>Total</b></th>
                           </tr>
                        </thead>
                        <tbody>
                           @php $subTotal = 0; @endphp
                           @foreach ($orderDetails as $detail)
                           @php
                              $lineTotal = $detail->total ?: ($detail->quantity * $detail->unit_price);
                              $subTotal += $lineTotal;
                           @endphp
                           <tr class="cart_item">
                              <td class="product-name">
                                 <div class="d-flex align-items-center gap-3">
                                    @if ($detail->product && $detail->product->img_path)
                                    <img src="{{ \App\Support\MediaStorage::url($detail->product->img_path, 'products') }}" alt="{{ $detail->product->name }}" style="width:70px;height:70px;object-fit:cover;">
                                    @endif
                                    <span>
                                       {{ $detail->product ? $detail->product->name : 'Product unavailable' }}
                                       <strong class="product-quantity"> x {{ $detail->quantity }}</strong>
                                    </span>
                                 </div>
                              </td>
                              <td class="product-total">
                                 <span class="amount">Tk. {{ $lineTotal }}</span>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                        <tfoot>
                           <tr class="cart-subtotal">
                              <th>Cart Subtotal</th>
                              <td><span class="amount">Tk. {{ $subTotal }}</span></td>
                           </tr>
                           <tr class="shipping">
                              <th>Shipping</th>
                              <td><span class="amount">Tk. {{ $order->delivery_charge }}</span></td>
                           </tr>
                           <tr class="cart-subtotal">
                              <th>Discount</th>
                              <td><span class="amount">Tk. {{ $order->discount }}</span></td>
                           </tr>
                           <tr class="order-total">
                              <th>Payable Amount</th>
                              <td><strong>TK. {{ $order->final_price }}/-</strong></td>
                           </tr>
                        </tfoot>
                     </table>
                  </div>

                  <div class="mt-30 d-flex flex-wrap gap-2">
                     <a href="{{ route('track-your-order') }}" class="tp-btn tp-color-btn banner-animation">Track Order</a>
                     <a href="{{ route('all-products') }}" class="tp-btn banner-animation">Continue Shopping</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
   $('.cat-menu__category .category-menu').css('display', 'none');
</script>
@endsection
