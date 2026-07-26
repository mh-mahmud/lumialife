@extends('front.feb.layouts.master')

@section('title')
    Home
@endsection

@section('content')
    <div class="wrapper-div">
        <div class="container"></div>
        <div class="container-fluid">
            <div id="homepage">
                <section class="video-hero" aria-label="Featured collection">
                    <video class="video-hero-media" autoplay muted loop playsinline preload="metadata">
                        <source src="{{ asset('feb/img/hero-video.mp4') }}" type="video/mp4">
                    </video>
                    <div class="video-hero-shade" aria-hidden="true"></div>
                    <a class="video-hero-button" href="{{ route('shop-new') }}">SHOP NOW</a>
                </section>

                @if ($featuredCategories->isNotEmpty())
                    <section class="featured-category-section" aria-labelledby="featuredCategoryTitle">
                        <h2 class="featured-category-title" id="featuredCategoryTitle">SHOP BY CATEGORY</h2>
                        <div class="featured-category-grid">
                            @foreach ($featuredCategories as $featuredCategory)
                                @php
                                    $featuredCategoryValue = $featuredCategory->category_slug ?: $featuredCategory->id;
                                    $featuredCategoryImage = $featuredCategory->category_image
                                        ? \App\Support\MediaStorage::url($featuredCategory->category_image, 'categories')
                                        : asset('uploads/blank.png');
                                @endphp
                                <a class="featured-category-card"
                                    href="{{ route('shop-new', ['category' => $featuredCategoryValue]) }}">
                                    <img src="{{ $featuredCategoryImage }}"
                                        alt="{{ $featuredCategory->category_name }}" loading="lazy">
                                    <span>{{ strtoupper($featuredCategory->category_name) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($homePromoCategory)
                    @php
                        $homePromoCategoryValue = $homePromoCategory->category_slug ?: $homePromoCategory->id;
                        $homePromoCategoryImage = \App\Support\MediaStorage::url(
                            $homePromoCategory->category_image,
                            'categories'
                        );
                    @endphp
                    <section class="home-category-promo" aria-label="{{ $homePromoCategory->category_name }}">
                        <div class="home-category-promo-image">
                            <img src="{{ $homePromoCategoryImage }}"
                                alt="{{ $homePromoCategory->category_name }}" loading="lazy">
                        </div>
                        <div class="home-category-promo-content">
                            <span class="home-category-promo-mark" aria-hidden="true">
                                <i class="fa fa-diamond"></i>
                            </span>
                            <h2>{{ strtoupper($homePromoCategory->category_name) }}</h2>
                            <a href="{{ route('shop-new', ['category' => $homePromoCategoryValue]) }}">Shop Now</a>
                        </div>
                    </section>
                @endif

                <div class="row hero-slider-row">
                    <div class="hero-slider">
                        @foreach ($sliders as $slider)
                            <div class="hero-slide">
                                <img src="{{ \App\Support\MediaStorage::url($slider->slider_image, 'sliders') }}"
                                    alt="{{ $slider->slider_title }}" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="body-menu">
                    @php
                        $sliderBottomMenuItemWidth = 100 / max($sliderBottomCategories->count() + 1, 1);
                    @endphp
                    <div class="body-menu-item slider-category-menu">
                        <div class="skew" style="width: {{ $sliderBottomMenuItemWidth }}%;">
                            <a class="no-style-link unskew" href="{{ route('shop-new') }}">SHOP NOW</a>
                        </div>
                        @foreach ($sliderBottomCategories as $sliderBottomCategory)
                            @php
                                $sliderBottomCategoryValue =
                                    $sliderBottomCategory->category_slug ?: $sliderBottomCategory->id;
                            @endphp
                            <div style="width: {{ $sliderBottomMenuItemWidth }}%;">
                                <a class="no-style-link"
                                    href="{{ route('shop-new', ['category' => $sliderBottomCategoryValue]) }}">
                                    {{ strtoupper($sliderBottomCategory->category_name) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- <div class="home-element" style="margin-top: 7px">
                <a href="/corporate">
                    <div class="row" style="background: #e0f7fa; color: #000">
                        <div class="col-lg-12">
                            <div style="padding: 0px 15px; margin-left: 10px">
                                <strong>Event T-shirt
                                    <i class="fa fa-caret-right" aria-hidden="true"></i></strong>&nbsp;
                                <span>
                                    T-shirt/Clothing with your brand logo or design? We are
                                    delivering worldwide at unbeatable prices.
                                    <strong>Click here
                                        <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div> --}}


            </div>

            <div class="home-content">
                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="campaign shoptop">
                                <a class="hot-container-link mindfordesign"
                                    href="{{ route('shop-new', ['collection' => 'new-collection']) }}">
                                    <div class="hot-image-title light">
                                        <div class="hot-topic">New Collection</div>
                                    </div>

                                </a>
                            </div>
                            <div class="text-center" style="margin-top: 10px;">
                                <a class="see-more-link"
                                    href="{{ route('shop-new', ['collection' => 'new-collection']) }}">See
                                    More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>

                        </a>
                    </div>

                </div>
            </div>

            <div class="home-element">
                <div class="row">
                    <div class="col-md-12 ">

                        <div class="your-class">
                            @forelse($newProducts as $product)
                                @php
                                    $hasDiscount =
                                        $product->discount_price > 0 &&
                                        $product->discount_price < $product->product_value;
                                    $discountPercent = $hasDiscount
                                        ? round(
                                            (($product->product_value - $product->discount_price) /
                                                $product->product_value) *
                                                100,
                                        )
                                        : 0;
                                @endphp
                                <div>
                                    <a class="product-link" href="{{ route('single-product', $product->slug) }}">
                                        <div class="home-product">
                                            @if ($hasDiscount)
                                                <span class="home-discount-badge">{{ $discountPercent }}% OFF</span>
                                            @endif
                                            @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                                <span class="home-stock-out-badge">STOCK OUT</span>
                                            @endif
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                alt="{{ $product->name }}" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    {{ $product->name }}
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}</strong>
                                                    @if ($hasDiscount)
                                                        <strike>{{ $febCurrency->format($product->product_value) }}</strike>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center">No new products available.</div>
                            @endforelse

                            @if (false)
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67285487491ac-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Sensational"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Sensational
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 780.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Mevarick"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Mevarick
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Single Jersey Knitted Cotton Polo - Light Coffee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Single Jersey Knitted Cotton Polo - Light Coffee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 750.00</strong>
                                                    <strike>৳ 980.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Premium Limited Edition Polo - Regardz" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Limited Edition Polo - Regardz
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1293.00</strong>
                                                    <strike>৳ 1700.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Phenomenal"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Phenomenal
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Woodlight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo- Starlit"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo- Starlit
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66614d348624a-square.jpg') }}"
                                                alt="Womens Premium Co-ords- Gracia" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Co-ords- Gracia
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2100.00</strong>
                                                    <strike>৳ 3090.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69722a3a15ee7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Elizarna" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Elizarna
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1640.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67fbe00c309e8-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Niyara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Niyara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1250.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67bff135dd08a-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Zivara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Zivara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1300.00</strong>
                                                    <strike>৳ 1600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69d5da3bddb9d-square.jpg') }}"
                                                alt="Womens Premium Kurti - Sylis" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Sylis
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 990.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69e61281da4a7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Petra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Petra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1290.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/683ab7113fa55-square.jpg') }}"
                                                alt="Teen’s Premium Co-Ords - Luxarish" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen’s Premium Co-Ords - Luxarish
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2150.00</strong>
                                                    <strike>৳ 2600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Dastan" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Dastan
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2790.00</strong>
                                                    <strike>৳ 3600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/6992dd5635041-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Zulfiqra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Zulfiqra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3900.00</strong>
                                                    <strike>৳ 4800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/699a9daa4944e-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kafelaa" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kafelaa
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2990.00</strong>
                                                    <strike>৳ 3800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b7a3c4adc16-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Qasr" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Qasr
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3290.00</strong>
                                                    <strike>৳ 4500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kashaba" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kashaba
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2490.00</strong>
                                                    <strike>৳ 3000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67dba9546f2b0-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Jafnahee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Jafnahee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3250.00</strong>
                                                    <strike>৳ 4000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Fiddah" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Fiddah
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2890.00</strong>
                                                    <strike>৳ 3500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans -Apex [Dark]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Apex [Dark]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2350.00</strong>
                                                    <strike>৳ 2900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Midnight" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Midnight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  -  Skyline" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Skyline
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Indigo" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Indigo
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans  - Jet Black" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Jet Black
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Mens Denim Jeans -Predeor [Light]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Predeor [Light]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1950.00</strong>
                                                    <strike>৳ 2500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans - Pacific Blue" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Pacific Blue
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2190.00</strong>
                                                    <strike>৳ 2850.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Kinetic" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Kinetic
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Rugger" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Rugger
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Sports Edition Shorts - Aquamarine" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Sports Edition Shorts - Aquamarine
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 590.00</strong>
                                                    <strike>৳ 900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Strike" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Strike
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kid&#039;s Premium T-shirt - Whisker" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kid&#039;s Premium T-shirt - Whisker
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Ironman" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Ironman
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Dinoride" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Dinoride
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Kids Premium T-Shirt - Witty" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Witty
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

            <div class="home-content">
                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="campaign shoptop">
                                <a class="hot-container-link mindfordesign"
                                    href="{{ route('shop-new', ['collection' => 'trending']) }}">
                                    <div class="hot-image-title light">
                                        <div class="hot-topic">Trending Now</div>
                                    </div>

                                </a>
                            </div>
                            <div class="text-center" style="margin-top: 10px;">
                                <a class="see-more-link" href="{{ route('shop-new', ['collection' => 'trending']) }}">See
                                    More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>

                        </a>
                    </div>

                </div>
            </div>

            <div class="home-element">
                <div class="row">
                    <div class="col-md-12 ">

                        <div class="your-class">
                            @forelse($trendingProducts as $product)
                                @php
                                    $hasDiscount =
                                        $product->discount_price > 0 &&
                                        $product->discount_price < $product->product_value;
                                    $discountPercent = $hasDiscount
                                        ? round(
                                            (($product->product_value - $product->discount_price) /
                                                $product->product_value) *
                                                100,
                                        )
                                        : 0;
                                @endphp
                                <div>
                                    <a class="product-link" href="{{ route('single-product', $product->slug) }}">
                                        <div class="home-product">
                                            @if ($hasDiscount)
                                                <span class="home-discount-badge">{{ $discountPercent }}% OFF</span>
                                            @endif
                                            @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                                <span class="home-stock-out-badge">STOCK OUT</span>
                                            @endif
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                alt="{{ $product->name }}" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">{{ $product->name }}</div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}</strong>
                                                    @if ($hasDiscount)
                                                        <strike>{{ $febCurrency->format($product->product_value) }}</strike>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center">No trending products available.</div>
                            @endforelse

                            @if (false)
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67285487491ac-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Sensational"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Sensational
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 780.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Mevarick"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Mevarick
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Single Jersey Knitted Cotton Polo - Light Coffee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Single Jersey Knitted Cotton Polo - Light Coffee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 750.00</strong>
                                                    <strike>৳ 980.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Premium Limited Edition Polo - Regardz" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Limited Edition Polo - Regardz
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1293.00</strong>
                                                    <strike>৳ 1700.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Phenomenal"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Phenomenal
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Woodlight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo- Starlit"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo- Starlit
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66614d348624a-square.jpg') }}"
                                                alt="Womens Premium Co-ords- Gracia" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Co-ords- Gracia
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2100.00</strong>
                                                    <strike>৳ 3090.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69722a3a15ee7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Elizarna" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Elizarna
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1640.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67fbe00c309e8-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Niyara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Niyara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1250.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67bff135dd08a-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Zivara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Zivara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1300.00</strong>
                                                    <strike>৳ 1600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69d5da3bddb9d-square.jpg') }}"
                                                alt="Womens Premium Kurti - Sylis" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Sylis
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 990.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69e61281da4a7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Petra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Petra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1290.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/683ab7113fa55-square.jpg') }}"
                                                alt="Teen’s Premium Co-Ords - Luxarish" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen’s Premium Co-Ords - Luxarish
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2150.00</strong>
                                                    <strike>৳ 2600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Dastan" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Dastan
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2790.00</strong>
                                                    <strike>৳ 3600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/6992dd5635041-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Zulfiqra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Zulfiqra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3900.00</strong>
                                                    <strike>৳ 4800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/699a9daa4944e-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kafelaa" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kafelaa
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2990.00</strong>
                                                    <strike>৳ 3800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b7a3c4adc16-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Qasr" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Qasr
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3290.00</strong>
                                                    <strike>৳ 4500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kashaba" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kashaba
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2490.00</strong>
                                                    <strike>৳ 3000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67dba9546f2b0-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Jafnahee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Jafnahee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3250.00</strong>
                                                    <strike>৳ 4000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Fiddah" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Fiddah
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2890.00</strong>
                                                    <strike>৳ 3500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans -Apex [Dark]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Apex [Dark]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2350.00</strong>
                                                    <strike>৳ 2900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Midnight" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Midnight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  -  Skyline" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Skyline
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Indigo" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Indigo
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans  - Jet Black" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Jet Black
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Mens Denim Jeans -Predeor [Light]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Predeor [Light]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1950.00</strong>
                                                    <strike>৳ 2500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans - Pacific Blue" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Pacific Blue
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2190.00</strong>
                                                    <strike>৳ 2850.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Kinetic" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Kinetic
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Rugger" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Rugger
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Sports Edition Shorts - Aquamarine" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Sports Edition Shorts - Aquamarine
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 590.00</strong>
                                                    <strike>৳ 900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Strike" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Strike
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kid&#039;s Premium T-shirt - Whisker" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kid&#039;s Premium T-shirt - Whisker
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Ironman" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Ironman
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Dinoride" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Dinoride
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Kids Premium T-Shirt - Witty" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Witty
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

            <div class="home-content">
                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="campaign shoptop">
                                <a class="hot-container-link mindfordesign"
                                    href="{{ route('shop-new', ['collection' => 'lifestyle']) }}">
                                    <div class="hot-image-title light">
                                        <div class="hot-topic">LifeStyle Accessories</div>
                                    </div>

                                </a>
                            </div>
                            <div class="text-center" style="margin-top: 10px;">
                                <a class="see-more-link"
                                    href="{{ route('shop-new', ['collection' => 'lifestyle']) }}">See
                                    More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>

                        </a>
                    </div>

                </div>
            </div>

            <div class="home-element">
                <div class="row">
                    <div class="col-md-12 ">

                        <div class="your-class">
                            @forelse($lifestyleProducts as $product)
                                @php
                                    $hasDiscount =
                                        $product->discount_price > 0 &&
                                        $product->discount_price < $product->product_value;
                                    $discountPercent = $hasDiscount
                                        ? round(
                                            (($product->product_value - $product->discount_price) /
                                                $product->product_value) *
                                                100,
                                        )
                                        : 0;
                                @endphp
                                <div>
                                    <a class="product-link" href="{{ route('single-product', $product->slug) }}">
                                        <div class="home-product">
                                            @if ($hasDiscount)
                                                <span class="home-discount-badge">{{ $discountPercent }}% OFF</span>
                                            @endif
                                            @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                                <span class="home-stock-out-badge">STOCK OUT</span>
                                            @endif
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                alt="{{ $product->name }}" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">{{ $product->name }}</div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}</strong>
                                                    @if ($hasDiscount)
                                                        <strike>{{ $febCurrency->format($product->product_value) }}</strike>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center">No lifestyle products available.</div>
                            @endforelse

                            @if (false)
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67285487491ac-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Sensational"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Sensational
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 780.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Mevarick"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Mevarick
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Single Jersey Knitted Cotton Polo - Light Coffee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Single Jersey Knitted Cotton Polo - Light Coffee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 750.00</strong>
                                                    <strike>৳ 980.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Premium Limited Edition Polo - Regardz" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Limited Edition Polo - Regardz
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1293.00</strong>
                                                    <strike>৳ 1700.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Phenomenal"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Phenomenal
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Woodlight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo- Starlit"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo- Starlit
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66614d348624a-square.jpg') }}"
                                                alt="Womens Premium Co-ords- Gracia" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Co-ords- Gracia
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2100.00</strong>
                                                    <strike>৳ 3090.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69722a3a15ee7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Elizarna" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Elizarna
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1640.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67fbe00c309e8-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Niyara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Niyara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1250.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67bff135dd08a-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Zivara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Zivara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1300.00</strong>
                                                    <strike>৳ 1600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69d5da3bddb9d-square.jpg') }}"
                                                alt="Womens Premium Kurti - Sylis" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Sylis
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 990.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69e61281da4a7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Petra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Petra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1290.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/683ab7113fa55-square.jpg') }}"
                                                alt="Teen’s Premium Co-Ords - Luxarish" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen’s Premium Co-Ords - Luxarish
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2150.00</strong>
                                                    <strike>৳ 2600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Dastan" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Dastan
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2790.00</strong>
                                                    <strike>৳ 3600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/6992dd5635041-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Zulfiqra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Zulfiqra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3900.00</strong>
                                                    <strike>৳ 4800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/699a9daa4944e-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kafelaa" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kafelaa
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2990.00</strong>
                                                    <strike>৳ 3800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b7a3c4adc16-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Qasr" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Qasr
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3290.00</strong>
                                                    <strike>৳ 4500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kashaba" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kashaba
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2490.00</strong>
                                                    <strike>৳ 3000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67dba9546f2b0-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Jafnahee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Jafnahee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3250.00</strong>
                                                    <strike>৳ 4000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Fiddah" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Fiddah
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2890.00</strong>
                                                    <strike>৳ 3500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans -Apex [Dark]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Apex [Dark]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2350.00</strong>
                                                    <strike>৳ 2900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Midnight" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Midnight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  -  Skyline" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Skyline
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Indigo" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Indigo
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans  - Jet Black" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Jet Black
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Mens Denim Jeans -Predeor [Light]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Predeor [Light]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1950.00</strong>
                                                    <strike>৳ 2500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans - Pacific Blue" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Pacific Blue
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2190.00</strong>
                                                    <strike>৳ 2850.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Kinetic" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Kinetic
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Rugger" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Rugger
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Sports Edition Shorts - Aquamarine" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Sports Edition Shorts - Aquamarine
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 590.00</strong>
                                                    <strike>৳ 900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Strike" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Strike
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kid&#039;s Premium T-shirt - Whisker" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kid&#039;s Premium T-shirt - Whisker
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Ironman" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Ironman
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Dinoride" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Dinoride
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Kids Premium T-Shirt - Witty" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Witty
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

            {{-- <div class="home-element">
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e19d4b4-square.jpg') }}" alt="Half-Sleeve T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt">Half-Sleeve
                                    T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch%20%28Designer%20Edition%29&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b738e-square.jpg') }}" alt="Designer Short Sleeve" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch%20%28Designer%20Edition%29&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch">Designer
                                    Short Sleeve</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Sports&amp;refinementList%5Bcats%5D%5B1%5D=Sports%20%3E%20Sports%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/6388945749713-square.jpg') }}" alt="Sports T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Sports&amp;refinementList%5Bcats%5D%5B1%5D=Sports%20%3E%20Sports%20T-shirt">Sports
                                    T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Classic&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Printed">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4d642b-square.jpg') }}" alt="Polo" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Classic&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Printed">Polo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Cut%20%26%20Stitch">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4d7304-square.jpg') }}" alt="Cut &amp; Stitch Polo" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Cut%20%26%20Stitch">Cut
                                    &amp; Stitch Polo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Raglan&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Raglan%20%28Designer%20Edition%29">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638894573d172-square.jpg') }}" alt="Half Sleeve Raglan" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Raglan&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Raglan%20%28Designer%20Edition%29">Half
                                    Sleeve Raglan</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Blank">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e18818e-square.jpg') }}" alt="Half Sleeve Blanks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Blank">Half
                                    Sleeve Blanks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch%20%28Designer%20Edition%29&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4d360c-square.jpg') }}" alt="Designer Full Sleeve" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch%20%28Designer%20Edition%29&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Cut%20%26%20Stitch">Designer
                                    Full Sleeve</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Raglan&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Raglan%20%28Designer%20Edition%29">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638894f4efd88-square.png') }}" alt="Full Sleeve Raglan" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Raglan&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Raglan%20%28Designer%20Edition%29">Full
                                    Sleeve Raglan</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Blank">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/6388715fe282b-square.jpg') }}" alt="Full Sleeve Blanks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Full%20Sleeve%20T-shirt%20%3E%20Blank">Full
                                    Sleeve Blanks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Sports%20%3E%20Football%20Jersey">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e1aa1f4-square.jpg') }}" alt="Football Jerseys" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Sports%20%3E%20Football%20Jersey">Football
                                    Jerseys</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Hoodie">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b169a-square.jpg') }}" alt="Hoodie" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Hoodie">Hoodie</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Jacket">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b7222-square.jpg') }}" alt="Jacket" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Jacket">Jacket</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Shorts">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4ba04f-square.jpg') }}" alt="Mens Shorts" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Shorts">Mens
                                    Shorts</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Comfy%20Trouser">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e174be7-square.jpg') }}" alt="Mens Trousers" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Comfy%20Trouser">Mens
                                    Trousers</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Sports%20Trouser">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e19552a-square.jpg') }}" alt="Track Pants" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Sports%20Trouser">Track
                                    Pants</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Underwear">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b0dd2-square.jpg') }}" alt="Mens Undergarments" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Underwear">Mens
                                    Undergarments</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20T-Shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b7e0e-square.jpg') }}" alt="Womens T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20T-Shirt">Womens
                                    T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Comfy%20Trouser">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e16d560-square.jpg') }}" alt="Womens Trendy Pajamas" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Comfy%20Trouser">Womens
                                    Trendy Pajamas</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Polo%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4b7330-square.jpg') }}" alt="Kids Polo" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Polo%20T-shirt">Kids
                                    Polo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Design%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4d4651-square.jpg') }}" alt="Kids Half Sleeve T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Design%20T-shirt">Kids
                                    Half Sleeve T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Blank%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e18e8a0-square.jpg') }}" alt="Kids Half-Sleeve Blanks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Blank%20T-shirt">Kids
                                    Half-Sleeve Blanks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Maggie">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e1741aa-square.jpg') }}" alt="Kids Maggie T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Maggie">Kids
                                    Maggie T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Full%20Sleeve%20T-shirt">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e1a5fec-square.jpg') }}" alt="Kids Full Sleeve T-shirt" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Full%20Sleeve%20T-shirt">Kids
                                    Full Sleeve T-shirt</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Trouser&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Girls%20%3E%20Trouser">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e1a15bc-square.jpg') }}" alt="Kids Trousers" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Trouser&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Girls%20%3E%20Trouser">Kids
                                    Trousers</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Girls%20%3E%20shorts&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Boys%20%3E%20Shorts">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/61a794e1a33c0-square.jpg') }}" alt="Kids Shorts" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Girls%20%3E%20shorts&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Boys%20%3E%20Shorts">Kids
                                    Shorts</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Professional%207%20Layer%20Mask">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/63889c77163fb-square.jpg') }}" alt="Premium Cloth Masks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Professional%207%20Layer%20Mask">Premium
                                    Cloth Masks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Womens%20Embroidery%20Edition&amp;refinementList%5Bcats%5D%5B1%5D=Face%20Mask%20%3E%20Womens%20Designer%20Edition&amp;refinementList%5Bcats%5D%5B2%5D=Face%20Mask%20%3E%20Sports%20Edition">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/63889c771b9a0-square.jpg') }}" alt="Designer Edition Masks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Womens%20Embroidery%20Edition&amp;refinementList%5Bcats%5D%5B1%5D=Face%20Mask%20%3E%20Womens%20Designer%20Edition&amp;refinementList%5Bcats%5D%5B2%5D=Face%20Mask%20%3E%20Sports%20Edition">Designer
                                    Edition Masks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Kids%20Mask">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/63889c77196df-square.jpg') }}" alt="Kids Edition Masks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask%20%3E%20Kids%20Mask">Kids
                                    Edition Masks</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="flex-cat-block">
                        <div class="flex-cat-img">
                            <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Socks">
                                <img class="lazy" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('feb/image-gallery/638741f4bcae5-square.jpg') }}" alt="Premium Antibacterial Socks" loading="lazy" />
                            </a>
                            <div class="flex-cat-button">
                                <a href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Socks">Premium
                                    Antibacterial Socks</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

            @if ($homePageSetting?->banner_section_status)
                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <a class="product-link" href="{{ $homePageSetting->banner_one_url ?: '#' }}">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ $homePageSetting->assetUrl($homePageSetting->banner_one_image) }}"
                                        alt="Home banner one" loading="lazy" />
                                </div>
                                <div class="hero-link"></div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <a class="product-link" href="{{ $homePageSetting->banner_two_url ?: '#' }}">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ $homePageSetting->assetUrl($homePageSetting->banner_two_image) }}"
                                        alt="Home banner two" loading="lazy" />
                                </div>
                                <div class="hero-link"></div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($homePageSetting?->about_section_status)
                <div class="home-element">
                    <div class="row comfort-flex">
                        <div class="col-lg-8 col-sm-12">
                            <div class="comfort">
                                <div class="comfort-heading">
                                    {{ $homePageSetting->about_title }}
                                    <i style="color: #5cb85c" class="fa fa-angle-right" aria-hidden="true"></i>
                                </div>
                                <div class="comfort-subheading">
                                    {{ $homePageSetting->about_subtitle }}
                                </div>
                                <span>{{ $homePageSetting->about_description }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <a class="product-link" href="{{ $homePageSetting->about_url ?: '#' }}">
                                <div>
                                    <img src="{{ $homePageSetting->assetUrl($homePageSetting->about_image) }}"
                                        alt="{{ $homePageSetting->about_title }}" />
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!---- add new section ------->
            @if ($homePageSetting?->promo_section_status)
                <div class="home-element">
                    <div class="row no-gutters">

                        <div class="col-md-7 pr-md-2 mb-1 mb-md-0">
                            <a href="{{ $homePageSetting->promo_left_url ?: '#' }}" class="promo-card">

                                @if ($homePageSetting->promoMediaIsVideo($homePageSetting->promo_left_media))
                                    <video class="w-100" autoplay muted loop playsinline>
                                        <source
                                            src="{{ $homePageSetting->assetUrl($homePageSetting->promo_left_media) }}">
                                    </video>
                                @else
                                    <img src="{{ $homePageSetting->assetUrl($homePageSetting->promo_left_media) }}"
                                        class="img-fluid w-100" alt="Promo">
                                @endif

                                <div class="overlay">
                                    <div class="content">
                                        <h2>EXCLUSIVE</h2>
                                        <span>COLLECTION</span>
                                    </div>
                                </div>

                            </a>
                        </div>


                        <div class="col-md-5 pl-md-2">
                            <a href="{{ $homePageSetting->promo_right_url ?: '#' }}" class="promo-card">

                                @if ($homePageSetting->promoMediaIsVideo($homePageSetting->promo_right_media))
                                    <video class="w-100" autoplay muted loop playsinline>
                                        <source
                                            src="{{ $homePageSetting->assetUrl($homePageSetting->promo_right_media) }}">
                                    </video>
                                @else
                                    <img src="{{ $homePageSetting->assetUrl($homePageSetting->promo_right_media) }}"
                                        class="img-fluid w-100" alt="Promo">
                                @endif

                                <div class="overlay">
                                    <div class="content">
                                        <h2>EXCLUSIVE</h2>
                                        <span>COLLECTION</span>
                                    </div>
                                </div>

                            </a>
                        </div>

                    </div>
                </div>
            @endif



            <div class="home-content">
                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="campaign shoptop">
                                <a class="hot-container-link mindfordesign"
                                    href="{{ route('shop-new', ['collection' => 'best-deal']) }}">
                                    <div class="hot-image-title light">
                                        <div class="hot-topic">Best Deals</div>
                                    </div>

                                </a>
                            </div>
                            <div class="text-center" style="margin-top: 10px;">
                                <a class="see-more-link"
                                    href="{{ route('shop-new', ['collection' => 'best-deal']) }}">See
                                    More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>

                        </a>
                    </div>

                </div>
            </div>

            <div class="home-element">
                <div class="row">
                    <div class="col-md-12 ">

                        <div class="your-class best-deal-products-slider">
                            @forelse($bestDealProducts as $product)
                                @php
                                    $hasDiscount =
                                        $product->discount_price > 0 &&
                                        $product->discount_price < $product->product_value;
                                    $discountPercent = $hasDiscount
                                        ? round(
                                            (($product->product_value - $product->discount_price) /
                                                $product->product_value) *
                                                100,
                                        )
                                        : 0;
                                @endphp
                                <div>
                                    <a class="product-link" href="{{ route('single-product', $product->slug) }}">
                                        <div class="home-product">
                                            @if ($hasDiscount)
                                                <span class="home-discount-badge">{{ $discountPercent }}% OFF</span>
                                            @endif
                                            @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                                <span class="home-stock-out-badge">STOCK OUT</span>
                                            @endif
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                alt="{{ $product->name }}" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">{{ $product->name }}</div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}</strong>
                                                    @if ($hasDiscount)
                                                        <strike>{{ $febCurrency->format($product->product_value) }}</strike>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center">No best deal products available.</div>
                            @endforelse

                            @if (false)
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67285487491ac-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Sensational"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Sensational
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 780.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo - Mevarick"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo - Mevarick
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Single Jersey Knitted Cotton Polo - Light Coffee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Single Jersey Knitted Cotton Polo - Light Coffee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 750.00</strong>
                                                    <strike>৳ 980.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Premium Limited Edition Polo - Regardz" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Limited Edition Polo - Regardz
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1293.00</strong>
                                                    <strike>৳ 1700.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Phenomenal"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Phenomenal
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Premium Designer Edition Double PK Cotton Polo - Woodlight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1140.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Classical Edition Single Jersey Knitted Polo- Starlit"
                                                loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Classical Edition Single Jersey Knitted Polo- Starlit
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 890.00</strong>
                                                    <strike>৳ 1490.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66614d348624a-square.jpg') }}"
                                                alt="Womens Premium Co-ords- Gracia" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Co-ords- Gracia
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2100.00</strong>
                                                    <strike>৳ 3090.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69722a3a15ee7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Elizarna" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Elizarna
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1640.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67fbe00c309e8-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Niyara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Niyara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1250.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67bff135dd08a-square.jpg') }}"
                                                alt="Teen&#039;s Premium Tops - Zivara" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen&#039;s Premium Tops - Zivara
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1300.00</strong>
                                                    <strike>৳ 1600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69d5da3bddb9d-square.jpg') }}"
                                                alt="Womens Premium Kurti - Sylis" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Sylis
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 990.00</strong>
                                                    <strike>৳ 1500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69e61281da4a7-square.jpg') }}"
                                                alt="Womens Premium Kurti - Petra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Womens Premium Kurti - Petra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1290.00</strong>
                                                    <strike>৳ 1800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/683ab7113fa55-square.jpg') }}"
                                                alt="Teen’s Premium Co-Ords - Luxarish" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Teen’s Premium Co-Ords - Luxarish
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2150.00</strong>
                                                    <strike>৳ 2600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Dastan" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Dastan
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2790.00</strong>
                                                    <strike>৳ 3600.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/6992dd5635041-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Zulfiqra" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Zulfiqra
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3900.00</strong>
                                                    <strike>৳ 4800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/699a9daa4944e-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kafelaa" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kafelaa
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2990.00</strong>
                                                    <strike>৳ 3800.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b7a3c4adc16-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Qasr" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Qasr
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3290.00</strong>
                                                    <strike>৳ 4500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Kashaba" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Kashaba
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2490.00</strong>
                                                    <strike>৳ 3000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/67dba9546f2b0-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Jafnahee" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Jafnahee
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 3250.00</strong>
                                                    <strike>৳ 4000.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                                alt="Mens Premium Panjabi - Fiddah" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Premium Panjabi - Fiddah
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2890.00</strong>
                                                    <strike>৳ 3500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans -Apex [Dark]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Apex [Dark]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2350.00</strong>
                                                    <strike>৳ 2900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Midnight" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Midnight
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  -  Skyline" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Skyline
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Mens Denim Jeans  - Indigo" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Indigo
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans  - Jet Black" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Jet Black
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1690.00</strong>
                                                    <strike>৳ 2200.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                                alt="Mens Denim Jeans -Predeor [Light]" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans -Predeor [Light]
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 1950.00</strong>
                                                    <strike>৳ 2500.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Denim Jeans - Pacific Blue" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Denim Jeans - Pacific Blue
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 2190.00</strong>
                                                    <strike>৳ 2850.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Kinetic" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Kinetic
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Rugger" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Rugger
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 550.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Mens Sports Edition Shorts - Aquamarine" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Mens Sports Edition Shorts - Aquamarine
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 590.00</strong>
                                                    <strike>৳ 900.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Sports Edition Shorts - Strike" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Sports Edition Shorts - Strike
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 720.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kid&#039;s Premium T-shirt - Whisker" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kid&#039;s Premium T-shirt - Whisker
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Ironman" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Ironman
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 435.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                                alt="Kids Premium T-Shirt - Dinoride" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Dinoride
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <a class="product-link" href="{{ route('single-product') }}">
                                        <div class="home-product">
                                            <img class="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                                alt="Kids Premium T-Shirt - Witty" loading="lazy" />
                                            <div class="product-info">
                                                <div class="product-name">
                                                    Kids Premium T-Shirt - Witty
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <div>
                                                    <strong>৳ 470.00</strong>
                                                    <strike>৳ 620.00</strike>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

            @foreach ($displayCategories as $displayCategory)
                @php
                    $categoryProducts = $displayCategory->products;
                    $viewMoreProduct = $categoryProducts->get(7);
                    $viewMoreImage =
                        $viewMoreProduct && $viewMoreProduct->img_path
                            ? \App\Support\MediaStorage::url($viewMoreProduct->img_path, 'products')
                            : ($displayCategory->category_image
                                ? \App\Support\MediaStorage::url($displayCategory->category_image, 'categories')
                                : asset('uploads/blank.png'));
                @endphp
                <div class="home-element category-product-section">
                    <div class="category-showcase-grid">
                        <a class="category-feature-card"
                            href="{{ route('shop-new', ['category' => $displayCategory->category_slug]) }}">
                            <img class="lazy"
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                data-src="{{ \App\Support\MediaStorage::url($displayCategory->category_image, 'categories') }}"
                                alt="{{ $displayCategory->category_name }}" loading="lazy" />
                            <span>{{ $displayCategory->category_name }}</span>
                        </a>

                        @foreach ($categoryProducts->take(7) as $product)
                            @php
                                $hasDiscount =
                                    $product->discount_price > 0 && $product->discount_price < $product->product_value;
                                $discountPercent = $hasDiscount
                                    ? round(
                                        (($product->product_value - $product->discount_price) /
                                            $product->product_value) *
                                            100,
                                    )
                                    : 0;
                            @endphp
                            <a class="product-link category-grid-product"
                                href="{{ route('single-product', $product->slug) }}">
                                <div class="home-product">
                                    @if ($hasDiscount)
                                        <span class="home-discount-badge">{{ $discountPercent }}% OFF</span>
                                    @endif
                                    @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                        <span class="home-stock-out-badge">STOCK OUT</span>
                                    @endif
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                        alt="{{ $product->name }}" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}</strong>
                                            @if ($hasDiscount)
                                                <strike>{{ $febCurrency->format($product->product_value) }}</strike>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <a class="category-view-more"
                            href="{{ route('shop-new', ['category' => $displayCategory->category_slug]) }}">
                            <img class="lazy"
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                data-src="{{ $viewMoreImage }}"
                                alt="View more {{ $displayCategory->category_name }}" loading="lazy" />
                            <span>VIEW<br>MORE</span>
                        </a>
                    </div>
                </div>
            @endforeach

            @if (false)
                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4f8805a51e-square.jpg') }}"
                                        alt="Designer Polo" loading="lazy" />
                                </div>
                                <div class="hero-link">Designer Polo</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/67285487491ac-square.jpg') }}"
                                        alt="Classical Edition Single Jersey Knitted Polo - Sensational"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Classical Edition Single Jersey Knitted Polo -
                                            Sensational
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 780.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Classical Edition Single Jersey Knitted Polo - Mevarick" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Classical Edition Single Jersey Knitted Polo -
                                            Mevarick
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 890.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Classical Edition Single Jersey Knitted Polo- Starlit" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Classical Edition Single Jersey Knitted Polo- Starlit
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 890.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                        alt="Single Jersey Knitted Cotton Polo - Light Coffee" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Single Jersey Knitted Cotton Polo - Light Coffee
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 750.00</strong> <strike>৳ 980.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Premium Limited Edition Polo - Regardz" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Limited Edition Polo - Regardz
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1293.00</strong> <strike>৳ 1700.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Premium Designer Edition Double PK Cotton Polo - Phenomenal"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Designer Edition Double PK Cotton Polo -
                                            Phenomenal
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1140.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Designer Edition Double PK Cotton Polo -
                                            Woodlight
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1140.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Premium Designer Edition Double PK Cotton Polo - Magnificent"
                                        loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Designer Edition Double PK Cotton Polo -
                                            Magnificent
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1140.00</strong> <strike>৳ 1490.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Kurti%20Tunic%20And%20Tops">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67dc35b5a0fc5-square.png') }}"
                                        alt="Kurti, Tunic &amp; Tops" loading="lazy" />
                                </div>
                                <div class="hero-link">Kurti, Tunic &amp; Tops</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66614d348624a-square.jpg') }}"
                                        alt="Womens Premium Co-ords- Gracia" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Womens Premium Co-ords- Gracia
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2100.00</strong> <strike>৳ 3090.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69722a3a15ee7-square.jpg') }}"
                                        alt="Womens Premium Kurti - Elizarna" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Womens Premium Kurti - Elizarna
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1640.00</strong> <strike>৳ 1800.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/67fbe00c309e8-square.jpg') }}"
                                        alt="Teen&#039;s Premium Tops - Niyara" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Teen&#039;s Premium Tops - Niyara
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1250.00</strong> <strike>৳ 1500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/67bff135dd08a-square.jpg') }}"
                                        alt="Teen&#039;s Premium Tops - Zivara" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Teen&#039;s Premium Tops - Zivara
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1300.00</strong> <strike>৳ 1600.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69d5da3bddb9d-square.jpg') }}"
                                        alt="Womens Premium Kurti - Sylis" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Womens Premium Kurti - Sylis
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 990.00</strong> <strike>৳ 1500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69e61281da4a7-square.jpg') }}"
                                        alt="Womens Premium Kurti - Petra" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Womens Premium Kurti - Petra
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1290.00</strong> <strike>৳ 1800.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/683ab7113fa55-square.jpg') }}"
                                        alt="Teen’s Premium Co-Ords - Luxarish" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Teen’s Premium Co-Ords - Luxarish
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2150.00</strong> <strike>৳ 2600.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/67c954420d52f-square.jpg') }}"
                                        alt="Teen&#039;s Premium Tops - Vivia" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Kurti%20Tunic%20And%20Tops">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Teen&#039;s Premium Tops - Vivia
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1250.00</strong> <strike>৳ 1500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Classic">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cc879b9411e-square.jpg') }}"
                                        alt="Classic Polo" loading="lazy" />
                                </div>
                                <div class="hero-link">Classic Polo</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Cut%20%26%20Stitch&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Polo%20T-shirt%20%3E%20Printed">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cc879b94f3a-square.jpg') }}"
                                        alt="Designer Polo" loading="lazy" />
                                </div>
                                <div class="hero-link">Designer Polo</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Polo%20T-shirt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd0908bd-square.jpg') }}"
                                        alt="Kids Polo" loading="lazy" />
                                </div>
                                <div class="hero-link">Kids Polo</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Panjabi">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd094777-square.jpg') }}"
                                        alt="Panjabi" loading="lazy" />
                                </div>
                                <div class="hero-link">Panjabi</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Dastan" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Dastan
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2790.00</strong> <strike>৳ 3600.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6992dd5635041-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Zulfiqra" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Zulfiqra
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 3900.00</strong> <strike>৳ 4800.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/699a9daa4944e-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Kafelaa" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Kafelaa
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2990.00</strong> <strike>৳ 3800.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69b7a3c4adc16-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Qasr" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Qasr
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 3290.00</strong> <strike>৳ 4500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Kashaba" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Kashaba
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2490.00</strong> <strike>৳ 3000.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/67dba9546f2b0-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Jafnahee" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Jafnahee
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 3250.00</strong> <strike>৳ 4000.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Fiddah" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Fiddah
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2890.00</strong> <strike>৳ 3500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Premium Panjabi - Shafeza" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Panjabi">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Panjabi - Shafeza
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2490.00</strong> <strike>৳ 3000.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Pajama">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c51415305a6-square.jpg') }}"
                                        alt="Pajamas" loading="lazy" />
                                </div>
                                <div class="hero-link">Pajamas</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Sports%20Trouser">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cc87e4ecfe9-square.jpg') }}"
                                        alt="Sports Trousers" loading="lazy" />
                                </div>
                                <div class="hero-link">Sports Trousers</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Comfy%20Trouser">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cc87e4edca0-square.jpg') }}"
                                        alt="Comfy Trousers" loading="lazy" />
                                </div>
                                <div class="hero-link">Comfy Trousers</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Shorts%20%3E%20Chino%20Shorts">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Chino Shorts" loading="lazy" />
                                </div>
                                <div class="hero-link">Chino Shorts</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Cargo%20Pants">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Cargo Pants" loading="lazy" />
                                </div>
                                <div class="hero-link">Cargo Pants</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Chino%20Pants">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Chino Pants" loading="lazy" />
                                </div>
                                <div class="hero-link">Chino Pants</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Jeans">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Denim Jeans" loading="lazy" />
                                </div>
                                <div class="hero-link">Denim Jeans</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Denim Jeans -Apex [Dark]" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans -Apex [Dark]
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2350.00</strong> <strike>৳ 2900.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                        alt="Mens Denim Jeans  - Midnight" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans - Midnight
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1690.00</strong> <strike>৳ 2200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                        alt="Mens Denim Jeans  -  Skyline" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans - Skyline
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1690.00</strong> <strike>৳ 2200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                        alt="Mens Denim Jeans  - Indigo" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans - Indigo
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1690.00</strong> <strike>৳ 2200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Denim Jeans  - Jet Black" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans - Jet Black
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1690.00</strong> <strike>৳ 2200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/650182af2f2e1-square.jpeg') }}"
                                        alt="Mens Denim Jeans -Predeor [Light]" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans -Predeor [Light]
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1950.00</strong> <strike>৳ 2500.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Denim Jeans - Pacific Blue" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans - Pacific Blue
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 2190.00</strong> <strike>৳ 2850.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/69abac455ef41.jpg') }}"
                                        alt="Mens Denim Jeans -Apex [Light]" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Jeans">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Denim Jeans -Apex [Light]
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1990.00</strong> <strike>৳ 2600.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Shorts">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Mens Shorts" loading="lazy" />
                                </div>
                                <div class="hero-link">Mens Shorts</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Sports Edition Shorts - Kinetic" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Sports Edition Shorts - Kinetic
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 550.00</strong> <strike>৳ 720.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Sports Edition Shorts - Rugger" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Sports Edition Shorts - Rugger
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 550.00</strong> <strike>৳ 720.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Sports Edition Shorts - Aquamarine" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Sports Edition Shorts - Aquamarine
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 590.00</strong> <strike>৳ 900.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Sports Edition Shorts - Strike" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Sports Edition Shorts - Strike
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 435.00</strong> <strike>৳ 720.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Mens Sports Edition Shorts - Helios" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Shorts">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Sports Edition Shorts - Helios
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 590.00</strong> <strike>৳ 900.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Accesorries%20%3E%20Cap">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Cap" loading="lazy" />
                                </div>
                                <div class="hero-link">Cap</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Accesorries%20%3E%20Belt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Belt" loading="lazy" />
                                </div>
                                <div class="hero-link">Belt</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Accesorries%20%3E%20Wallet">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Wallet" loading="lazy" />
                                </div>
                                <div class="hero-link">Wallet</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Polo%20T-shirt&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Boys%20%3E%20Design%20T-shirt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67c4ecd090deb-square.jpg') }}"
                                        alt="Little ones tees" loading="lazy" />
                                </div>
                                <div class="hero-link">Little ones tees</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kid&#039;s Premium T-shirt - Whisker" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kid&#039;s Premium T-shirt - Whisker
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 470.00</strong> <strike>৳ 620.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium T-Shirt - Ironman" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium T-Shirt - Ironman
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 435.00</strong> <strike>৳ 620.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium T-Shirt - Dinoride" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium T-Shirt - Dinoride
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 470.00</strong> <strike>৳ 620.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/648f0baeaf70a-square.png') }}"
                                        alt="Kids Premium T-Shirt - Witty" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium T-Shirt - Witty
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 470.00</strong> <strike>৳ 620.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium T-Shirt - Croco" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium T-Shirt - Croco
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 470.00</strong> <strike>৳ 620.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium Polo T-Shirt - Pow Pow" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium Polo T-Shirt - Pow Pow
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 1200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium Polo T-Shirt - Wild Friends" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium Polo T-Shirt - Wild Friends
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 1200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Kids Premium Polo T-Shirt - Lets play" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Polo%20T-shirt&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Boys%20%3E%20Design%20T-shirt">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Kids Premium Polo T-Shirt - Lets play
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 1200.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="shoptop" style="min-height: 100px">
                                <a class="hot-container-link mindfordesign"
                                    href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Socks">
                                    <div class="hot-image-title light">
                                        <div class="hot-title">
                                            The Best Quality Socks you can find in Bangladesh
                                        </div>
                                        <div class="hot-topic">Premium Antibacterial Socks</div>
                                        <div class="hot-link">
                                            Visit Store&nbsp;&nbsp;&nbsp;>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Socks">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/1.jpg') }}" alt="Socks"
                                        loading="lazy" />
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/693945567d351-square.jpg') }}"
                                        alt="Premium Antibacterial Sports Socks - Playmaker (Antha-melange)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Sports Socks - Playmaker
                                            (Antha-melange)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 190.00</strong> <strike>৳ 330.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6939455cc3abe-square.png') }}"
                                        alt="Premium Antibacterial Sports Socks - Raven (Antha Melange)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Sports Socks - Raven (Antha
                                            Melange)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 190.00</strong> <strike>৳ 330.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/693945573952b-square.jpg') }}"
                                        alt="Premium Antibacterial Socks - Maroon" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Socks - Maroon
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 160.00</strong> <strike>৳ 250.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6939455737a2c-square.jpg') }}"
                                        alt="Premium Antibacterial Sports Socks - Playmaker (Grey Melange)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Sports Socks - Playmaker (Grey
                                            Melange)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 190.00</strong> <strike>৳ 330.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6556fba76a097-square.jpg') }}"
                                        alt="Premium Antibacterial Socks - Aerobics" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Socks - Aerobics
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 160.00</strong> <strike>৳ 250.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/659ec7ce127a4-square.jpg') }}"
                                        alt="Premium Antibacterial Socks - Regal" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Socks - Regal
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 190.00</strong> <strike>৳ 250.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/654deb180abce-square.jpg') }}"
                                        alt="Premium Antibacterial Socks - Zenith" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Socks - Zenith
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 160.00</strong> <strike>৳ 290.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/659ec7ce42a63-square.jpg') }}"
                                        alt="Premium Antibacterial Socks - Affluenza" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Mens%20%3E%20Socks">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Antibacterial Socks - Affluenza
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 190.00</strong> <strike>৳ 250.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Girls%20%3E%20Frock&amp;refinementList%5Bcats%5D%5B1%5D=Kids%20%3E%20Girls%20%3E%20Two%20Piece%20Set">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cb360ef2243-square.jpg') }}"
                                        alt="Frock" loading="lazy" />
                                </div>
                                <div class="hero-link">Frock</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Kids%20%3E%20Boys%20%3E%20Design%20T-shirt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cb360eec45f-square.jpg') }}"
                                        alt="T-shirts and Shorts" loading="lazy" />
                                </div>
                                <div class="hero-link">T-shirts and Shorts</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList[cats][0]=Kids%20%3E%20Boys%20%3E%20Panjabi">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67dc306c9872f-square.jpg') }}"
                                        alt="Panjabi" loading="lazy" />
                                </div>
                                <div class="hero-link">Panjabi</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20T-Shirt">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cbc262628da-square.jpg') }}"
                                        alt="Womens Designer T-shirts" loading="lazy" />
                                </div>
                                <div class="hero-link">Womens Designer T-shirts</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6571b0b9c97ae-square.jpg') }}"
                                        alt="Women Premium Tee - Herminie" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Herminie
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 750.00</strong> <strike>৳ 980.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/662122c3b1db0-square.jpg') }}"
                                        alt="Women Premium Tee - Fern" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">Women Premium Tee - Fern</div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 670.00</strong> <strike>৳ 880.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/677136bcd5618-square.jpg') }}"
                                        alt="Women Premium Tee - Sun Shine" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Sun Shine
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 670.00</strong> <strike>৳ 880.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/625c18053bb27-square.jpg') }}"
                                        alt="Women Premium Tee - Ornate " loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Ornate
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 670.00</strong> <strike>৳ 880.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/625c1805120b0-square.jpg') }}"
                                        alt="Women Premium Tee - Athena" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Athena
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 670.00</strong> <strike>৳ 880.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66eada34483f1-square.jpg') }}"
                                        alt="Womens Premium Polo - Femme" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Womens Premium Polo - Femme
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 750.00</strong> <strike>৳ 980.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66ca078469fa7-square.jpg') }}"
                                        alt="Women Premium Tee - Lush Life" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Lush Life
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 750.00</strong> <strike>৳ 980.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66ca0998262f1-square.jpg') }}"
                                        alt="Women Premium Tee - Modern Marven " loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20T-Shirt">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Women Premium Tee - Modern Marven
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 670.00</strong> <strike>৳ 880.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Pants">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cbc3f799744-square.jpg') }}"
                                        alt="Pants" loading="lazy" />
                                </div>
                                <div class="hero-link">Pants</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Pajamas&amp;refinementList%5Bcats%5D%5B1%5D=Womens%20%3E%20Palazzo">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cbc2625af58-square.jpg') }}"
                                        alt="Designer Pajamas" loading="lazy" />
                                </div>
                                <div class="hero-link">Designer Pajamas</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Womens%20%3E%20Comfy%20Trouser">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cbc2625af2d-square.jpg') }}"
                                        alt="Comfy Trousers" loading="lazy" />
                                </div>
                                <div class="hero-link">Comfy Trousers</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link"
                                href="/shop?refinementList%5Bcats%5D%5B0%5D=Sports%20%3E%20Sports%20T-shirt&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Sports">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/67cbc783cf923-square.jpg') }}"
                                        alt="Sports T-shirt" loading="lazy" />
                                </div>
                                <div class="hero-link">Sports T-shirt</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6526a0ac21c13-square.jpg') }}"
                                        alt="Argentina 2026 World Cup Home Jersey - Fan Edition (Fabrilife Original)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Argentina 2026 World Cup Home Jersey - Fan Edition
                                            (Fabrilife Original)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 870.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/683db934b64f2-square.jpg') }}"
                                        alt="Brazil 2026 World Cup Home Jersey - Player Edition" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Brazil 2026 World Cup Home Jersey - Player Edition
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 1290.00</strong> <strike>৳ 1600.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/650c5a9a10c63-square.jpg') }}"
                                        alt="England 2026 World Cup Home Jersey - Fan Edition (Fabrilife Original)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            England 2026 World Cup Home Jersey - Fan Edition
                                            (Fabrilife Original)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 870.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6a2541ec656cf-square.jpg') }}"
                                        alt="Cristiano Ronaldo CR7 Jersey - World Cup 2026 Fan Edition (Fabrilife Original)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Cristiano Ronaldo CR7 Jersey - World Cup 2026 Fan
                                            Edition (Fabrilife Original)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 749.00</strong> <strike>৳ 870.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6a02c4fc163f9-square.jpg') }}"
                                        alt="Mens Premium Sports Active Wear T-shirt - Snowdrift" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Sports Active Wear T-shirt - Snowdrift
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 570.00</strong> <strike>৳ 750.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6a2405312e0d6-square.jpg') }}"
                                        alt="Mens Premium Sports Active Wear T-shirt - Skylark" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Mens Premium Sports Active Wear T-shirt - Skylark
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 570.00</strong> <strike>৳ 750.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6832e7b9ea976-square.jpg') }}"
                                        alt="Germany 2026 World Cup Home Jersey - Fan Edition (Fabrilife Original)"
                                        loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Germany 2026 World Cup Home Jersey - Fan Edition
                                            (Fabrilife Original)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 870.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/6a265c51279d1-square.jpg') }}"
                                        alt="France 2026 World Cup Home Jersey - Fan Edition (Fabrilife Original)"
                                        loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Sports%20%3E%20Sports%20T-shirt&amp;refinementList%5Bcats%5D%5B1%5D=Mens%20%3E%20Half%20Sleeve%20T-shirt%20%3E%20Sports">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            France 2026 World Cup Home Jersey - Fan Edition
                                            (Fabrilife Original)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 690.00</strong> <strike>৳ 870.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="home-element">
                    <div class="row">
                        <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a class="product-link" href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask">
                                <div class="gallery-skeleton">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/image-gallery/638a77dcdd7bb-square.jpg') }}"
                                        alt="Certified Face Masks" loading="lazy" />
                                </div>
                                <div class="hero-link">Certified Face Masks</div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eb191ac092a4.jpg') }}"
                                        alt="Premium Cotton Face Mask (Deep Blue)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Deep Blue)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eb191ab6d7b7.jpg') }}"
                                        alt="Premium Cotton Face Mask (Maroon)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Maroon)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eec9a0dd8210.jpg') }}"
                                        alt="Premium Cotton Face Mask (Purple)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Purple)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/60d071a9712db-square.jpg') }}"
                                        alt="Premium Cotton Face Mask (Light Pink)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Light Pink)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eec9a0e1d59e.jpg') }}"
                                        alt="Premium Cotton Face Mask (Black)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Black)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eb191abaeec5.jpg') }}"
                                        alt="Premium Cotton Face Mask (Denim)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Denim)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eec9a0e17886.jpg') }}"
                                        alt="Premium Cotton Face Mask (Charcoal)" loading="lazy" />
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Charcoal)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <a class="product-link" href="{{ route('single-product') }}">
                                <div class="home-product">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/5eec9a0e17886.jpg') }}"
                                        alt="Premium Cotton Face Mask (Navy)" loading="lazy" />
                                    <a class="hot-container-link"
                                        href="/shop?refinementList%5Bcats%5D%5B0%5D=Face%20Mask">
                                        <div class="hot-image-title">
                                            <span>View</span><span>More</span>
                                        </div>
                                    </a>
                                    <div class="product-info">
                                        <div class="product-name">
                                            Premium Cotton Face Mask (Navy)
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <div>
                                            <strong>৳ 195.00</strong> <strike>৳ 350.00</strike>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- @if ($homePageSetting?->bulk_section_status)
        <div class="home-element">
            <a href="{{ $homePageSetting->bulk_url ?: '#' }}">
                <div class="row" style="background: #eaeaea; color: #000">
                    <div class="col-lg-5">
                        <div style="padding: 15px; margin-left: 10px">
                            <div class="comfort-subheading" style="color: #4a96d3">
                                {{ $homePageSetting->bulk_title }} &nbsp;<i style="color: #5cb85c" class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <span>{{ $homePageSetting->bulk_description }}</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div>
                            <img src="{{ $homePageSetting->assetUrl($homePageSetting->bulk_image) }}" alt="{{ $homePageSetting->bulk_title }}" />
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif --}}

            @if ($homePageSetting?->partners_section_status)
                <div class="row" style="margin-top: 60px">
                    <div class="col-lg-12" style="text-align: center">
                        <h3>{{ $homePageSetting->partners_title }}</h3>
                        <div style="line-height: 60px; font-size: 1.5rem">
                            {{ $homePageSetting->partners_subtitle }}
                        </div>
                        <p>{{ $homePageSetting->partners_description }}</p>
                    </div>
                </div>

                @include('front.feb.partials.partner-logos', ['setting' => $homePageSetting])
            @endif

            @if (false)
                <div class="container-fluid text-center" style="margin-top: 20px">
                    <div id="print_type_carousel" class="carousel-items" style="display: none">
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/1.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/2.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/3.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/4.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/5.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/6.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/7.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/8.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/9.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/10.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/11.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/12.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/13.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/14.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/15.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/16.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/17.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/18.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/19.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/20.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/21.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/22.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/23.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/24.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/25.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/26.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/27.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/28.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/29.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/30.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/31.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/32.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/33.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/34.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/35.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/36.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/37.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/38.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/39.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/40.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/41.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/42.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/43.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/44.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/45.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/46.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/47.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/48.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-block">
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/49.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/50.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/51.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/52.jpg') }}" alt="" />
                                </div>
                            </div>
                            <div class="carousel-row">
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/53.jpg') }}" alt="" />
                                </div>
                                <div class="carousel-col">
                                    <img src="{{ asset('feb/img/clients/54.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- <div class="home-element">
            <div class="row">
                <div class="col-lg-12 affliate_container">
                    <div>
                        <a class="affliate_link" href="/become-an-affiliate">
                            <div class="affiliate_invite">
                                <div class="affliate-title">EARN MONEY</div>
                                <div class="affliate-title-sub">JOIN FABRISQUAD</div>
                                <div class="affiliate-hot-link">
                                    <i style="color: #007bff" class="fa fa-check-circle" aria-hidden="true"></i>
                                    An Affiliate Program by Fabrilife
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>
    </div>
    </div>
    </div>

    <style>
        .home-discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 3;
            padding: 4px 9px;
            border-radius: 4px;
            background: #e53935;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
        }

        .home-stock-out-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
            padding: 4px 9px;
            border-radius: 4px;
            background: #222;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
        }

        .category-product-section {
            margin-top: 22px;
            padding-bottom: 12px;
        }

        .category-showcase-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            grid-template-rows: repeat(2, minmax(0, 1fr));
            gap: 14px;
            min-height: 460px;
        }

        .category-feature-card {
            position: relative;
            grid-column: span 2;
            grid-row: span 2;
            display: block;
            min-width: 0;
            overflow: hidden;
            background: #f1f1f1;
            color: #fff;
        }

        .category-feature-card::after {
            content: '';
            position: absolute;
            inset: 45% 0 0;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, .7));
            pointer-events: none;
        }

        .category-feature-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .category-feature-card:hover img {
            transform: scale(1.035);
        }

        .category-feature-card span {
            position: absolute;
            right: 18px;
            bottom: 14px;
            left: 18px;
            z-index: 2;
            color: #fff !important;
            font-size: 24px;
            font-weight: 700;
            line-height: 1.2;
            text-align: center;
            text-shadow: 0 2px 6px rgba(0, 0, 0, .65);
        }

        .category-grid-product {
            display: block;
            min-width: 0;
            min-height: 0;
            overflow: visible;
            background: #f4f4f4;
        }

        .category-grid-product .home-product {
            height: 100%;
        }

        .category-grid-product .home-product>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-grid-product .product-price {
            bottom: -8px;
            z-index: 4;
        }

        .category-view-more {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #555, #222);
            color: #fff !important;
            text-decoration: none !important;
        }

        .category-view-more img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .category-view-more:hover img {
            transform: scale(1.04);
        }

        .category-view-more::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 1;
            background: rgba(0, 0, 0, .55);
        }

        .category-view-more span {
            position: relative;
            z-index: 2;
            font-size: 22px;
            line-height: 1.5;
            text-align: center;
        }

        @media (max-width: 991px) {
            .category-showcase-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                grid-template-rows: auto;
                min-height: auto;
            }

            .category-feature-card {
                min-height: 430px;
            }

            .category-grid-product,
            .category-view-more {
                min-height: 208px;
            }
        }

        @media (max-width: 575px) {
            .category-showcase-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .category-feature-card {
                grid-column: span 2;
                grid-row: auto;
                min-height: 330px;
            }

            .category-feature-card span {
                font-size: 21px;
            }

            .category-grid-product,
            .category-view-more {
                min-height: 190px;
            }
        }

        .hero-slider-row {
            display: block;
            margin: 10px 0 0;
        }

        .video-hero {
            position: relative;
            width: 100%;
            aspect-ratio: 1920 / 800;
            overflow: hidden;
            background: #111;
        }

        .video-hero-media {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .video-hero-shade {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .32), transparent 42%);
            pointer-events: none;
        }

        .video-hero-button {
            position: absolute;
            left: 50%;
            bottom: clamp(18px, 4vw, 54px);
            z-index: 2;
            min-width: 150px;
            transform: translateX(-50%);
            border: 1px solid #fff;
            background: rgba(0, 0, 0, .72);
            padding: 12px 26px;
            color: #fff !important;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-align: center;
            text-decoration: none !important;
            transition: background .2s ease, color .2s ease;
        }

        .video-hero-button:hover {
            background: #fff;
            color: #111 !important;
        }

        .featured-category-section {
            padding: 34px 10px 8px;
            background: #fff;
        }

        .featured-category-title {
            margin: 0 0 32px;
            color: #050505;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 34px;
            font-weight: 800;
            line-height: 1.15;
            text-align: center;
        }

        .featured-category-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .featured-category-card {
            position: relative;
            display: block;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #f3f3f3;
            color: #050505 !important;
            text-decoration: none !important;
        }

        .featured-category-card img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .featured-category-card:hover img {
            transform: scale(1.025);
        }

        .featured-category-card span {
            position: absolute;
            left: 50%;
            bottom: 30px;
            min-width: 100px;
            transform: translateX(-50%);
            background: #fff;
            padding: 12px 22px;
            color: #050505;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            white-space: nowrap;
        }

        .home-category-promo {
            display: grid;
            grid-template-columns: 2fr 1fr;
            height: clamp(520px, 40vw, 680px);
            min-height: 0;
            margin: 8px 10px 0;
            overflow: hidden;
            background: #fff;
        }

        .home-category-promo-image {
            min-width: 0;
            overflow: hidden;
            background: #241713;
        }

        .home-category-promo-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .6s ease;
        }

        .home-category-promo:hover .home-category-promo-image img {
            transform: scale(1.015);
        }

        .home-category-promo-content {
            display: flex;
            min-width: 0;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 48px;
            color: #111;
            text-align: center;
        }

        .home-category-promo-mark {
            display: flex;
            width: 92px;
            height: 92px;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            border: 2px solid #111;
            transform: rotate(45deg);
            color: #111;
            font-size: 34px;
        }

        .home-category-promo-mark i {
            transform: rotate(-45deg);
        }

        .home-category-promo-content h2 {
            margin: 0 0 25px;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: clamp(30px, 3vw, 54px);
            font-weight: 800;
            line-height: 1.05;
            text-transform: uppercase;
        }

        .home-category-promo-content a {
            min-width: 174px;
            border-radius: 24px;
            background: #151515;
            padding: 11px 28px;
            color: #fff !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            font-weight: 500;
            line-height: 20px;
            text-decoration: none !important;
            transition: background .2s ease, transform .2s ease;
        }

        .home-category-promo-content a:hover {
            transform: translateY(-2px);
            background: #e91d2a;
        }

        .hero-slider,
        .hero-slider .slick-list,
        .hero-slider .slick-track {
            width: 100%;
        }

        .hero-slide {
            aspect-ratio: 1920 / 650;
            overflow: hidden;
            background: #f5f5f5;
        }

        .hero-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .hero-slider .slick-dots {
            bottom: 16px;
            margin-bottom: 0;
        }

        .hero-slider .slick-dots li button::before {
            color: #fff;
            font-size: 11px;
            opacity: .65;
        }

        .hero-slider .slick-dots li.slick-active button::before {
            color: #fff;
            opacity: 1;
        }

        @media (max-width: 767px) {
            .video-hero {
                margin: 10px 11px 0;
                width: calc(100% - 22px);
                aspect-ratio: 16 / 7;
                border-radius: 5px;
            }

            .video-hero-button {
                bottom: 12px;
                min-width: 112px;
                padding: 8px 16px;
                font-size: 10px;
            }

            .featured-category-section {
                padding: 25px 6px 4px;
            }

            .featured-category-title {
                margin-bottom: 20px;
                font-size: 23px;
            }

            .featured-category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 5px;
            }

            .featured-category-card span {
                bottom: 12px;
                min-width: 76px;
                padding: 8px 11px;
                font-size: 11px;
                line-height: 14px;
            }

            .home-category-promo {
                grid-template-columns: 1fr;
                min-height: 0;
                margin: 5px 6px 0;
            }

            .home-category-promo-image {
                aspect-ratio: 16 / 11;
            }

            .home-category-promo-content {
                min-height: 220px;
                padding: 30px 20px;
            }

            .home-category-promo-mark {
                width: 56px;
                height: 56px;
                margin-bottom: 18px;
                font-size: 21px;
            }

            .home-category-promo-content h2 {
                margin-bottom: 18px;
                font-size: 27px;
            }

            .home-category-promo-content a {
                min-width: 140px;
                padding: 9px 22px;
                font-size: 13px;
            }

            .hero-slider-row {
                margin: 10px 11px 0;
            }

            .hero-slide {
                aspect-ratio: 16 / 7;
                border-radius: 5px;
            }

            .hero-slider .slick-dots {
                bottom: 8px;
            }
        }

        .promo-card {
            position: relative;
            display: block;
            overflow: hidden;
            border-radius: 12px;
        }

        .promo-card video,
        .promo-card img {

            width: 100%;
            display: block;

            transition: all .7s ease;
        }

        .promo-card .overlay {

            position: absolute;
            left: 0;
            top: 0;

            width: 100%;
            height: 100%;

            background: rgba(0, 0, 0, .15);

            transition: .5s;

            display: flex;
            justify-content: center;
            align-items: center;

        }

        .promo-card:hover .overlay {

            background: rgba(0, 0, 0, .35);

        }

        .promo-card:hover img,
        .promo-card:hover video {

            transform: scale(1.06);

        }

        .content {

            text-align: center;
            color: #fff;

            opacity: 0;

            transform: translateY(40px);

            transition: .6s ease;

        }

        .promo-card:hover .content {

            opacity: 1;
            transform: translateY(0);

        }

        .content h2 {

            margin: 0;
            font-size: 54px;
            font-weight: 800;
            letter-spacing: 2px;

            text-shadow:
                -2px -2px 0 #0d4ea5,
                2px -2px 0 #0d4ea5,
                -2px 2px 0 #0d4ea5,
                2px 2px 0 #0d4ea5;

        }

        .content span {

            display: block;
            margin-top: -8px;

            font-size: 34px;
            font-weight: 600;
            letter-spacing: 8px;

            text-shadow:
                -2px -2px 0 #0d4ea5,
                2px -2px 0 #0d4ea5,
                -2px 2px 0 #0d4ea5,
                2px 2px 0 #0d4ea5;

        }

        @media(max-width:768px) {

            .content h2 {

                font-size: 34px;

            }

            .content span {

                font-size: 22px;
                letter-spacing: 4px;

            }

        }

        .slick-slide-client img {
            display: block;
            border: 1px solid #ddd;
            border-radius: 50%;
            padding: 5px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var $heroSlider = $('.hero-slider').not('.slick-initialized');

            $heroSlider.slick({
                autoplay: true,
                autoplaySpeed: 1800,
                dots: true,
                arrows: false,
                infinite: true,
                speed: 400,
                slidesToShow: 1,
                slidesToScroll: 1,
                pauseOnHover: true,
                adaptiveHeight: false
            });

            if ($heroSlider.find('.hero-slide').length > 1) {
                window.setTimeout(function() {
                    $heroSlider.slick('slickNext');
                }, 900);
            }
        });
    </script>
@endsection
