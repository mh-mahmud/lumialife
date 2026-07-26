@extends('front.feb.layouts.master')

@section('title', 'My Wishlist')

@section('content')
<style>
    .wishlist-page {
        min-height: 68vh;
        padding: 42px 16px 78px;
        background: #fff;
        color: #222;
    }

    .wishlist-container {
        width: min(1055px, 100%);
        margin: 0 auto;
    }

    .wishlist-heading {
        padding-bottom: 21px;
        border-bottom: 1px solid #e8e8e8;
        text-align: center;
    }

    .wishlist-heading h1 {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 0 7px;
        color: #20252d;
        font-size: 24px;
        font-weight: 500;
        line-height: 1.2;
    }

    .wishlist-heading h1 i {
        font-size: 25px;
    }

    .wishlist-count-text {
        margin: 0;
        color: #8a8a8a;
        font-size: 11px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
        padding-top: 26px;
    }

    .wishlist-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .1);
    }

    .wishlist-card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .wishlist-card-link:hover {
        color: inherit;
        text-decoration: none;
    }

    .wishlist-image {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #f1f2f3;
    }

    .wishlist-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .25s ease;
    }

    .wishlist-card:hover .wishlist-image img {
        transform: scale(1.018);
    }

    .wishlist-stock-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 9px 14px;
        border-radius: 3px;
        background: rgba(35, 35, 35, .9);
        color: #fff;
        font-size: 11px;
        white-space: nowrap;
    }

    .wishlist-info {
        padding: 14px 14px 13px;
    }

    .wishlist-name {
        display: -webkit-box;
        min-height: 38px;
        margin: 0 0 10px;
        overflow: hidden;
        color: #24272d;
        font-size: 12px;
        font-weight: 400;
        line-height: 1.55;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .wishlist-price-row {
        display: flex;
        align-items: baseline;
        min-height: 22px;
        gap: 7px;
        margin-bottom: 11px;
        white-space: nowrap;
    }

    .wishlist-price {
        color: #202020;
        font-size: 14px;
        font-weight: 600;
    }

    .wishlist-old-price {
        color: #aaa;
        font-size: 11px;
        text-decoration: line-through;
    }

    .wishlist-discount {
        color: #ff4b55;
        font-size: 10px;
    }

    .wishlist-view-button {
        display: block;
        width: 100%;
        padding: 11px 12px;
        border-radius: 5px;
        background: #303030;
        color: #fff;
        font-size: 11px;
        text-align: center;
        text-decoration: none;
        transition: background .2s ease;
    }

    .wishlist-view-button:hover {
        background: #171717;
        color: #fff;
        text-decoration: none;
    }

    .wishlist-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 3;
        display: grid;
        place-items: center;
        width: 30px;
        height: 30px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .96);
        color: #ff414b;
        font-size: 13px;
        box-shadow: 0 2px 7px rgba(0, 0, 0, .08);
        cursor: pointer;
    }

    .wishlist-remove:hover {
        background: #fff;
        color: #d91e29;
        transform: scale(1.05);
    }

    .wishlist-remove.is-loading {
        opacity: .6;
        pointer-events: none;
    }

    .wishlist-empty {
        padding: 72px 20px;
        text-align: center;
    }

    .wishlist-empty i {
        display: block;
        margin-bottom: 17px;
        color: #d1d4d8;
        font-size: 50px;
    }

    .wishlist-empty h2 {
        margin: 0 0 8px;
        font-size: 21px;
    }

    .wishlist-empty p {
        margin: 0 0 22px;
        color: #777;
        font-size: 13px;
    }

    .wishlist-shop-link {
        display: inline-block;
        padding: 11px 23px;
        border-radius: 4px;
        background: #303030;
        color: #fff;
        font-size: 13px;
        text-decoration: none;
    }

    @media (max-width: 991px) {
        .wishlist-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    @media (max-width: 767px) {
        .wishlist-page { padding: 28px 12px 62px; }
        .wishlist-heading h1 { font-size: 21px; }
        .wishlist-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 11px; padding-top: 20px; }
        .wishlist-info { padding: 11px 10px 10px; }
        .wishlist-name { min-height: 36px; font-size: 11px; }
        .wishlist-price-row { gap: 4px; }
        .wishlist-price { font-size: 13px; }
        .wishlist-old-price, .wishlist-discount { font-size: 9px; }
        .wishlist-view-button { padding: 10px 8px; }
        .wishlist-remove { top: 7px; right: 7px; width: 28px; height: 28px; }
    }

    @media (max-width: 380px) {
        .wishlist-grid { grid-template-columns: 1fr; }
    }
</style>

<main class="wishlist-page">
    <div class="wishlist-container">
        <header class="wishlist-heading">
            <h1><i class="fa fa-heart"></i> My Wishlist</h1>
            <p class="wishlist-count-text"><span data-wishlist-page-count>{{ $wishlists->count() }}</span> items in your wishlist</p>
        </header>

        <div class="wishlist-grid" data-wishlist-grid @if($wishlists->isEmpty()) style="display:none" @endif>
            @foreach($wishlists as $wishlist)
                @php
                    $product = $wishlist->product;
                    $slug = $product?->slug;
                    $image = $product?->img_path ?: $wishlist->product_image;
                    $name = $product?->name ?: $wishlist->product_name;
                    $regularPrice = (float) ($product?->product_value ?: $wishlist->unit_price);
                    $salePrice = (float) ($product?->discount_price ?? 0);
                    $hasDiscount = $salePrice > 0 && $salePrice < $regularPrice;
                    $displayPrice = $hasDiscount ? $salePrice : $regularPrice;
                    $discountPercent = $hasDiscount && $regularPrice > 0
                        ? (int) round((($regularPrice - $salePrice) / $regularPrice) * 100)
                        : 0;
                    $outOfStock = $product && ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0);
                @endphp

                <article class="wishlist-card" data-wishlist-card="{{ $wishlist->product_id }}">
                    @if($slug)
                        <a class="wishlist-card-link" href="{{ route('single-product', $slug) }}">
                    @else
                        <div class="wishlist-card-link">
                    @endif
                        <div class="wishlist-image">
                            <img src="{{ \App\Support\MediaStorage::url($image, 'products') }}" alt="{{ $name }}">
                            @if($outOfStock)
                                <span class="wishlist-stock-badge">Out of Stock</span>
                            @endif
                        </div>
                        <div class="wishlist-info">
                            <h2 class="wishlist-name">{{ $name }}</h2>
                            <div class="wishlist-price-row">
                                <span class="wishlist-price">{{ $febCurrency->format($displayPrice, 0) }}</span>
                                @if($hasDiscount)
                                    <span class="wishlist-old-price">{{ $febCurrency->format($regularPrice, 0) }}</span>
                                    <span class="wishlist-discount">-{{ $discountPercent }}%</span>
                                @endif
                            </div>
                            @if($slug)
                                <span class="wishlist-view-button">View</span>
                            @endif
                        </div>
                    @if($slug)</a>@else</div>@endif

                    <button type="button" class="wishlist-remove active" data-wishlist-btn data-wishlist-style="remove" data-product-id="{{ $wishlist->product_id }}" aria-label="Remove from wishlist" title="Remove from wishlist">
                        <i class="fa fa-times"></i>
                    </button>
                </article>
            @endforeach
        </div>

        <div class="wishlist-empty" data-wishlist-empty @if($wishlists->isNotEmpty()) style="display:none" @endif>
            <i class="fa fa-heart-o"></i>
            <h2>Your wishlist is empty</h2>
            <p>Save products you love and find them here later.</p>
            <a class="wishlist-shop-link" href="{{ route('shop-new') }}">Continue Shopping</a>
        </div>
    </div>
</main>

<script>
    window.addEventListener('wishlistUpdated', function (event) {
        if (event.detail.action !== 'removed') return;

        var card = document.querySelector('[data-wishlist-card="' + event.detail.productId + '"]');
        if (card) card.remove();

        var count = document.querySelector('[data-wishlist-page-count]');
        if (count) count.textContent = event.detail.count;

        if (event.detail.count === 0) {
            document.querySelector('[data-wishlist-grid]').style.display = 'none';
            document.querySelector('[data-wishlist-empty]').style.display = '';
        }
    });
</script>
@endsection
