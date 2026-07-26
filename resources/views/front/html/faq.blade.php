@extends('front.feb.layouts.master')
@section('title', 'FAQs')
@section('content')
@include('front.html.partials.info-page-styles')
<main class="info-page">
    <header class="info-page__hero"><div class="info-page__container">
        <nav class="info-page__crumbs"><a href="{{ route('home') }}">Home</a><span>/</span><strong>FAQs</strong></nav>
        <h1>Frequently Asked<br>Questions</h1><p>Quick answers to common questions about shopping, delivery, payment, and returns.</p>
    </div></header>
    <section class="info-page__body"><div class="info-page__container">
        @if($faqs->isNotEmpty())
            <div class="info-page__faq-list">
                @foreach($faqs as $faq)
                    <details class="info-page__faq" @if($loop->first) open @endif>
                        <summary>{{ $faq->question }}</summary>
                        <div class="info-page__faq-answer">{!! $faq->answer !!}</div>
                    </details>
                @endforeach
            </div>
        @else
            <div class="info-page__card info-page__empty">
                <div class="info-page__empty-icon" aria-hidden="true">?</div><h2>Answers are coming soon</h2><p>We are preparing helpful answers for this section. In the meantime, please contact our support team if you need assistance.</p>
                <a class="info-page__button" href="{{ route('contact-us') }}">Contact Support</a>
            </div>
        @endif
    </div></section>
</main>
@endsection
