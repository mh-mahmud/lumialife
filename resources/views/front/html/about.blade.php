@extends('front.feb.layouts.master')

@section('title', 'About Us')

@section('content')
@php
    $siteSettings = \App\Helpers\Helper::settings();
    $aboutImage = $siteSettings->about_us_img
        ? \App\Support\MediaStorage::url($siteSettings->about_us_img, 'settings', '')
        : asset('uploads/noimage.jpg');
@endphp

<style>
    .feb-about {
        --about-ink: #171923;
        --about-muted: #636978;
        --about-line: #e7e8ec;
        --about-soft: #f5f5f3;
        --about-accent: #222126;
        color: var(--about-ink);
        background: #fff;
        overflow: hidden;
    }

    .feb-about, .feb-about * { box-sizing: border-box; }

    .feb-about__container {
        width: min(1180px, calc(100% - 40px));
        margin-inline: auto;
    }

    .feb-about__hero {
        position: relative;
        padding: 70px 0 76px;
        background:
            radial-gradient(circle at 88% 20%, rgba(0, 0, 0, .07), transparent 25%),
            linear-gradient(135deg, #f8f8f6 0%, #eeeeeb 100%);
    }

    .feb-about__crumbs {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 22px;
        color: var(--about-muted);
        font-size: 14px;
    }

    .feb-about__crumbs a { color: inherit; text-decoration: none; }
    .feb-about__crumbs a:hover { color: #000; }
    .feb-about__crumbs span { opacity: .55; }

    .feb-about__hero h1 {
        max-width: 720px;
        margin: 0;
        color: var(--about-ink);
        font-size: clamp(38px, 6vw, 72px);
        font-weight: 700;
        letter-spacing: -.045em;
        line-height: .98;
    }

    .feb-about__hero p {
        max-width: 610px;
        margin: 24px 0 0;
        color: var(--about-muted);
        font-size: clamp(16px, 2vw, 19px);
        line-height: 1.7;
    }

    .feb-about__story { padding: 80px 0; }

    .feb-about__story-grid {
        display: grid;
        grid-template-columns: minmax(0, .95fr) minmax(0, 1.05fr);
        gap: clamp(38px, 6vw, 78px);
        align-items: start;
    }

    .feb-about__media {
        position: sticky;
        top: 105px;
        margin: 0;
        padding: 12px;
        border: 1px solid var(--about-line);
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 22px 60px rgba(24, 26, 33, .09);
    }

    .feb-about__media img {
        display: block;
        width: 100%;
        max-width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        object-fit: cover;
    }

    .feb-about__eyebrow {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 2px 0 20px;
        color: #6c707a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .feb-about__eyebrow::before {
        width: 34px;
        height: 2px;
        background: var(--about-accent);
        content: '';
    }

    .feb-about__copy {
        min-width: 0;
        color: #3d414b;
        font-size: 16px;
        line-height: 1.82;
        overflow-wrap: anywhere;
    }

    .feb-about__copy > :first-child { margin-top: 0 !important; }
    .feb-about__copy > :last-child { margin-bottom: 0 !important; }

    .feb-about__copy h1,
    .feb-about__copy h2,
    .feb-about__copy h3,
    .feb-about__copy h4,
    .feb-about__copy h5,
    .feb-about__copy h6 {
        margin: 34px 0 14px;
        color: var(--about-ink);
        font-weight: 700;
        letter-spacing: -.025em;
        line-height: 1.25;
    }

    .feb-about__copy h1,
    .feb-about__copy h2 { font-size: clamp(27px, 3vw, 40px); }
    .feb-about__copy h3 { font-size: clamp(21px, 2.3vw, 27px); }
    .feb-about__copy h4 { font-size: 20px; }
    .feb-about__copy p { margin: 0 0 19px; }

    .feb-about__copy ul,
    .feb-about__copy ol { margin: 14px 0 24px; padding-left: 24px; }
    .feb-about__copy li { margin: 8px 0; padding-left: 5px; }
    .feb-about__copy strong { color: var(--about-ink); }
    .feb-about__copy a { color: var(--about-ink); text-decoration: underline; }
    .feb-about__copy img { height: auto !important; max-width: 100% !important; border-radius: 12px; }

    .feb-about__copy table {
        display: block;
        width: 100% !important;
        max-width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
    }

    .feb-about__copy iframe { width: 100%; max-width: 100%; }

    .feb-about__values {
        padding: 0 0 84px;
    }

    .feb-about__values-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        border: 1px solid var(--about-line);
        border-radius: 18px;
        overflow: hidden;
        background: var(--about-soft);
    }

    .feb-about__value { padding: 30px; }
    .feb-about__value + .feb-about__value { border-left: 1px solid var(--about-line); }

    .feb-about__value-number {
        display: block;
        margin-bottom: 22px;
        color: #9a9da5;
        font-size: 13px;
        font-weight: 700;
    }

    .feb-about__value h3 { margin: 0 0 9px; font-size: 20px; }
    .feb-about__value p { margin: 0; color: var(--about-muted); font-size: 14px; line-height: 1.65; }

    @media (max-width: 900px) {
        .feb-about__hero { padding: 54px 0 58px; }
        .feb-about__story { padding: 56px 0; }
        .feb-about__story-grid { grid-template-columns: 1fr; }
        .feb-about__media { position: static; }
        .feb-about__values-grid { grid-template-columns: 1fr; }
        .feb-about__value + .feb-about__value { border-top: 1px solid var(--about-line); border-left: 0; }
    }

    @media (max-width: 575px) {
        .feb-about__container { width: min(100% - 28px, 1180px); }
        .feb-about__hero { padding: 38px 0 42px; }
        .feb-about__hero p { margin-top: 18px; }
        .feb-about__story { padding: 38px 0 44px; }
        .feb-about__story-grid { gap: 32px; }
        .feb-about__media { padding: 7px; border-radius: 15px; }
        .feb-about__media img { border-radius: 10px; }
        .feb-about__copy { font-size: 15px; line-height: 1.72; }
        .feb-about__copy h1,
        .feb-about__copy h2,
        .feb-about__copy h3,
        .feb-about__copy h4 { margin-top: 28px; }
        .feb-about__values { padding-bottom: 52px; }
        .feb-about__value { padding: 24px; }
    }
</style>

<main class="feb-about">
    <section class="feb-about__hero">
        <div class="feb-about__container">
            <nav class="feb-about__crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <strong>About Us</strong>
            </nav>
            <h1>More than products.<br>Built around trust.</h1>
            <p>Discover our story, our values, and the commitment behind every product we bring to your home.</p>
        </div>
    </section>

    <section class="feb-about__story">
        <div class="feb-about__container feb-about__story-grid">
            <figure class="feb-about__media">
                <img src="{{ $aboutImage }}" alt="About {{ config('app.name') }}" loading="eager">
            </figure>

            <article>
                <div class="feb-about__eyebrow">Our story</div>
                <div class="feb-about__copy">
                    {!! $siteSettings->about_us !!}
                </div>
            </article>
        </div>
    </section>

    <section class="feb-about__values" aria-label="Our values">
        <div class="feb-about__container">
            <div class="feb-about__values-grid">
                <article class="feb-about__value">
                    <span class="feb-about__value-number">01</span>
                    <h3>Quality First</h3>
                    <p>Carefully selected products that balance quality, performance, and everyday value.</p>
                </article>
                <article class="feb-about__value">
                    <span class="feb-about__value-number">02</span>
                    <h3>Dependable Service</h3>
                    <p>A smooth shopping experience backed by responsive and reliable customer support.</p>
                </article>
                <article class="feb-about__value">
                    <span class="feb-about__value-number">03</span>
                    <h3>Customer Focused</h3>
                    <p>Every decision starts with understanding what makes life easier for our customers.</p>
                </article>
            </div>
        </div>
    </section>
</main>
@endsection
