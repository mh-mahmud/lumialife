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
                     <span>Checkout</span>
                  </div>
                  <h2 class="tp-breadcrumb__title">Checkout Page</h2>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- coupon-area start -->

   <section class="coupon-area pt-80 pb-30 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
      {{--
         <div class="container">
         <div class="row">
            <div class="col-md-6">
               <div class="coupon-accordion">
                     <!-- ACCORDION START -->
                     <h3>Returning customer? <span id="showlogin">Click here to login</span></h3>
                     <div id="checkout-login" class="coupon-content">
                        <div class="coupon-info">
                           <p class="coupon-text">Quisque gravida turpis sit amet nulla posuere lacinia. Cras sed est
                                 sit amet ipsum luctus.</p>
                           <form action="#">
                                 <p class="form-row-first">
                                    <label>Username or email <span class="required">*</span></label>
                                    <input type="text" />
                                 </p>
                                 <p class="form-row-last">
                                    <label>Password <span class="required">*</span></label>
                                    <input type="text" />
                                 </p>
                                 <p class="form-row">
                                    <button class="tp-btn tp-color-btn" type="submit">Login</button>
                                    <label>
                                       <input type="checkbox" />
                                       Remember me
                                    </label>
                                 </p>
                                 <p class="lost-password">
                                    <a href="#">Lost your password?</a>
                                 </p>
                           </form>
                        </div>
                     </div>
                     <!-- ACCORDION END -->
               </div>
            </div>
            <div class="col-md-6">
               <div class="coupon-accordion">
                     <!-- ACCORDION START -->
                     <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                     <div id="checkout_coupon" class="coupon-checkout-content">
                        <div class="coupon-info">
                           <form action="#">
                                 <p class="checkout-coupon">
                                    <input type="text" placeholder="Coupon Code" />
                                    <button class="tp-btn tp-color-btn" type="submit">Apply Coupon</button>
                                 </p>
                           </form>
                        </div>
                     </div>
                     <!-- ACCORDION END -->
               </div>
            </div>
         </div>
      </div>
      --}}
   </section>

   <!-- coupon-area end -->

   <!-- checkout-area start -->
   <section class="checkout-area pb-50 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
      <div class="container">

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

         @if ($errors->any())
         <div class="alert alert-danger">
            <ul class="mb-0">
               @foreach ($errors->all() as $error)
               <strong>{{ $error }}</strong>
               @endforeach
            </ul>
         </div>
         @endif

         @if(!empty($carts))
         <form action="{{ route('checkout-store') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_session_id" value="{{ $session_id }}">
            <div class="row">
               <div class="col-lg-6 col-md-12">
                  <div class="checkbox-form">
                     <h3>Billing Details</h3>
                     <div class="row">

                        <div class="col-md-6">
                           <div class="checkout-form-list">
                              <label>Full Name <span class="required">*</span></label>
                              <input type="text" value="{{ Auth::user() ? Auth::user()->first_name : old('first_name') }}" name="first_name" required />
                           </div>
                        </div>
                        <!-- <div class="col-md-6">
									<div class="checkout-form-list">
									   <label>Last Name <span class="required">*</span></label>
									   <input name="last_name" value="{{ Auth::user() ? Auth::user()->last_name : null }}" type="text" required />
									</div>
									</div> -->

                        {{--
									<div class="col-md-6">
									<div class="checkout-form-list">
									   <label>Company Name</label>
									   <input name="company_name" type="text" />
									</div>
									</div>
                           --}}

                        <!-- <div class="col-md-6">
									<div class="checkout-form-list">
									   <label>Town / City</label>
									   <input type="text" name="city" value="{{ Auth::user() ? Auth::user()->city : null }}" />
									</div>
									</div>
									<div class="col-md-6">
									<div class="checkout-form-list">
									   <label>State <span class="required">*</span></label>
									   <input type="text" name="state" value="{{ Auth::user() ? Auth::user()->state : null }}" />
									</div>
									</div>
									<div class="col-md-6">
									<div class="checkout-form-list">
									   <label>Postcode / Zip <span class="required">*</span></label>
									   <input type="text" name="zip" value="{{ Auth::user() ? Auth::user()->zip : null }}" />
									</div>
									</div>
									<div class="col-md-6">
									<div class="checkout-form-list">
									   <label>Email Address</label>
									   <input name="email" type="email" value="{{ Auth::user() ? Auth::user()->email : null }}" />
									</div>
									</div> -->
                        <div class="col-md-6">
                           <div class="checkout-form-list">
                              <label>Phone <span class="required">*</span></label>
                              <input name="mobile" type="text" value="{{ Auth::user() ? Auth::user()->phone_number : old('mobile') }}" required placeholder="Phone Number" />
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="checkout-form-list">
                              <label>Shipping Address <span class="required">*</span></label>
                              <div class="order-notes">
                                 <div class="checkout-form-list">
                                    <textarea id="checkout-mess" cols="30" rows="10" name="shipping_address">{{ Auth::user() ? Auth::user()->address : old('shipping_address') }}</textarea>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- <div class="col-md-12">
									<div class="checkout-form-list">
									   <label>Shipping Address (optional)</label>
									 <div class="order-notes">
									    <div class="checkout-form-list">
									       <textarea id="checkout-mess" cols="30" rows="10" name="shipping_address_2"></textarea>
									    </div>
									 </div>

									</div>
									</div> -->
                        <div class="col-md-12">
                           <div class="checkout-form-list">
                              <label>Order Notes</label>
                              <div class="order-notes">
                                 <div class="checkout-form-list">
                                    <textarea name="order_note" cols="30" rows="10"
                                       placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('order_note') }}</textarea>
                                 </div>
                              </div>

                           </div>
                        </div>

                        @if(Auth::user()==null)
                        <div class="col-md-12">
                           <div class="checkout-form-list create-acc">
                              <label>Create an account? Go to <a style="color:red" href="{{ route('user-register') }}">Signup Page</a></label>
                           </div>
                        </div>
                        @endif
                     </div>

                  </div>
               </div>
               <div class="col-lg-6 col-md-12">
                  <div class="your-order mb-30 ">
                     <h3>Your order</h3>
                     <div class="your-order-table table-responsive">
                        <table>
                           <thead>
                              <tr>
                                 <th class="product-name"><b>Product<b></th>
                                 <th class="product-total"><b>Total<b></th>
                              </tr>
                           </thead>
                           <tbody>

                              @php $total = []; @endphp
                              @foreach($carts as $cart)
                              <tr class="cart_item">
                                 <td class="product-name">
                                    {{ $cart->product_name }} <strong class="product-quantity"> × {{ $cart->quantity }}</strong>
                                 </td>
                                 <td class="product-total">
                                    <span class="amount">{{ $cart->total_price }}</span>
                                 </td>
                              </tr>
                              @php $total[] = $cart->total_price @endphp
                              @endforeach
                              @php $sub_total = array_sum($total) @endphp

                           </tbody>
                           <tfoot>
                              <tr class="cart-subtotal">
                                 <th>Cart Subtotal</th>
                                 <td><span class="amount">Tk.{{$sub_total}}</span></td>
                              </tr>
                              <tr class="shipping">
                                 <th>Shipping</th>
                                 <td>
                                    <ul>
                                       <li>
                                          <input required id="charge_inside_dhaka" type="radio" name="shipping" value="{{\App\Helpers\Helper::settings()->charge_inside_dhaka}}" />
                                          <label style="color:red;">Inside Dhaka: <span class="amount">Tk. {{\App\Helpers\Helper::settings()->charge_inside_dhaka}}/-</span></label>
                                       </li>
                                       <li>
                                          <input required id="charge_outside_dhaka" type="radio" name="shipping" value="{{\App\Helpers\Helper::settings()->charge_outside_dhaka}}" />
                                          <label style="color:red">Next to Dhaka: <span class="amount">Tk. {{\App\Helpers\Helper::settings()->charge_outside_dhaka}}/-</span></label>
                                       </li>
                                    </ul>

                                    @error('shipping')
                                    <div class="text-danger">
                                       {{ $message }}
                                    </div>
                                    @enderror
                                 </td>
                              </tr>
                              <tr class="order-total">
                                 <input id="order_total" type="hidden" name="total_price" value="{{$sub_total}}">
                                 <input type="hidden" name="discount" value="0">
                                 <th>Order Total</th>
                                 <td><strong>TK.<span class="total_amount">{{$sub_total}}</span></strong>
                                 </td>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                     <div class="payment-method">
                        <div class="accordion" id="checkoutAccordion">
                           {{--
                                 <div class="accordion-item">
                                    <h2 class="accordion-header" id="checkoutOne">
                                       <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bankOne" aria-expanded="true" aria-controls="bankOne">
                                       Cash on Delivery
                                       </button>
                                    </h2>
                                    <div id="bankOne" class="accordion-collapse collapse show" aria-labelledby="checkoutOne" data-bs-parent="#checkoutAccordion">
                                       <div class="accordion-body">
                                       Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.
                                       </div>
                                    </div>
                                 </div>
                                 --}}


                           <div class="accordion-item">
                              <h2 class="accordion-header" id="paymentTwo">
                                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment" aria-expanded="false" aria-controls="payment">
                                    Payment Type: Cash on Delivery
                                 </button>
                              </h2>
                              <div id="payment" class="accordion-collapse collapse" aria-labelledby="paymentTwo" data-bs-parent="#checkoutAccordion">
                                 <div class="accordion-body">
                                    Now, we only support cash on delivery
                                 </div>
                              </div>
                           </div>

                           {{--
                                 <div class="accordion-item">
                                    <h2 class="accordion-header" id="paypalThree">
                                       <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#paypal" aria-expanded="false" aria-controls="paypal">
                                       PayPal
                                       </button>
                                    </h2>
                                    <div id="paypal" class="accordion-collapse collapse" aria-labelledby="paypalThree" data-bs-parent="#checkoutAccordion">
                                       <div class="accordion-body">
                                       Pay via PayPal; you can pay with your credit card if you don’t have a
                                       PayPal account.
                                       </div>
                                    </div>
                                 </div>
                                 --}}
                        </div>
                        <div class="order-button-payment mt-20">
                           <button type="submit" class="tp-btn tp-color-btn w-100 banner-animation">Place order</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
         @else
         <div class="alert alert-danger" role="alert">
            Your cart is empty
         </div>
         @endif
      </div>
   </section>
   <!-- checkout-area end -->

</div>

@endsection

@section('custom_js')
<script type="text/javascript">
   $('.cat-menu__category .category-menu').css('display', 'none');
   $("#apply-coupon").on("click", function(e) {
      e.preventDefault();
      let code = $("#coupon_code").val();
      if (code != "") {
         $('.coupon-p').show();
      } else {
         $('.coupon-p').hide();
      }
   });
   var order_total = parseInt($("#order_total").val());
   $('input[name="shipping"]').change(function() {
      var selectedValue = $('input[name="shipping"]:checked').val();
      var total = order_total + parseInt(selectedValue);
      $("span.total_amount").text(total);
      $("#order_total").val(total);
      console.log(selectedValue);
   });
</script>
@endsection