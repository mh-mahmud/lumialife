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
                        <span class="breadcrumb-item-active"><a href="index.html">Home</a></span>
                        <span>Product Details</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Product Details</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->

      <!-- product-area-start -->
      <section class="product-area pt-80 pb-25">
         <div class="container">
            <div class="row">
               <div class="col-lg-5 col-md-12">
                  <div class="tpproduct-details__nab pr-50 mb-40">
                     <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                          <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                              <img src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}" alt="">
                           </button>

                           @if(!empty($product->img_path_2))
                          <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                              <img src="{{ \App\Support\MediaStorage::url($product->img_path_2, 'products') }}" alt="">
                           </button>
                           @endif

                           @if(!empty($product->img_path_3))
                          <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                           <img src="{{ \App\Support\MediaStorage::url($product->img_path_3, 'products') }}" alt="">
                           </button>
                           @endif

                        </div>

                        <div class="tab-content" id="v-pills-tabContent">

                          <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                           <img src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}" alt="">
                          </div>

                          @if(!empty($product->img_path_2))
                          <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                           <img src="{{ \App\Support\MediaStorage::url($product->img_path_2, 'products') }}" alt="">
                          </div>
                          @endif

                          @if(!empty($product->img_path_3))
                          <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                           <img src="{{ \App\Support\MediaStorage::url($product->img_path_3, 'products') }}" alt="">
                          </div>
                          @endif
                          
                        </div>
                      </div>
                  </div>
               </div>
               <div class="col-lg-5 col-md-7">
                  <div class="tpproduct-details__content">
                     <div class="tpproduct-details__tag-area d-flex align-items-center mb-5">
                        <span class="tpproduct-details__tag">{{$product->category->category_name}}</span>
                        <div class="tpproduct-details__rating">
                           <a href="#"><i class="fas fa-star"></i></a>
                           <a href="#"><i class="fas fa-star"></i></a>
                           <a href="#"><i class="fas fa-star"></i></a>
                        </div>
                        <a class="tpproduct-details__reviewers">{{ $product->total_sell }}+ Total Sell</a>
                     </div>
                     <div class="tpproduct-details__title-area d-flex align-items-center flex-wrap mb-5">
                        <h3 class="tpproduct-details__title">{{$product->name}}</h3>
                        @if($product->stock_status=="In Stock")
                           <span class="tpproduct-details__stock" style="color:green">{{ $product->stock_status }}</span>
                        @else
                           <span class="tpproduct-details__stock">{{ $product->stock_status }}</span>
                        @endif
                        
                     </div>
                     <div class="tpproduct-details__price mb-30">
                        <!-- <del>$9.35</del> -->
                        <span>Tk. {{$product->product_value}}</span>
                     </div>
                     {{--
                     <div class="tpproduct-details__pera" style="max-width: 90%;">
                        <p>{!!$product->description!!}</p>
                     </div>
                     --}}
                     <div class="tpproduct-details__count d-flex align-items-center flex-wrap mb-25">
                        <div class="tpproduct-details__quantity">
                           <span class="cart-minus"><i class="far fa-minus"></i></span>
                           <input class="tp-cart-input" type="text" value="1">
                           <span class="cart-plus"><i class="far fa-plus"></i></span>
                        </div>
                        <div class="tpproduct-details__cart ml-20">
                            <button data-cart-url="{{ route('add-to-cart', $product->slug) }}" id="add-to-cart"><i class="fal fa-shopping-cart"></i> Add To Cart</button>
                           <!-- <a href=""><i class="fal fa-shopping-cart"></i> Add To Cart</a> -->
                        </div>

                        {{--
                        <div class="tpproduct-details__wishlist ml-20">
                           <a href="#"><i class="fal fa-heart"></i></a>
                        </div>
                        --}}

                     </div>
                     {{--
                     <div class="tpproductdot mb-30">
                        <a class="tpproductdot__variationitem" href="#">
                           <div class="tpproductdot__termshape">
                              <span class="tpproductdot__termshape-bg"></span>
                              <span class="tpproductdot__termshape-border"></span>
                           </div>
                        </a>
                        <a class="tpproductdot__variationitem" href="#">
                           <div class="tpproductdot__termshape">
                              <span class="tpproductdot__termshape-bg red-product-bg"></span>
                              <span class="tpproductdot__termshape-border red-product-border"></span>
                           </div>
                        </a>
                        <a class="tpproductdot__variationitem" href="#">
                           <div class="tpproductdot__termshape">
                              <span class="tpproductdot__termshape-bg orange-product-bg"></span>
                              <span class="tpproductdot__termshape-border orange-product-border"></span>
                           </div>
                        </a>
                        <a class="tpproductdot__variationitem" href="#">
                           <div class="tpproductdot__termshape">
                              <span class="tpproductdot__termshape-bg purple-product-bg"></span>
                              <span class="tpproductdot__termshape-border purple-product-border"></span>
                           </div>
                        </a>
                     </div>
                     --}}
                     <div class="tpproduct-details__information tpproduct-details__code">
                        <p>SKU:</p><span>{{$product->product_code}}</span>
                     </div>
                     <div class="tpproduct-details__information tpproduct-details__categories">
                        <p>Categories:</p>
                        @php
                           $cat = explode(" ", strtolower($product->category->category_name));
                           $cat = implode("-", $cat);
                        @endphp
                        <span><a href="{{ route('product-category-wise', $cat) }}">{{ $product->category->category_name }}</a></span>
                     </div>

                     <div class="tpproduct-details__information tpproduct-details__categories">
                        <p>Brand Name:</p>
                        @php
                           $brand = explode(" ", strtolower($product->brand->brand_name));
                           $brand = implode("-", $brand);
                        @endphp
                        <span><a href="{{ route('product-brand-wise', $brand) }}">{{ $product->brand->brand_name }}</a></span>
                     </div>

                     {{--
                     <div class="tpproduct-details__information tpproduct-details__tags">
                        <p>Tags:</p>
                        <span><a href="#">fashion,</a></span>
                        <span><a href="#">t-shirts,</a></span>
                        <span><a href="#">women</a></span>
                     </div>
                     --}}
                     <div class="tpproduct-details__information tpproduct-details__social">
                        <p>Share:</p>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-behance"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-2 col-md-5">
                  <div class="tpproduct-details__condation">
                     <ul>
                        <li>
                           <div class="tpproduct-details__condation-item d-flex align-items-center">
                              <div class="tpproduct-details__condation-thumb">
                                 <img src="{{url('/')}}/assets/theme/assets/img/icon/product-det-1.png" alt="" class="tpproduct-details__img-hover">
                              </div>
                              <div class="tpproduct-details__condation-text">
                                 <p>Free Shipping apply to all<br>orders over Tk 10000</p>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="tpproduct-details__condation-item d-flex align-items-center">
                              <div class="tpproduct-details__condation-thumb">
                                 <img src="{{url('/')}}/assets/theme/assets/img/icon/product-det-2.png" alt="" class="tpproduct-details__img-hover">
                              </div>
                              <div class="tpproduct-details__condation-text">
                                 <p>Guranteed 100% Organic<br>from natural farmas</p>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="tpproduct-details__condation-item d-flex align-items-center">
                              <div class="tpproduct-details__condation-thumb">
                                 <img src="{{url('/')}}/assets/theme/assets/img/icon/product-det-3.png" alt="" class="tpproduct-details__img-hover">
                              </div>
                              <div class="tpproduct-details__condation-text">
                                 <p>1 Day Returns if you change<br>your mind</p>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="tpproduct-details__condation-item d-flex align-items-center">
                              <div class="tpproduct-details__condation-thumb">
                                 <img src="{{url('/')}}/assets/theme/assets/img/icon/product-det-4.png" alt="" class="tpproduct-details__img-hover">
                              </div>
                              <div class="tpproduct-details__condation-text">
                                 <p>Covid-19 Info: We keep<br>delivering.</p>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- product-area-end -->

      <!-- product-details-area-start -->
      <div class="product-details-area">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="tpproduct-details__navtab mb-60">
                     <div class="tpproduct-details__nav mb-30">
                        <ul class="nav nav-tabs pro-details-nav-btn" id="myTabs" role="tablist">
                           <li class="nav-item" role="presentation">
                              <button class="nav-links active" id="home-tab-1" data-bs-toggle="tab" data-bs-target="#home-1" type="button" role="tab" aria-controls="home-1" aria-selected="true">Description</button>
                           </li>
                           <li class="nav-item" role="presentation">
                              <button class="nav-links" id="information-tab" data-bs-toggle="tab" data-bs-target="#additional-information" type="button" role="tab" aria-controls="additional-information" aria-selected="false">Additional information</button>
                           </li>
                           <li class="nav-item" role="presentation">
                              <button class="nav-links" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                           </li>
                        </ul>
                     </div>
                     <div class="tab-content tp-content-tab" id="myTabContent-2">
                        <div class="tab-para tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">
                           {!! $product->description !!}
                        </div>
                        <div class="tab-pane fade" id="additional-information" role="tabpanel"
                        aria-labelledby="information-tab">
                           <div class="product__details-info table-responsive">
                              <!-- <table class="table table-striped">
                                 <tbody>
                                    <tr>
                                       <td class="add-info">Weight</td>
                                       <td class="add-info-list"> 2 lbs</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Dimensions</td>
                                       <td class="add-info-list"> 12 × 16 × 19 in</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Product</td>
                                       <td class="add-info-list"> Purchase this product on rag-bone.com</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Color</td>
                                       <td class="add-info-list"> Gray, Black</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Size</td>
                                       <td class="add-info-list"> S, M, L, XL</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Model</td>
                                       <td class="add-info-list"> Model </td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Shipping</td>
                                       <td class="add-info-list"> Standard shipping: $5,95L</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Care Info</td>
                                       <td class="add-info-list"> Machine Wash up to 40ºC/86ºF Gentle Cycle</td>
                                    </tr>
                                    <tr>
                                       <td class="add-info">Brand</td>
                                       <td  class="add-info-list">  Kazen</td>
                                    </tr>
                                 </tbody>
                             </table> -->
                             {!! $product->product_specification !!}
                           </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">

                           <div class="product-details-review">
                              <h3 class="tp-comments-title mb-35">No review found on this product</h3>
                              {{--
                              <h3 class="tp-comments-title mb-35">3 reviews for “Wide Cotton Tunic extreme hammer”</h3>
                              <div class="latest-comments mb-55">
                                 <ul>
                                    <li>
                                       <div class="comments-box d-flex">
                                          <div class="comments-avatar mr-25">
                                             <img src="{{url('/')}}/assets/theme/assets/img/shop/reviewer-01.png" alt="">
                                          </div>
                                          <div class="comments-text">
                                             <div class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                <div class="avatar-name">
                                                   <b>Siarhei Dzenisenka</b>
                                                   <div class="comments-date mb-20">
                                                      <span>March 27, 2018 9:51 am</span>
                                                   </div>
                                                </div>
                                                <div class="user-rating">
                                                   <ul>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                   </ul>
                                                </div>
                                             </div>
                                             <p class="m-0">This is cardigan is a comfortable warm classic piece. Great to layer with a light top and you can dress up or down given the jewel buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                          </div>
                                       </div>
                                    </li>
                                    <li>
                                       <div class="comments-box d-flex">
                                          <div class="comments-avatar mr-25">
                                             <img src="{{url('/')}}/assets/theme/assets/img/shop/reviewer-02.png" alt="">
                                          </div>
                                          <div class="comments-text">
                                             <div class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                <div class="avatar-name">
                                                   <b>Tommy Jarvis </b>
                                                   <div class="comments-date mb-20">
                                                      <span>March 27, 2018 9:51 am</span>
                                                   </div>
                                                </div>
                                                <div class="user-rating">
                                                   <ul>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                   </ul>
                                                </div>
                                             </div>
                                             <p class="m-0">This is cardigan is a comfortable warm classic piece. Great to layer with a light top and you can dress up or down given the jewel buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                          </div>
                                       </div>
                                    </li>
                                    <li>
                                       <div class="comments-box d-flex">
                                          <div class="comments-avatar mr-25">
                                             <img src="{{url('/')}}/assets/theme/assets/img/shop/reviewer-03.png" alt="">
                                          </div>
                                          <div class="comments-text">
                                             <div class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                <div class="avatar-name">
                                                   <b>Johnny Cash</b>
                                                   <div class="comments-date mb-20">
                                                      <span>March 27, 2018 9:51 am</span>
                                                   </div>
                                                </div>
                                                <div class="user-rating">
                                                   <ul>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                      <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                   </ul>
                                                </div>
                                             </div>
                                             <p class="m-0">This is cardigan is a comfortable warm classic piece. Great to layer with a light top and you can dress up or down given the jewel buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                          </div>
                                       </div>
                                    </li>
                                 </ul>
                              </div>
                              <div class="product-details-comment">
                                 <div class="comment-title mb-20">
                                    <h3>Add a review</h3>
                                    <p>Your email address will not be published. Required fields are marked*</p>
                                 </div>
                                 <div class="comment-rating mb-20 d-flex">
                                    <span>Overall ratings</span>
                                    <ul>
                                       <li><a href="#"><i class="fas fa-star"></i></a></li>
                                       <li><a href="#"><i class="fas fa-star"></i></a></li>
                                       <li><a href="#"><i class="fas fa-star"></i></a></li>
                                       <li><a href="#"><i class="fas fa-star"></i></a></li>
                                       <li><a href="#"><i class="fal fa-star"></i></a></li>
                                    </ul>
                                 </div>
                                 <div class="comment-input-box">
                                    <form action="#">
                                       <div class="row">
                                          <div class="col-xxl-12">
                                             <div class="comment-input">
                                                <textarea placeholder="Your review..."></textarea>
                                             </div>
                                          </div>
                                          <div class="col-xxl-6">
                                             <div class="comment-input">
                                                <input type="text" placeholder="Your Name*">
                                             </div>
                                          </div>
                                          <div class="col-xxl-6">
                                             <div class="comment-input">
                                                <input type="email" placeholder="Your Email*">
                                             </div>
                                          </div>
                                          <div class="col-xxl-12">
                                             <div class="comment-submit">
                                                <button type="submit" class="tp-btn pro-submit">Submit</button>
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                              --}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- product-details-area-end -->

      <!-- related-product-area-start -->
      {{--
      <div class="related-product-area pt-65 pb-50 related-product-border">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-sm-6">
                  <div class="tpsection mb-40">
                     <h4 class="tpsection__title">Related Products</h4>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="tprelated__arrow d-flex align-items-center justify-content-end mb-40">
                     <div class="tprelated__prv"><i class="far fa-long-arrow-left"></i></div>
                     <div class="tprelated__nxt"><i class="far fa-long-arrow-right"></i></div>
                  </div>
               </div>
            </div>
            <div class="swiper-container related-product-active">
               <div class="swiper-wrapper">
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <a href="shop-details-2.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-1.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-2.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="shop-details.html">Miko Wooden Bluetooth Speaker</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <a href="shop-details.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-3.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-4.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="shop-details-2.html">Gorgeous Wooden Gloves</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <a href="shop-details-2.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-5.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-6.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="shop-details.html">Pinkol Enormous Granite Bottle</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <span class="tpproduct__thumb-topsall">On Sale</span>
                           <a href="shop-details-2.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-7.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-8.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="shop-details-2.html">Gorgeous Aluminum Table</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <a href="shop-details-2.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-9.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-10.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="shop-details.html">Evo Lightweight Granite Shirt</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                                 <span class="tpproduct__priceinfo-list-oldprice">$39.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="tpproduct pb-15 mb-30">
                        <div class="tpproduct__thumb p-relative">
                           <span class="tpproduct__thumb-volt"><i class="fas fa-bolt"></i></span>
                           <a href="shop-details-2.html">
                              <img src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-11.jpg" alt="product-thumb">
                              <img class="product-thumb-secondary" src="{{url('/')}}/assets/theme/assets/img/product/home-one/product-12.jpg" alt="">
                           </a>
                           <div class="tpproduct__thumb-action">
                              <a class="comphare" href="#"><i class="fal fa-exchange"></i></a>
                              <a class="quckview" href="#"><i class="fal fa-eye"></i></a>
                              <a class="wishlist" href="wishlist.html"><i class="fal fa-heart"></i></a>
                           </div>
                        </div>
                        <div class="tpproduct__content">
                           <h3 class="tpproduct__title"><a href="#">CLCo. Incredible Paper Car</a></h3>
                           <div class="tpproduct__priceinfo p-relative">
                              <div class="tpproduct__priceinfo-list">
                                 <span>$31.00</span>
                              </div>
                              <div class="tpproduct__cart">
                                 <a href="cart.html"><i class="fal fa-shopping-cart"></i>Add To Cart</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      --}}
      <!-- related-product-area-end -->
	</div>

@endsection

@section('custom_js')
<script>
   $(document).ready(function() {
      $('.cat-menu__category .category-menu').css('display', 'none');
   });
   $("#add-to-cart").on("click", function() {
      var url_data = $(this).data('cart-url');
      // alert(url_data);
      window.location.href = url_data;
   });
</script>
@endsection
