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
	                <span>Login Page</span>
	             </div>
	             <h2 class="tp-breadcrumb__title">Log In</h2>
	          </div>
	       </div>
	    </div>
	 </div>
	</section>
	<!-- breadcrumb-area-end -->
	  
	<!-- track-area-start -->
	<section class="track-area pt-80 pb-40">
	 <div class="container">
	    <div class="row justify-content-center">
	       <div class="col-lg-6 col-sm-12">

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

	          <div class="tptrack__product mb-40">
	             <div class="tptrack__thumb">
	                <img src="{{url('/')}}/assets/theme/assets/img/banner/full-banner.png" alt="">
	             </div>
	             <div class="tptrack__content grey-bg-3">
	                <div class="tptrack__item d-flex mb-20">
	                   <div class="tptrack__item-icon">
	                      <img src="{{url('/')}}/assets/theme/assets/img/icon/lock.png" alt="">
	                   </div>
	                   <div class="tptrack__item-content">
	                      <h4 class="tptrack__item-title">Login Here</h4>
	                      <p>Your personal data will be used to support your experience throughout this website, to manage access to your account.</p>
	                   </div>
	                </div>

	                <form action="{{ route('user.login.post') }}" method="POST">
	                	@csrf

		                <div class="tptrack__id mb-10">
		                    <span><i class="fal fa-phone"></i></span>
		                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="Phone Number">
                            @if ($errors->has('phone_number'))
                                <div class="text-danger">{{ $errors->first('phone_number') }}</div>
                            @endif
		                </div>

		                <div class="tptrack__email mb-10">
		                    <span><i class="fal fa-key"></i></span>
		                    <input type="password" name="password" required placeholder="Password">
                            @if ($errors->has('password'))
                                <div class="text-danger">{{ $errors->first('password') }}</div>
                            @endif
		                </div>
		                <div class="tpsign__account">
		                   <a href="{{ route('user-register') }}">Not a user? Create an Account?</a>
		                </div>
		                <div class="tptrack__btn">
		                   <button class="tptrack__submition">Login Now<i class="fal fa-long-arrow-right"></i></button>
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