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
                        <span>Career</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Career Page</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->
          
      <!-- postbox area start -->
      <div class="postbox-area pt-80 pb-30">
         <div class="container">
            <div class="row">
               <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
                  <div class="postbox pr-20 pb-50">

                  	@foreach($careers as $career)
                     <article class="postbox__item format-image mb-60 transition-3">
                        <div class="postbox__thumb w-img mb-25">
                           <a href="{{ route('career-details', $career->id) }}">
                              <img src="{{ \App\Support\MediaStorage::url($career->job_image, 'careers') }}" alt="blog-thumg">
                           </a>
                        </div>
                        <div class="postbox__content">
                           <div class="postbox__meta mb-15">
                              <!-- <span><a href="#"><i class="fal fa-user-alt"></i> Alextina</a></span> -->
                              <span><i class="fal fa-clock"></i> {{ date('d M Y', strtotime($career->created_at)) }}</span>
                              <span><a href="#"><i class="far fa-comment-alt"></i> (0) Comments</a></span>
                           </div>
                           <h3 class="postbox__title mb-20">
                              <a href="{{ route('career-details', $career->id) }}">{{ $career->job_title }}</a>
                           </h3>
                           <div class="postbox__text mb-30">
                              
                           </div>
                           <div class="postbox__read-more">
                              <a href="{{ route('career-details', $career->id) }}" class="tp-btn tp-color-btn banner-animation">Reade More</a>                               
                           </div>
                        </div>
                     </article>
                    @endforeach

                     <div class="basic-pagination">
                        <nav>
                           @include('components.front_pagination', ['paginator' => $careers])
                         </nav>
                     </div>
                  </div>
               </div>

               {{--
               <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-12">
                  <div class="sidebar__wrapper pl-25 pb-50">
                     <div class="sidebar__widget mb-45">
                        <div class="sidebar__widget-content">
                           <h3 class="sidebar__widget-title mb-25">Search</h3>
                           <div class="sidebar__search">
                              <form action="#">
                                 <div class="sidebar__search-input-2 p-relative">
                                    <input type="text" placeholder="Search post">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="sidebar__widget mb-40">
                        <h3 class="sidebar__widget-title mb-25">Category</h3>
                        <div class="sidebar__widget-content">
                           <ul>
                              <li><a href="#">Chemistry<span>03</span></a></li>
                              <li><a href="#">Forensic science <span>07</span></a></li>
                              <li><a href="#">Gemological <span>09</span></a></li>
                              <li><a href="#">COvid Analysis <span>01</span></a></li>
                              <li><a href="#">Becteriology <span>00</span></a></li>
                              <li><a href="#">Angiosperm <span>26</span></a></li>
                           </ul>
                        </div>
                     </div>
                     {{--
                     <div class="sidebar__widget mb-55">
                        <h3 class="sidebar__widget-title mb-25">Recent Post</h3>
                        <div class="sidebar__widget-content">
                           <div class="sidebar__post rc__post">
                              <div class="rc__post mb-20 d-flex align-items-center">
                                 <div class="rc__post-thumb">
                                    <a href="blog-details.html"><img src="assets/img/blog/blog-in-01.jpg" alt="blog-sidebar"></a>
                                 </div>
                                 <div class="rc__post-content">
                                    <div class="rc__meta">
                                       <span>4 March. 2022</span>
                                    </div>
                                    <h3 class="rc__post-title">
                                       <a href="blog-details.html">Don't Underestimate Tree for Furniture</a>
                                    </h3>
                                 </div>
                              </div>
                              <div class="rc__post mb-20 d-flex align-items-center">
                                 <div class="rc__post-thumb">
                                    <a href="blog-details.html"><img src="assets/img/blog/sidebar/blog-sm-2.jpg" alt="blog-sidebar"></a>
                                 </div>
                                 <div class="rc__post-content">
                                    <div class="rc__meta">
                                       <span>12 February. 2022</span>
                                    </div>
                                    <h3 class="rc__post-title">
                                       <a href="blog-details.html">Equipping Researchers in the Developing World</a>
                                    </h3>
                                 </div>
                              </div>
                              <div class="rc__post mb-20 d-flex align-items-center">
                                 <div class="rc__post-thumb">
                                    <a href="blog-details.html"><img src="assets/img/blog/sidebar/blog-sm-3.jpg" alt="blog-sidebar"></a>
                                 </div>
                                 <div class="rc__post-content">
                                    <div class="rc__meta">
                                       <span>14 January. 2022</span>
                                    </div>
                                    <h3 class="rc__post-title">
                                       <a href="blog-details.html">Things to do before shopping</a>
                                    </h3>
                                 </div>
                              </div>
                              <div class="rc__post d-flex align-items-center">
                                 <div class="rc__post-thumb">
                                    <a href="blog-details.html"><img src="assets/img/blog/sidebar/blog-sm-4.jpg" alt="blog-sidebar"></a>
                                 </div>
                                 <div class="rc__post-content">
                                    <div class="rc__meta">
                                       <span>18 March. 2021</span>
                                    </div>
                                    <h3 class="rc__post-title">
                                       <a href="blog-details.html">Research And Verify of a Quality Product</a>
                                    </h3>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     --}}
                     <div class="sidebar__widget mb-55">
                        <h3 class="sidebar__widget-title mb-25">Popular Tag</h3>
                        <div class="sidebar__widget-content">
                           <div class="tagcloud">
                              <a href="#">Furniture</a>
                              <a href="#">Table</a>
                              <a href="#">Chair</a>
                              <a href="#">Cloths</a>
                              <a href="#">Toy</a>
                              <a href="#">Suit</a>
                              <a href="#">T-Shirt </a>
                              <a href="#">Dress</a>
                              <a href="#">Wooden</a>
                              <a href="#">Clock</a>
                              <a href="#">Craft</a>
                              <a href="#">Gift</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               --}}
            </div>
         </div>
      </div>
      <!-- postbox area end -->
</div>

@endsection
@section('custom_js')
   <script type="text/javascript">
      $(document).ready(function() {
         $('.cat-menu__category .category-menu').css('display', 'none');
      })
   </script>
@endsection
