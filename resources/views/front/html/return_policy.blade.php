@extends('front.feb.layouts.master')
@section('title', 'Cancellation & Return Policy')
@section('content')
@php($settings = \App\Helpers\Helper::settings())
@include('front.html.partials.info-page-styles')
<main class="info-page">
    <header class="info-page__hero"><div class="info-page__container">
        <nav class="info-page__crumbs"><a href="{{ route('home') }}">Home</a><span>/</span><strong>Return Policy</strong></nav>
        <h1>Cancellation &<br>Return Policy</h1><p>Everything you need to know about cancellations, returns, exchanges, and refunds.</p>
    </div></header>
    <section class="info-page__body"><div class="info-page__container"><article class="info-page__card info-page__content">
        <div class="info-page__lead">Returns & refunds</div>
        @if(!empty($settings->return_policy))
            {!! $settings->return_policy !!}
        @else
            <h2>Our return process</h2>
            <p>If you are not satisfied with your purchase, you may request a return while the product is unused and remains in its original packaging. Please contact us as soon as possible after delivery and explain the issue.</p>
            <ul><li>Keep the product, invoice, packaging, and all included accessories together.</li><li>For a damaged or incorrect item, send clear photos of the item and its packaging.</li><li>Customer-caused damage is not eligible for return.</li><li>Eligible refunds are processed after the returned item has been received and inspected.</li><li>Applicable delivery or return shipping charges may be deducted from the refund.</li></ul>
        @endif
    </article></div></section>
</main>
@endsection
