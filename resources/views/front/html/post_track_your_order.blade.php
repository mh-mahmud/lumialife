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
                        <span>Track Your Order</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Your Order Details</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->
          
      <!-- cart area -->
      <div class="cart-area pt-80 pb-80 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
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

                  
                  <p>Order Status: <strong>{{ $chk_data->order_status }}</strong></p>
                  <p>Total Order Value: <strong>Tk. {{ $chk_data->total_price }}</strong></p>
                  <p>Payment Status: <strong>{{ $chk_data->payment_status }}</strong></p>
                  <p>Delivery Status: <strong>{{ $chk_data->delivery_status }}</strong></p>
                  <p>Possible Delivery Date: <strong>{{ $chk_data->possible_delivery_date }}</strong></p>
                  <br><br>


                  <form action="#">
                     <div class="table-content table-responsive">
                        <table class="table">
                              <thead>
                                 <tr>
                                    <th class="product-thumbnail">Images</th>
                                    <th class="cart-product-name">Product Name</th>
                                    <th class="product-price">Unit Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                 </tr>
                              </thead>
                              <tbody>

                              	@foreach($lists as $list)
                                 <tr>
                                    <td class="product-thumbnail">
                                       <a href="{{route('product-details', $list->product_id)}}"><img src="{{ \App\Support\MediaStorage::url($list->product->img_path, 'products') }}" alt="">
                                       </a>
                                    </td>
                                    <td class="product-name">
                                       <a>{{ $list->product->name }}</a>
                                    </td>
                                    <td class="product-price">
                                       <span class="amount">{{ $list->unit_price }}</span>
                                    </td>
                                    <td class="product-subtotal">
                                       <span class="amount">{{ $list->quantity }}</span>
                                    </td>
                                    <td class="product-subtotal">
                                       <span class="amount">{{ $list->total }}</span>
                                    </td>

                                 </tr>
                                @endforeach

                              </tbody>
                        </table>
                     </div>
                  </form>
            </div>
         </div>
         </div>
      </div>
      <!-- cart area end-->
</div>

@endsection
@section('custom_js')
   <script type="text/javascript">
      $(document).ready(function() {
        $('.cat-menu__category .category-menu').css('display', 'none');
      });
   </script>
@endsection
