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
                        <span>Track Order</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Track Your Order</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->
          
      <!-- track-area-start -->
      <section class="track-area pt-80 pb-80">
         <div class="container">
            <div class="row justify-content-center">



               <div class="col-md-7">

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

                  <div class="tptrack__product">
                     <div class="tptrack__thumb">
                        <img src="{{url('/')}}/assets/theme/assets/img/banner/track-bg.jpg" alt="">
                     </div>
                     <div class="tptrack__content grey-bg-3">
                        <div class="tptrack__item d-flex mb-20">
                           <div class="tptrack__item-icon">
                              <img src="{{url('/')}}/assets/theme/assets/img/icon/track-1.png" alt="">
                           </div>
                           <div class="tptrack__item-content">
                              <h4 class="tptrack__item-title">Track Your Order</h4>
                              <p>To track your order please enter your Order ID in the box below and press the "Track" button. This was  given to you on your receipt and in the confirmation email you should have received.</p>
                           </div>
                        </div>
                        <form action="{{ route('track-your-order') }}" method="POSt">
                           @csrf
                           <div class="tptrack__id mb-10">
                              <span><i class="fal fa-address-card"></i></span>
                              <input required name="order_id" type="text" placeholder="Order ID">
                               @if ($errors->has('order_id'))
                                   <div class="text-danger">{{ $errors->first('order_id') }}</div>
                               @endif
                           </div>
                           <div class="tptrack__email mb-10">
                                 <span><i class="fal fa-phone"></i></span>
                                 <input required name="phone_number" type="text" placeholder="Phone Number">
                               @if ($errors->has('phone_number'))
                                   <div class="text-danger">{{ $errors->first('phone_number') }}</div>
                               @endif
                           </div>
                           <div class="tptrack__btn">
                              <button class="tptrack__submition">Track Now<i class="fal fa-long-arrow-right"></i></button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- track-area-end -->
</div>

@endsection
@section('custom_js')
   <script type="text/javascript">
      $(document).ready(function() {
        $('.cat-menu__category .category-menu').css('display', 'none');
      });
   </script>
@endsection