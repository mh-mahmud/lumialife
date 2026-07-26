<!doctype html>
<html class="no-js" lang="en">
@php
use App\Models\Category;
use App\Models\Cart;

$sett = \App\Helpers\Helper::settings();
$set_phone = $sett->office_phone_number;
$set_fb = $sett->facebook_link;
$set_twitter = $sett->twitter_link;
$set_youtube = $sett->youtube_link;
$set_insta = $sett->instagram_link;
$set_linkedin = $sett->linkedin_link;
$set_whats_app_link = $sett->whats_app_link;
$site_logo = $sett->site_logo;


$cats = Category::where('parent_id', '=', null)->orderBy('id', 'asc')->get();
if(Auth::user() != null) {
   $carts = Cart::where('user_id', Auth::user()->id)->get();
}
else if(Session::get('car-clinic-visitor') != null) {
   $carts = Cart::where('session_id', Session::get('car-clinic-visitor'))->get();
}
else {
   $carts = [];
}

@endphp
<!-- header links -->
@include('front.html.header')
<style type="text/css">
.whatsapp-float {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background-color: #25D366;
    border-radius: 50%;
    padding: 10px;
    box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    z-index: 99;
}
.whatsapp-float img {
    width: 50px;
}
</style>

<body>


   <!-- preloader -->
   <!-- <div id="preloader">
      <div class="preloader">
            <span></span>
            <span></span>
      </div>
   </div> -->
   <!-- preloader end  -->

   <!-- Scroll-top -->
   <a href="{{ $set_whats_app_link }}" class="whatsapp-float" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" width="40px"></a>
   <button class="scroll-top scroll-to-target" data-target="html">
      <i class="fas fa-angle-up"></i>
   </button>
   <!-- Scroll-top-end-->

   <!-- header-area-start -->
   <header>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <div class="header-top tertiary-header-top space-bg">
         <div class="container">
            <div class="row">
			{{--
               <div class="col-xl-7 col-lg-12 col-md-12">
                  <div class="header-welcome-text">
                     <!-- <span>Welcome to our international shop! Enjoy free shipping on orders $100 & up.</span>
                     <a href="#">Shop Now<i class="fal fa-long-arrow-right"></i></a> -->
                  </div>
               </div>
			--}}
               <div class="col-xl-12 d-none d-xl-block">
                  <div class="headertoplag d-flex align-items-center justify-content-end">
                     <div class="headertoplag__lang">
                        <ul>
                           <li>
                              @if(Auth::user())
                                 <a href="{{ route('customer-dashboard') }}"><i class="fal fa-user"></i> Dashboard</a>
                              @else
                                 <a href="{{ route('user-register') }}"><i class="fal fa-user"></i> Account</a>
                              @endif
                              <a class="order-tick" href="{{ URL::to('track-your-order') }}"><i class="fal fa-plane-departure"></i>Track Your Order</a>
                           </li>
                        </ul>
                     </div>
                     <div class="menu-top-social">
                        <a target="_blank" href="{{ $set_fb }}"><i class="fab fa-facebook-f"></i></a>
                        <a target="_blank" href="{{ $set_twitter }}"><i class="fab fa-twitter"></i></a>
                        <a target="_blank" href="{{ $set_insta }}"><i class="fab fa-instagram"></i></a>
                        <a target="_blank" href="{{ $set_youtube }}"><i class="fab fa-youtube"></i></a>
                        <a target="_blank" href="{{ $set_linkedin }}"><i class="fab fa-linkedin"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="logo-area green-logo-area d-none d-xl-block">
         <div class="container">
            <div class="row align-items-center" style="background-color: #2d2d2d;padding:10px;margin-left:0px;">
               <div class="col-xl-2 col-lg-3">
                  <!-- <div class="logo" style="width:155px;background-color: #332D2D;"> -->
                  <div class="logo">
                     {{--<a href="{{route('index')}}"><img style="width:145px" src="{{url('/')}}/assets/theme/assets/img/logo/car-clinic-logo.png" alt="car-clinic-logo"></a>--}}
                     <a href="{{route('index')}}"><img style="width:145px" src="{{ \App\Support\MediaStorage::url($site_logo, 'settings', '') }}" alt="loyal-home-logo"></a>
                  </div>
               </div>
               <div class="col-xl-10 col-lg-9">
                  <div class="header-meta-info d-flex align-items-center justify-content-between">

                     <div class="header-search-bar">
                        <!-- <form action="#">
                           <div class="search-info p-relative">
                              <button class="header-search-icon"><i class="fal fa-search"></i></button>
                              <input type="text" placeholder="Search products...">
                           </div>
                        </form> -->
                     </div>
                     <div class="header-meta header-language d-flex align-items-center">
                        <!-- <div class="header-meta__lang">
                           <ul>
                              <li>
                                 <a href="#">
                                    <img src="{{url('/')}}/assets/theme/assets/img/icon/lang-flag.png" alt="flag">
                                    English
                                    <span><i class="fal fa-angle-down"></i></span>
                                 </a>
                                 <ul class="header-meta__lang-submenu">
                                    <li>
                                       <a href="#">Arabic</a>
                                    </li>
                                    <li>
                                       <a href="#">Spanish</a>
                                    </li>
                                    <li>
                                       <a href="#">Mandarin</a>
                                    </li>
                                 </ul>
                              </li>
                           </ul>
                        </div> -->
                        <!-- <div class="header-meta__value mr-15">
                           <select>
                              <option>USD</option>
                              <option>YEAN</option>
                              <option>EURO</option>
                           </select>
                        </div> -->
                        <div class="header-meta__social d-flex align-items-center ml-25" style="color:red">
                           <button class="header-cart p-relative tp-cart-toggle">
                              <i class="fal fa-shopping-cart"></i>
                              @if(count($carts) > 0)
                              <span>{{ count($carts) }}</span>
                              @endif
                           </button>
                           <a href="{{ route('user-login') }}"><i class="fal fa-user"></i></a>
                           <a href="{{ route('my-wishlist') }}"><i class="fal fa-heart"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="main-menu-area d-none d-xl-block" style="margin-top:-1px;margin-bottom:30px;">
         <div class="for-megamenu p-relative">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-xl-2 col-lg-3">
                     <div class="cat-menu__category p-relative">
                        <a class="tp-cat-toggle" href="#" role="button"><i style="color:#111" class="fal fa-bars"></i>Menu</a>
                        <div class="category-menu category-menu-off">
                           <ul class="cat-menu__list">
                              @foreach($cats as $cat)

                                 @php
                                    $cat_link = explode(" ", strtolower($cat->category_name));
                                    $cat_link = implode("-", $cat_link);
                                 @endphp

                                 @if($cat->has_child==1)

                                 <li class="menu-item-has-children"><a href="{{ route('product-category-wise', $cat_link) }}"><i class="fal fa-crown"></i>{{ $cat->category_name }}</a>
                                    <ul class="submenu">
                                       @php
                                          $sub_cats = Category::where('parent_id', $cat->id)->where('status', 1)->get(['category_name']);
                                       @endphp

                                       @foreach($sub_cats as $scat)
                                       @php
                                          $scat_link = explode(" ", strtolower($scat->category_name));
                                          $scat_link = implode("-", $scat_link);
                                       @endphp
                                       <li><a href="{{ route('product-category-wise', $scat_link) }}">{{ $scat->category_name }}</a></li>
                                       @endforeach
                                    </ul>
                                 </li>
                                 @else
                                    <li><a href="{{ route('product-category-wise', $cat_link) }}"><i class="fal fa-futbol"></i> {{ $cat->category_name }}</a></li>
                                 @endif
                              @endforeach

                           </ul>
                           <!-- <div class="daily-offer">
                              <ul>
                                 <li><a href="shop.html">Value of the Day</a></li>
                                 <li><a href="shop.html">Top 100 Offers</a></li>
                                 <li><a href="shop.html">New Arrivals</a></li>
                              </ul>
                           </div> -->
                        </div>
                     </div> 
                  </div>
                  <div class="col-xl-7 col-lg-6">
                     <div class="main-menu">
                        <nav id="mobile-menu">
                           <ul>
                              <li><a href="{{route('index')}}">Home</a></li>
                              <li><a href="{{ route('all-products') }}">Products</a></li>
                              <li><a href="{{ route('blogs') }}">Blogs</a></li>
                              <li><a href="{{ route('contact-us') }}">Contact</a></li>
                              <li><a href="{{ route('about-us') }}">About</a></li>
                              <li class="d-block d-md-none has-dropdown has-megamenu">
                                 <a href="#">Categories</a>
                                 <ul class="submenu mega-menu">
                                    @foreach($cats as $cat)

                                    @if($cat->has_child==1)
                                    @php
                                       $sub_cats = Category::where('parent_id', $cat->id)->where('status', 1)->get(['category_name']);
                                    @endphp
                                    <li>
                                       <a class="mega-menu-title">{{ $cat->category_name }}</a>
                                       <ul>
                                          @foreach($sub_cats as $scat)
                                          @php
                                             $scat_link = explode(" ", strtolower($scat->category_name));
                                             $scat_link = implode("-", $scat_link);
                                          @endphp
                                          <li><a href="{{ route('product-category-wise', $scat_link) }}">{{ $scat->category_name }}</a></li>
                                          @endforeach
                                       </ul>
                                    </li>
                                    @else
                                    @php
                                       $cat_link = explode(" ", strtolower($cat->category_name));
                                       $cat_link = implode("-", $cat_link);
                                    @endphp
                                    <li><a href="{{ route('product-category-wise', $cat_link) }}">{{ $cat->category_name }}</a></li>
                                    @endif
                                    @endforeach

                                 </ul>
                              </li>
                           </ul>
                        </nav>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-3">

                     <div class="menu-contact">
                        <ul>
                           <li>
                              <div class="menu-contact__item">
                                 <div class="menu-contact__icon">
                                    <i class="fal fa-phone"></i>
                                 </div>
                                 <div class="menu-contact__info">
                                    <a href="">{{ \App\Helpers\Helper::settings()->office_phone_number }}</a>
                                 </div>
                              </div>
                           </li>
                           <li>
                              <div class="menu-contact__item">
                                 <div class="menu-contact__icon">
                                    <i class="fal fa-map-marker-alt"></i>
                                 </div>
                                 <div class="menu-contact__info">
                                    <a href="#">24/7 Support</a>
                                 </div>
                              </div>
                           </li>
                        </ul>
                     </div>

                  </div>
               </div>
            </div>
         </div>
      </div>

   </header>
   <!-- header-area-end -->

   <!-- header-xl-sticky-area -->
   {{--
   <div id="header-sticky" class="logo-area tp-sticky-one mainmenu-5">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-2 col-lg-3">
               <div class="logo" style="width:155px;background-color: #332D2D;">
                  <a href="{{route('index')}}"><img style="width:145px" src="{{url('/')}}/assets/theme/assets/img/logo/car-clinic-logo.png" alt="logo"></a>
               </div>
            </div>
            <div class="col-xl-6 col-lg-6">
               <div class="main-menu">
                  <nav>
                     <ul>
                        <li><a href="{{route('index')}}">Home</a></li>
                        <li><a href="{{ route('all-products') }}">Products</a></li>
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="{{ route('contact-us') }}">Contact</a></li>
                        <li><a href="{{ route('about-us') }}">About</a></li>
                        <li><a href="{{ route('careers') }}">Career</a></li>
                        
                        <li class="has-dropdown has-megamenu">
                           <a href="about.html">Pages</a>
                           <ul class="submenu mega-menu">
                              <li>
                                 <a class="mega-menu-title">Page layout</a>
                                 <ul>
                                    <li><a href="shop.html">Shop filters v1</a></li>
                                    <li><a href="shop-2.html">Shop filters v2</a></li>
                                    <li><a href="shop-details.html">Shop sidebar</a></li>
                                    <li><a href="shop-details-2.html">Shop Right sidebar</a></li>
                                    <li><a href="shop-location.html">Shop List view</a></li>
                                 </ul>
                              </li>
                              <li>
                                 <a class="mega-menu-title">Page layout</a>
                                 <ul>
                                    <li><a href="about.html">About</a></li>
                                    <li><a href="cart.html">Cart</a></li>
                                    <li><a href="checkout.html">Checkout</a></li>
                                    <li><a href="sign-in.html">Sign In</a></li>
                                    <li><a href="sign-in.html">Log In</a></li>
                                 </ul>
                              </li>
                              <li>
                                 <a class="mega-menu-title">Page type</a>
                                 <ul>
                                    <li><a href="track.html">Product Track</a></li>
                                    <li><a href="wishlist.html">Wishlist</a></li>
                                    <li><a href="error.html">404 / Error</a></li>
                                    <li><a href="coming-soon.html">Coming Soon</a></li>
                                 </ul>
                              </li>
                           </ul>
                        </li>
                        
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="col-xl-4 col-lg-9">
               <div class="header-meta-info d-flex align-items-center justify-content-end">
                  <div class="header-meta__social  d-flex align-items-center"> 
                     <button class="header-cart p-relative tp-cart-toggle">
                        <i class="fal fa-shopping-cart"></i>
                        
                        @if(count($carts) > 0)
                           <span class="tp-product-count">{{ count($carts) }}</span>
                        @endif

                     </button>
                     <a href="{{ route('user-login') }}"><i class="fal fa-user"></i></a>
                     <a href="{{ route('my-wishlist') }}"><i class="fal fa-heart"></i></a>
                  </div>
                  <div class="header-meta__search-5 ml-25">
                     <div class="header-search-bar-5">
                        <form action="#">
                           <div class="search-info-5 p-relative">
                              <button class="header-search-icon-5"><i class="fal fa-search"></i></button>
                              <input type="text" placeholder="Search products...">
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   --}}

   <!-- header-xl-sticky-end -->

   <!-- header-md-lg-area -->
   <div id="header-tab-sticky" class="tp-md-lg-header d-none d-md-block d-xl-none" style="background-color:#2d2d2d;margin-bottom: 20px;padding: 10px;">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 d-flex align-items-center">
               <div class="header-canvas flex-auto">
                  <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
               </div>
               <div class="logo">
                  <a href="{{route('index')}}">
                      <img style="width:145px" src="{{ \App\Support\MediaStorage::url($site_logo, 'settings', '') }}" alt="loyal-home-logo">
                  </a>
               </div>
            </div>
            <div class="col-lg-9 col-md-8">
               <div class="header-meta-info d-flex align-items-center justify-content-between">
                  <div class="header-search-bar">
                     {{--
                     <form action="#">
                        <div class="search-info p-relative">
                           <button class="header-search-icon"><i class="fal fa-search"></i></button>
                           <input type="text" placeholder="Search products...">
                        </div>
                     </form>
                     --}}
                  </div>
                  <div class="header-meta__social d-flex align-items-center ml-25">
                     <button class="header-cart p-relative tp-cart-toggle">
                        <i class="fal fa-shopping-cart"></i>
                        @if(count($carts) > 0)
                           <span>{{ count($carts) }}</span>
                        @endif
                     </button>
                     <a href="{{ route('user-login') }}"><i class="fal fa-user"></i></a>
                     <a href="{{ route('my-wishlist') }}"><i class="fal fa-heart"></i></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="header-mob-sticky" class="tp-md-lg-header d-md-none pt-20 pb-20" style="background-color: #2d2d2d;margin-bottom: 20px;">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-3 d-flex align-items-center">
               <div class="header-canvas flex-auto">
                  <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
               </div>
            </div>
            <div class="col-6">
               <div class="logo text-center">
                  {{--<a href="{{route('index')}}"><img style="width:145px" src="{{url('/')}}/assets/theme/assets/img/logo/car-clinic-logo.png" alt="logo"></a>--}}
                  <a href="{{route('index')}}"><img style="width:145px" src="{{ \App\Support\MediaStorage::url($site_logo, 'settings', '') }}" alt="loyal-home-logo"></a>
               </div>
            </div>
            <div class="col-3" style="color:#DC4C64;">
               <div class="header-meta-info d-flex align-items-center justify-content-end ml-25">
                  <div class="header-meta m-0 d-flex align-items-center">
                     <div class="header-meta__social d-flex align-items-center"> 
                        <button class="header-cart p-relative tp-cart-toggle">
                           <i class="fal fa-shopping-cart"></i>
                           @if(count($carts) > 0)
                              <span>{{ count($carts) }}</span>
                           @endif
                        </button>
                        @if(Auth::user())
                           <a href="{{ route('customer-dashboard') }}"><i class="fal fa-tachometer-alt"></i></a>
                        @else
                           <a href="{{ route('user-login') }}"><i class="fal fa-user"></i></a>
                        @endif
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- header-md-lg-area -->

   <!-- sidebar-menu-area -->
   <div class="tpsideinfo">
      <button class="tpsideinfo__close">Close<i class="fal fa-times ml-10"></i></button>
      <div class="tpsideinfo__search text-center pt-35">
         <span class="tpsideinfo__search-title mb-20">What Are You Looking For?</span>
         <form action="#">
            <input type="text" placeholder="Search Products...">
            <button><i class="fal fa-search"></i></button>
         </form>
      </div>
      <div class="tpsideinfo__nabtab">
         <!-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                  type="button" role="tab" aria-controls="pills-home" aria-selected="true">Menu</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                  type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Categories</button>
            </li>
         </ul> -->
         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
               tabindex="0">
               <div class="mobile-menu"></div>
            </div>
            <!-- <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
               tabindex="0">
               <div class="tpsidebar-categories">
                  <ul>
                     <li><a href="shop-2.html">Dairy Farm</a></li>
                     <li><a href="shop-2.html">Healthy Foods</a></li>
                     <li><a href="shop-2.html">Lifestyle</a></li>
                     <li><a href="shop-2.html">Organics</a></li>
                     <li><a href="shop-2.html">Photography</a></li>
                     <li><a href="shop-2.html">Shopping</a></li>
                     <li><a href="shop-2.html">Tips & Tricks</a></li>
                  </ul>
               </div>
            </div> -->
         </div>
      </div>
      @if(Auth::user())
      <div class="tpsideinfo__account-link">
         <a href="{{ route('customer-dashboard') }}"><i class="fal fa-tachometer-alt"></i> Dashboard</a>
      </div>
      @else
      <div class="tpsideinfo__account-link">
         <a href="{{ route('user-login') }}"><i class="fal fa-user"></i> Login</a>
      </div>
      <div class="tpsideinfo__account-link">
         <a href="{{ route('user-register') }}"><i class="fal fa-user-plus"></i> Register</a>
      </div>
      @endif

      <div class="tpsideinfo__wishlist-link">
         <a href="{{ route('my-wishlist') }}" target="_parent"><i class="fal fa-heart"></i> Wishlist</a>
      </div>
   </div>
   <div class="body-overlay"></div>
   <!-- sidebar-menu-area-end -->

   <!-- header-cart-start -->
   <div class="tpcartinfo tp-cart-info-area p-relative">
      <button class="tpcart__close"><i class="fal fa-times"></i></button>
      <div class="tpcart">
         <h4 class="tpcart__title">Your Cart</h4>
         <div class="tpcart__product">
            <div class="tpcart__product-list">
               <ul>
                  
                  @php $total_cart=0; @endphp
                  @foreach($carts as $cart)
                  <li>
                     <div class="tpcart__item">
                        <div class="tpcart__img">
                           <img src="{{ \App\Support\MediaStorage::url($cart->product_image, 'products') }}" alt="">
                           <div class="tpcart__del">
                              <a href="{{ route('remove-from-cart', $cart->id) }}"><i class="far fa-times-circle"></i></a>
                           </div>
                        </div>
                        <div class="tpcart__content">
                           <span class="tpcart__content-title"><a href="{{ route('product-details', $cart->product_id) }}">{{ $cart->product_name }}</a>
                           </span>
                           <div class="tpcart__cart-price">
                              <span class="quantity">{{ $cart->quantity }}</span>
                              <span class="new-price">Tk.{{ $cart->total_price }}</span>
                           </div>
                        </div>
                     </div>
                  </li>
                  @php $total_cart += $cart->total_price @endphp
                  @endforeach

               </ul>
            </div>
            @if(count($carts) > 0)
            <div class="tpcart__checkout">
               <div class="tpcart__total-price d-flex justify-content-between align-items-center">
                  <span> Subtotal:</span>
                  <span class="heilight-price">Tk. {{$total_cart}}</span>
               </div>
               <div class="tpcart__checkout-btn">
                  <a class="tpcart-btn mb-10" href="{{ route('add-to-cart-details') }}">View Cart</a>
                  <a class="tpcheck-btn" href="{{ route('checkout') }}">Checkout</a>
               </div>
            </div>
            @else
            <div class="tpcart__checkout">
               Your cart is empty
            </div>
            @endif
         </div>
         <div class="tpcart__free-shipping text-center">
            <span>Free shipping for orders <b>under Dhaka City</b></span>
         </div>

      </div>
   </div>
   <div class="cartbody-overlay"></div>
   <!-- header-cart-end -->

   <!-- main-area-start -->
   <main style="min-height: 700px;">

      @yield('content')

   </main>
   <!-- main-area-end -->

   <!-- footer section -->
   @include('front.html.footer')


   <!-- JS here -->
   <script src="{{url('/')}}/assets/theme/assets/js/jquery.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/waypoints.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/bootstrap.bundle.min.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/swiper-bundle.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/slick.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/magnific-popup.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/nice-select.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/counterup.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/wow.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/isotope-pkgd.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/imagesloaded-pkgd.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/countdown.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/ajax-form.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/meanmenu.js"></script>
   <script src="{{url('/')}}/assets/theme/assets/js/main.js"></script>
   @yield('custom_js')
</body>
</html>
