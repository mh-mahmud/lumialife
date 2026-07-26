@extends('front.html.master')
@section('content')
@include('front.html.css')
	<div class="free">
      <!-- breadcrumb-area -->
      <section class="breadcrumb__area pt-60 pb-60 tp-breadcrumb__bg" data-background="{{url('/')}}/assets/theme/assets/img/banner/breadcrumb-01.jpg">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-xl-7 col-lg-12 col-md-12 col-12">
                  <div class="tp-breadcrumb">
                     <div class="tp-breadcrumb__link mb-10">
                        <span class="breadcrumb-item-active"><a href="index.html">Home</a></span>
                        <span>Dashboard</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Dashboard</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->
          
      <!-- about-area-start -->
      <!-- <section class="about-area pt-80  pb-40"> -->

<div class="pt-80 pb-80">
   <div class="container">
      <div class="bb-customer-page card crop-avatar">
         <div class="container">
            <div class="customer-body">
               <div class="row body-border">
                  <div class="col-md-3 bb-customer-sidebar-wrapper">
                     <div class="bb-profile-sidebar">
                        <div class="bb-profile-user-menu">
                           @include('front.html.sidebar')
                        </div>
                     </div>
                  </div>
                  <div class="col-md-9">

                    <div class="bb-profile-content">
                      <div class="bb-profile-header">
                        <div class="bb-profile-header-title"> Address books </div>
                      </div>
                      <div class="bb-empty">

                        <div class="bb-empty-action">
                          <a class="btn btn-success" type="button" href="{{ route('add-shipping-address') }}"> Add a new address </a>
                        </div>

                        @include('front.html.emptysvg')
                        <p class="bb-empty-title">No addresses!</p>
                        <p class="bb-empty-subtitle text-secondary"> You have not added any addresses yet. </p>

                      </div>
                    </div>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

      <!-- </section> -->
      <!-- about-area-end -->

	</div>

@endsection
@section('custom_js')
   <script type="text/javascript">
      $(document).ready(function() {
         $('.cat-menu__category .category-menu').css('display', 'none');
      })
   </script>
@endsection