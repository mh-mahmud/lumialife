@extends('front.feb.layouts.master')

@section('title', 'Our Outlets')

@section('content')
<style>
    .outlets-page { padding: 28px 16px 76px; background: #f7f7f7; color: #222; }
    .outlets-container { width: min(1180px, 100%); margin: 0 auto; }
    .outlets-banner {
        position: relative;
        min-height: 330px;
        overflow: hidden;
        border-radius: 3px;
        background-position: center;
        background-size: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .outlets-banner::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,.35); }
    .outlets-banner-content { position: relative; z-index: 1; max-width: 650px; padding: 30px; color: #fff; }
    .outlets-banner h1 { margin: 0 0 12px; font-size: 34px; font-weight: 700; }
    .outlets-banner p { margin: 0; font-size: 16px; line-height: 1.7; }
    .outlets-heading { margin: 42px 0 24px; text-align: center; }
    .outlets-heading h2 { margin: 0 0 8px; font-size: 27px; font-weight: 700; }
    .outlets-heading p { margin: 0; color: #737373; font-size: 14px; }
    .outlets-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; }
    .outlet-card { overflow: hidden; background: #fff; border: 1px solid #e2e2e2; border-radius: 5px; box-shadow: 0 3px 15px rgba(0,0,0,.055); }
    .outlet-card-info { min-height: 155px; padding: 22px 24px 19px; }
    .outlet-card-title { display: flex; align-items: center; gap: 9px; margin: 0 0 15px; font-size: 20px; font-weight: 700; }
    .outlet-card-title i { display: grid; place-items: center; width: 29px; height: 29px; border-radius: 50%; background: #252f40; color: #fff; font-size: 13px; }
    .outlet-detail { display: grid; grid-template-columns: 78px minmax(0, 1fr); gap: 7px; margin-bottom: 9px; font-size: 14px; line-height: 1.55; }
    .outlet-detail strong { color: #333; }
    .outlet-detail span, .outlet-detail a { color: #666; text-decoration: none; }
    .outlet-detail a:hover { color: #e4315d; }
    .outlet-map { width: 100%; height: 350px; border: 0; display: block; }
    .outlets-empty { padding: 70px 20px; background: #fff; border-radius: 5px; text-align: center; color: #777; }
    .outlets-empty i { display: block; margin-bottom: 14px; font-size: 42px; color: #c7c7c7; }
    @media (max-width: 767px) {
        .outlets-page { padding: 15px 10px 60px; }
        .outlets-banner { min-height: 230px; }
        .outlets-banner h1 { font-size: 27px; }
        .outlets-banner p { font-size: 14px; }
        .outlets-heading { margin: 30px 0 18px; }
        .outlets-grid { grid-template-columns: 1fr; gap: 16px; }
        .outlet-card-info { min-height: auto; padding: 18px 16px; }
        .outlet-card-title { font-size: 18px; }
        .outlet-detail { grid-template-columns: 70px minmax(0, 1fr); font-size: 13px; }
        .outlet-map { height: 285px; }
    }
</style>

@php
    $bannerUrl = $outletPageSetting?->bannerUrl() ?? asset('feb/image-gallery/outletbanner.jpg');
@endphp

<main class="outlets-page">
    <div class="outlets-container">
        <section class="outlets-banner" style="background-image:url('{{ $bannerUrl }}')">
            <div class="outlets-banner-content">
                <h1>Discover Our Fashion Outlets</h1>
                <p>Visit our outlets for exclusive styles, premium shopping experiences and the latest Fabrilife collections.</p>
            </div>
        </section>

        <header class="outlets-heading">
            <h2>Find an Outlet Near You</h2>
            <p>{{ $outlets->count() }} active outlet{{ $outlets->count() === 1 ? '' : 's' }}</p>
        </header>

        @if($outlets->isNotEmpty())
            <div class="outlets-grid">
                @foreach($outlets as $outlet)
                    <article class="outlet-card">
                        <div class="outlet-card-info">
                            <h3 class="outlet-card-title"><i class="fa fa-map-marker"></i>{{ $outlet->location_name }}</h3>
                            <div class="outlet-detail">
                                <strong>Address:</strong>
                                <span>{{ $outlet->address }}</span>
                            </div>
                            <div class="outlet-detail">
                                <strong>Hotline:</strong>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $outlet->hotline) }}">{{ $outlet->hotline }}</a>
                            </div>
                        </div>
                        <iframe class="outlet-map" src="{{ $outlet->map_url }}" title="{{ $outlet->location_name }} map"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </article>
                @endforeach
            </div>
        @else
            <div class="outlets-empty">
                <i class="fa fa-map-marker"></i>
                <p>No active outlet is available right now.</p>
            </div>
        @endif
    </div>
</main>
@endsection
