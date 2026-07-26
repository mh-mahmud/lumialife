@extends('front.feb.layouts.master')
@section('title', 'Contact Us')
@section('content')
@php
    $settings = \App\Helpers\Helper::settings();
    $phone = $settings->office_phone_number;
    $phoneLink = preg_replace('/[^0-9+]/', '', (string) $phone);
@endphp
@include('front.html.partials.info-page-styles')
<main class="info-page">
    <header class="info-page__hero"><div class="info-page__container">
        <nav class="info-page__crumbs"><a href="{{ route('home') }}">Home</a><span>/</span><strong>Contact Us</strong></nav>
        <h1>Let’s start a<br>conversation.</h1><p>Have a question about a product or an order? Send us a message and our team will help.</p>
    </div></header>

    <section class="info-page__body"><div class="info-page__container">
        <div class="info-page__contact-grid">
            <aside class="info-page__contact-card">
                <h2>Get in touch</h2><p>Reach us using the details below during our regular business hours.</p>
                @if(!empty($settings->contact_address))
                    <div class="info-page__detail"><span class="info-page__detail-icon" aria-hidden="true">⌖</span><div><small>Address</small><div>{!! $settings->contact_address !!}</div></div></div>
                @endif
                @if(!empty($phone))
                    <div class="info-page__detail"><span class="info-page__detail-icon" aria-hidden="true">☎</span><div><small>Phone</small><a href="tel:{{ $phoneLink }}">{{ $phone }}</a></div></div>
                @endif
                <div class="info-page__detail"><span class="info-page__detail-icon" aria-hidden="true">◷</span><div><small>Store hours</small><div>10:00 AM – 10:00 PM<br>7 days a week</div></div></div>
            </aside>

            <section class="info-page__contact-card">
                <h2>Send a message</h2><p>Fill in the form and share as much detail as possible.</p>
                <form action="" id="contact-form" method="POST">
                    @csrf
                    <div class="info-page__form-grid">
                        <div class="info-page__field"><label for="contact-name">Full name</label><input id="contact-name" name="name" type="text" placeholder="Your name" required></div>
                        <div class="info-page__field"><label for="contact-email">Email address</label><input id="contact-email" name="email" type="email" placeholder="you@example.com" required></div>
                        <div class="info-page__field"><label for="contact-phone">Phone number</label><input id="contact-phone" name="number" type="tel" placeholder="01XXXXXXXXX" required></div>
                        <div class="info-page__field"><label for="contact-subject">Subject</label><input id="contact-subject" name="subject" type="text" placeholder="How can we help?" required></div>
                        <div class="info-page__field info-page__field--wide"><label for="contact-message">Message</label><textarea id="contact-message" name="message" placeholder="Write your message here..." required></textarea></div>
                    </div>
                    <button class="info-page__button" type="submit">Send Message</button>
                    <p class="ajax-response" aria-live="polite"></p>
                </form>
            </section>
        </div>

        <div class="info-page__map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.089890097914!2d90.34928531457048!3d23.7676268845825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8100031a53f%3A0x9c6fd142dd0a3aa3!2sAdabor%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1691580634567!5m2!1sen!2sbd" title="Store location" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div></section>
</main>
@endsection
