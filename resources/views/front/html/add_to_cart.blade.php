@extends('front.html.master')
@section('content')

	<div class="free">
      <!-- breadcrumb-area -->
      <section class="breadcrumb__area pt-60 pb-60 tp-breadcrumb__bg" data-background="{{url('/')}}/assets/theme/assets/img/banner/breadcrumb-01.jpg">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-xl-7 col-lg-12 col-md-12 col-12">
                  <div class="tp-breadcrumb">
                     <div class="tp-breadcrumb__link mb-10">
                        <span class="breadcrumb-item-active"><a href="{{ route('index') }}">Home</a></span>
                        <span>Cart</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Product Cart</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->

      <!-- cart area -->
      <section class="cart-area pt-80 pb-80 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
         <div class="container">
         <div class="row">
            <div class="col-12">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

                  <form action="{{ route('go-checkout') }}" method="POST">
                     @csrf
                     <div class="table-content table-responsive">
                        <table id="cartTable" class="table">
                              <thead>
                                 <tr>
                                    <th class="product-thumbnail">Images</th>
                                    <th class="cart-product-name">Name</th>
                                    <th class="product-price">Unit Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-remove">Remove</th>
                                 </tr>
                              </thead>
                              <tbody>

                                 @php
                                    $total = [];
                                    //dd($carts);
                                 @endphp
                                 @foreach($carts as $cart)

                                 <tr>
                                    <input type="hidden" name="cart_id[]" value="{{$cart->id}}">
                                    <td class="product-thumbnail">
                                       <a href="{{ route('product-details', $cart->product_id) }}"><img src="{{ \App\Support\MediaStorage::url($cart->product_image, 'products') }}" alt="">
                                       </a>
                                    </td>
                                    <td class="product-name">
                                       <a href="{{ route('product-details', $cart->product_id) }}">{{ $cart->product_name }}</a>
                                    </td>
                                    <td class="product-price">
                                       <span class="unit-price" data-price="{{$cart->unit_price}}">Tk. {{ $cart->unit_price }}</span>
                                       <input type="hidden" name="unit_price[]" value="{{$cart->unit_price}}">
                                    </td>
                                    <td class="product-quantity">
                                          <span class="cart-minus">-</span>
                                          <input class="cart-input quantity" name="quantity[]" type="text" value="{{ $cart->quantity }}"/>
                                          <span class="cart-plus">+</span>
                                    </td>
                                    <td class="product-subtotal">
                                       TK. <span class="product-total">{{ $cart->total_price }}</span>
                                    </td>
                                    <td class="product-remove">
                                       <a href="{{ route('remove-from-cart', $cart->id) }}"><i class="fa fa-times"></i></a>
                                    </td>
                                 </tr>
                                 @php $total[] = $cart->total_price @endphp
                                 @endforeach
                                 @php $sub_total = array_sum($total) @endphp

                              </tbody>
                        </table>
                     </div>

                     {{--
                     <div class="row">
                        <div class="col-12">
                              <div class="coupon-all">
                                 <div class="coupon">
                                    <input id="coupon_code" class="input-text" name="coupon_code" value=""
                                          placeholder="Coupon code" required type="text">
                                    <button id="apply-coupon" class="tp-btn tp-color-btn banner-animation" name="apply_coupon" type="submit">Apply Coupon</button>
                                    <p class="coupon-p" style="color:red;font-size: 14px;display:none;">Invalid coupon</p>
                                 </div>
                                 <div class="coupon2">
                                    <button class="tp-btn tp-color-btn banner-animation" name="update_cart" type="submit">Update cart</button>
                                 </div>
                              </div>
                        </div>
                     </div>
                     --}}
                     <div class="row justify-content-end">
                        <div class="col-md-5 ">
                              <div class="cart-page-total">
                                 <h2>Cart total</h2>
                                 <ul class="mb-20">
                                    <li>Subtotal <span>Tk. <span class="cart-total">{{ $sub_total }}</span></span></li>
                                    <li>Total <span>Tk. <span class="cart-total">{{ $sub_total }}</span></span></li>
                                 </ul>
                                 <!-- <a href="{{ route('checkout') }}" class="tp-btn tp-color-btn banner-animation">Proceed to Checkout</a> -->
                                 <div>
                                 @if(count($carts) > 0)
                                 <button style="overflow: hidden !important;" type="submit" class="tp-btn tp-color-btn banner-animation">Proceed to Checkout</button>
                                 @endif
                                 <a href="{{ route('all-products') }}" class="tp-btn banner-animation" style="background-color:#66BB6A;overflow: visible !important;">Continue Shopping</a>
                                 </div>

                                 
                              </div>
                        </div>
                     </div>
                  </form>
            </div>
         </div>
         </div>
      </section>
      <!-- cart area end-->
    </div>

@endsection

@section('custom_js')
<script type="text/javascript">
   $('.cat-menu__category .category-menu').css('display', 'none');
   $("#apply-coupon").on("click", function(e) {
      e.preventDefault();
      let code = $("#coupon_code").val();
      if(code!="") {
         $('.coupon-p').show();
      }
      else {
         $('.coupon-p').hide();
      }
   });

   $(document).ready(function() {
     // Function to update total prices
      function updateCart() {
         let cartTotal = 0;

         // Iterate through each row in the cart
         $('#cartTable tbody tr').each(function() {
             const unitPrice = parseFloat($(this).find('.unit-price').data('price'));
             const quantity = parseInt($(this).find('.quantity').val());
             const productTotal = unitPrice * quantity;

             // Update the product total in the table
             $(this).find('.product-total').text(productTotal.toFixed(2));

             // Add to cart total
             cartTotal += productTotal;
         });

         // Update the cart total
         $('.cart-total').text(cartTotal.toFixed(2));
      }

      // Event listener for quantity changes
      $("span.cart-plus, span.cart-minus").on("click", function() {
         updateCart();
      });
   });

</script>
@endsection
