@extends('front.feb.layouts.master')
@section('title', 'Privacy Policy')
@section('content')
@include('front.html.partials.info-page-styles')
<main class="info-page">
    <header class="info-page__hero"><div class="info-page__container">
        <nav class="info-page__crumbs"><a href="{{ route('home') }}">Home</a><span>/</span><strong>Privacy Policy</strong></nav>
        <h1>Privacy Policy</h1><p>Your privacy matters. This page explains how we collect, use, and protect your information.</p>
    </div></header>
    <section class="info-page__body"><div class="info-page__container"><article class="info-page__card info-page__content">
        <div class="info-page__lead">Your information</div>
        <h2>How we handle your information</h2>
        <p>We collect the contact, delivery, and order information required to process purchases, provide customer support, and deliver products.</p>
        <h3>How your information is used</h3>
        <p>We use reasonable safeguards to protect personal information and do not sell customer information. Information may be shared with payment and delivery providers only when needed to complete an order.</p>
        <h3>Your choices</h3>
        <p>You may contact us to request access to or correction of your personal information.</p>
    </article></div></section>
</main>
@endsection
