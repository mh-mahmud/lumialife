@extends('front.feb.layouts.master')
@section('title', 'Terms & Conditions')
@section('content')
@php($settings = \App\Helpers\Helper::settings())
@include('front.html.partials.info-page-styles')
<main class="info-page">
    <header class="info-page__hero"><div class="info-page__container">
        <nav class="info-page__crumbs"><a href="{{ route('home') }}">Home</a><span>/</span><strong>Terms & Conditions</strong></nav>
        <h1>Terms & Conditions</h1><p>Please read the terms that guide the use of our website, services, and purchases.</p>
    </div></header>
    <section class="info-page__body"><div class="info-page__container"><article class="info-page__card info-page__content">
        <div class="info-page__lead">Website terms</div>{!! $settings->terms_and_conditions !!}
    </article></div></section>
</main>
@endsection
