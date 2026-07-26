@php
    $footerSettings = $febSettings ?? \App\Models\Settings::first();
    $footerPhones = collect([
        $footerSettings?->office_phone_number,
        $footerSettings?->phone_number_2,
        $footerSettings?->phone_number_3,
    ])->filter()->unique()->values();
    $footerSocialLinks = collect([
        ['label' => 'Facebook', 'icon' => 'fa-facebook', 'url' => $footerSettings?->facebook_link],
        ['label' => 'Instagram', 'icon' => 'fa-instagram', 'url' => $footerSettings?->instagram_link],
        ['label' => 'YouTube', 'icon' => 'fa-youtube-play', 'url' => $footerSettings?->youtube_link],
        ['label' => 'X (Twitter)', 'icon' => 'fa-twitter', 'url' => $footerSettings?->twitter_link],
        ['label' => 'LinkedIn', 'icon' => 'fa-linkedin', 'url' => $footerSettings?->linkedin_link],
        ['label' => 'WhatsApp', 'icon' => 'fa-whatsapp', 'url' => $footerSettings?->whats_app_link],
    ])->filter(fn ($social) => filled($social['url']));
    $phoneHref = fn ($phone) => preg_replace('/[^0-9+]/', '', (string) $phone);
    $footerEmail = config('mail.from.address');
@endphp

<footer class="feb-site-footer">
    <div class="feb-footer-social-bar">
        @foreach($footerSocialLinks as $social)
            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">
                <i class="fa {{ $social['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>

    <div class="feb-footer-main">
        <div class="feb-footer-column">
            <h3>Services</h3>
            <ul>
                <li><a href="{{ route('about-us') }}">About febristudio</a></li>
                <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
            </ul>
        </div>

        <div class="feb-footer-column">
            <h3>Company</h3>
            <ul>
                <li><a href="{{ route('terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="feb-footer-column">
            <h3>Help</h3>
            <ul>
                <li><a href="{{ route('return-policy') }}">Cancellation &amp; Return Policy</a></li>
                <li><a href="{{ route('faq') }}">FAQs</a></li>
            </ul>
        </div>

        <div class="feb-footer-column feb-footer-contact">
            <h3>Contact</h3>
            @if(filled($footerEmail))
                <p>
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <em>Mail us:</em>
                    <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
                </p>
            @endif
            @foreach($footerPhones as $phone)
                <p>
                    <i class="fa {{ $loop->first ? 'fa-phone' : 'fa-whatsapp' }}" aria-hidden="true"></i>
                    <em>{{ $loop->first ? 'Phones:' : 'WhatsApp:' }}</em>
                    <a href="tel:{{ $phoneHref($phone) }}">{{ $phone }}</a>
                </p>
            @endforeach
            @if($footerSettings?->contact_address)
                <div class="feb-footer-address">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span>{!! $footerSettings->contact_address !!}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="feb-footer-copyright">
        COPYRIGHT © {{ date('Y') }} <span>LUMIALIFE</span>, ALL RIGHTS RESERVED.
    </div>
</footer>

<style>
    .feb-site-footer {
        width: 100%;
        background: #fff;
        color: #343434;
        font-family: Arial, Helvetica, sans-serif;
    }
    .feb-footer-social-bar {
        display: flex;
        min-height: 78px;
        align-items: center;
        justify-content: center;
        gap: 66px;
        background: #101010;
    }
    .feb-footer-social-bar a {
        display: inline-flex;
        width: 26px;
        height: 34px;
        align-items: center;
        justify-content: center;
        color: #eee;
        font-size: 20px;
        text-decoration: none;
        transition: color .2s ease, transform .2s ease;
    }
    .feb-footer-social-bar a:hover {
        color: #ed1c24;
        transform: translateY(-2px);
    }
    .feb-footer-main {
        display: grid;
        min-height: 278px;
        grid-template-columns: .9fr .9fr .9fr 1.4fr;
        padding: 20px 32px 30px;
        background: #fff;
    }
    .feb-footer-column {
        min-width: 0;
        padding: 0 16px;
        border-right: 1px solid #bcbcbc;
    }
    .feb-footer-column:first-child { padding-left: 0; }
    .feb-footer-column:last-child {
        padding-right: 0;
        border-right: 0;
    }
    .feb-footer-column h3 {
        margin: 0 0 20px;
        color: #090909;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.25;
    }
    .feb-footer-column ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .feb-footer-column li { margin-bottom: 15px; }
    .feb-footer-column a {
        color: #444;
        font-size: 14px;
        line-height: 1.4;
        text-decoration: none;
    }
    .feb-footer-column a:hover { color: #ed1c24; }
    .feb-footer-contact p {
        display: flex;
        align-items: baseline;
        gap: 5px;
        margin: 0 0 16px;
        font-size: 14px;
        line-height: 1.4;
    }
    .feb-footer-contact p > i,
    .feb-footer-address > i {
        width: 13px;
        flex: 0 0 13px;
        color: #777;
    }
    .feb-footer-contact p a { color: #0658ff; }
    .feb-footer-address {
        display: flex;
        gap: 6px;
        color: #555;
        font-size: 13px;
        line-height: 1.5;
    }
    .feb-footer-address p { display: inline; margin: 0; }
    .feb-footer-copyright {
        padding: 0 20px 14px;
        color: #444;
        background: #fff;
        font-size: 12px;
        line-height: 1.5;
        text-align: center;
        text-transform: uppercase;
    }
    .feb-footer-copyright span { color: #ed1c24; }

    @media (max-width: 991px) {
        .feb-footer-main {
            min-height: 0;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px 0;
            padding: 30px 24px 42px;
        }
        .feb-footer-column:nth-child(2) { border-right: 0; }
        .feb-footer-column:nth-child(3),
        .feb-footer-column:nth-child(4) { padding-top: 4px; }
    }

    @media (max-width: 575px) {
        .feb-footer-social-bar {
            min-height: 66px;
            gap: 30px;
        }
        .feb-footer-social-bar a { font-size: 18px; }
        .feb-footer-main {
            display: block;
            padding: 28px 20px 34px;
        }
        .feb-footer-column,
        .feb-footer-column:first-child,
        .feb-footer-column:last-child {
            padding: 0 0 22px;
            border-right: 0;
            border-bottom: 1px solid #dedede;
            margin-bottom: 22px;
        }
        .feb-footer-column:last-child {
            padding-bottom: 0;
            border-bottom: 0;
            margin-bottom: 0;
        }
        .feb-footer-column h3 { margin-bottom: 15px; }
        .feb-footer-copyright { padding: 5px 20px 22px; font-size: 11px; }
    }
</style>
