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
                        <span>Wishlist</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Product Wishlist</h2>
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

                  <form action="#">
                     <div class="table-content table-responsive">
                        <table class="table">
                              <thead>
                                 <tr>
                                    <th class="product-thumbnail">Images</th>
                                    <th class="cart-product-name">Product Name</th>
                                    <th class="product-price">Unit Price</th>
                                    {{--
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-add-to-cart">Add To Cart</th>
                                    --}}
                                    <th class="product-remove">Remove</th>
                                 </tr>
                              </thead>
                              <tbody>

                              	@foreach($lists as $list)
                                 <tr>
                                    <td class="product-thumbnail">
                                       <a href="{{route('product-details', $list->product_id)}}"><img src="{{ \App\Support\MediaStorage::url($list->product_image, 'products') }}" alt="">
                                       </a>
                                    </td>
                                    <td class="product-name">
                                       <a href="{{route('product-details', $list->product_id)}}">{{ $list->product_name }}</a>
                                    </td>
                                    <td class="product-price">
                                       <span class="amount">{{ $list->unit_price }}</span>
                                    </td>
                                    {{--
                                    <td class="product-quantity">
                                          <span class="cart-minus">-</span>
                                          <input class="cart-input" type="text" value="1"/>
                                          <span class="cart-plus">+</span>
                                    </td>
                                    <td class="product-subtotal">
                                       <span class="amount">$130.00</span>
                                    </td>
                                    <td class="product-add-to-cart">
                                       <button class="tp-btn tp-color-btn  tp-wish-cart banner-animation">Add To Cart</button>
                                    </td>
                                    --}}
                                    <td class="product-remove">
                                       <a href="{{ route('remove-wishlist', $list->id) }}"><i class="fa fa-times"></i></a>
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
