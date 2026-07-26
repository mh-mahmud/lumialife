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
                        <span>Blog Details</span>
                     </div>
                     <h2 class="tp-breadcrumb__title">Blog Details</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb-area-end -->
          
      <!-- postbox area start -->
      <div class="postbox-area pt-80 pb-60">
         <div class="container">
            <div class="row">
               <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
                  <div class="postbox__wrapper pr-20">
                     <article class="postbox__item format-image mb-50 transition-3">
                        <div class="postbox__thumb w-img mb-30">
                           <img src="{{ \App\Support\MediaStorage::url($blog->blog_image, 'blogs') }}" alt="">
                        </div>
                        <div class="postbox__content">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="postbox__content postbox__content-area mb-55">
                                    <div class="postbox__meta mb-15">
                                       <!-- <span><a href="#"><i class="fal fa-user-alt"></i> Alextina</a></span> -->
                                       <span><i class="fal fa-clock"></i> {{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                       <span><a href="#"><i class="far fa-comment-alt"></i> (0) Comments</a></span>
                                    </div>
                                    <h4 class="mb-35">
                                       {{ $blog->blog_name }}
                                    </h4>
                                    {!! $blog->blog_description !!} 
                                 </div>
                              </div>
                           </div>

                           <div class="postbox__tag-border">
                              <div class="row align-items-center">
                                 <div class="col-xl-7 col-md-12">
                                    <div class="postbox__tag">
                                       <!-- <div class="postbox__tag-list tagcloud">
                                          <span>Tag</span>
                                          <a href="blog.html">Furniture</a>
                                          <a href="blog.html">Table</a>
                                          <a href="blog.html">Fashion</a>
                                       </div> -->
                                    </div>
                                 </div>
                                 <div class="col-xl-5 col-md-12">
                                    <div class="postbox__social-tag">
                                       <span>Share</span>
                                       <a class="blog-d-lnkd" href="#"><i class="fab fa-linkedin-in"></i></a>
                                       <a class="blog-d-pin" href="#"><i class="fab fa-pinterest-p"></i></a>
                                       <a class="blog-d-fb" href="#"><i class="fab fa-facebook-f"></i></a>
                                       <a class="blog-d-tweet" href="#"><i class="fab fa-twitter"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </article>
                     <div class="postbox__comment mb-65">
                        <h3 class="postbox__comment-title">(0) Comment</h3>
                        {{--
                        <ul>
                           <li>
                              <div class="postbox__comment-box d-flex">
                                 <div class="postbox__comment-info">
                                    <div class="postbox__comment-avater mr-25">
                                       <img src="assets/img/blog/comments/comment-1.jpg" alt="">
                                    </div>
                                 </div>
                                 <div class="postbox__comment-text">
                                    <div class="postbox__comment-name">
                                       <h5>Kristin Watson</h5>
                                       <span class="post-meta">MARCH 10, 2020</span>
                                    </div>
                                    <p>Patient Comments are a collection of comments submitted by viewers in response to <br> a question posed by a MedicineNet doctor.</p>
                                    <div class="postbox__comment-reply">
                                       <a href="#"><i class="fas fa-reply-all"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </li>
                           <li class="children mb-30">
                              <div class="postbox__comment-box d-flex">
                                 <div class="postbox__comment-info">
                                    <div class="postbox__comment-avater mr-25">
                                       <img src="assets/img/blog/comments/comment-2.jpg" alt="">
                                    </div>
                                 </div>
                                 <div class="postbox__comment-text">
                                    <div class="postbox__comment-name">
                                       <h5>Brooklyn Simmons</h5>
                                       <span class="post-meta">MARCH 10, 2020</span>
                                    </div>
                                    <p>Include anecdotal examples of your experience, or things you took notice of that you <br> feel others would find useful.</p>
                                    <div class="postbox__comment-reply">
                                       <a href="#"><i class="fas fa-reply-all"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        --}}
                     </div>
                     <div class="postbox__comment-form">
                        <h3 class="postbox__comment-form-title">Leave a Reply</h3>
                        <p>Your email address will not be published. Required fields are marked *</p>
                        <form action="" method="POST">
                           <div class="row">
                              <div class="col-xxl-6 col-xl-6 col-lg-6">
                                 <div class="postbox__comment-input">
                                    <input name="name" required type="text" placeholder="Enter your Name">
                                 </div>
                              </div>
                              <div class="col-xxl-6 col-xl-6 col-lg-6">
                                 <div class="postbox__comment-input">
                                    <input name="email" required type="email" placeholder="Enter your email">
                                 </div>
                              </div>
                              <div class="col-xxl-6 col-xl-6 col-lg-6">
                                 <div class="postbox__comment-input">
                                    <input name="phone_number" required type="text" placeholder="Enter your number">
                                 </div>
                              </div>
                              <div class="col-xxl-6 col-xl-6 col-lg-6">
                                 <div class="postbox__comment-input">
                                    <input type="text" placeholder="Enter your website">
                                 </div>
                              </div>
                              <div class="col-xxl-12">
                                 <div class="postbox__comment-input">
                                    <textarea name="comment" required placeholder="Type your comment"></textarea>
                                 </div>
                              </div>
                              <div class="col-xxl-12">
                                 <div class="postbox__comment-btn ">
                                    <button type="submit" class="tp-color-btn tp-btn banner-animation">Post Comment</button>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
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
