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
                           <div class="bb-profile-header-title"> Order History </div>
                        </div>
                        <div class="bb-customer-profile-wrapper">
                           <div class="bb-customer-profile">
                              
                              <div class="bb-customer-profile-info">
                              <div class="table-responsive">
                              <table class="table table-sm table-condensed table-row-gray-100 align-middle gs-0 gy-3 table-row-bordered">
                                <thead>
                                  <tr>
                                    <th>SL</th>
                                    <th>Order No</th>
                                    <th>Total</th>
                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Delivery Status</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                    <td>{{ $order->custom_order_id }}</td>
                                    <td>{{ $order->total_price }}</td>

                                    <td>{{ $order->order_status }}</td>
                                    <td>{{ $order->payment_status }}</td>
                                    <td>{{ $order->delivery_status }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <div class="d-inline-flex justify-content-end gap-1 w-100 border-bottom-0">
                                          <a href="{{ route('customer-order-details', $order->custom_order_id) }}" class="btn btn-success btn-bg-light btn-active-color-primary btn-sm me-1">Details</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                              </table>
                              </div>
                              </div>
                           </div>

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