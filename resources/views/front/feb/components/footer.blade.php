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
@endphp

<div class="footer-container">
    <div id="footer" style="" class="container-fluid no-margin-padding">
        <div class="row" style="display: none">
            <div class="ftbl" style="margin: 0 auto; padding: 40px 0; display: block">
                <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                    <i style="font-size: 1.5rem" class="fa fa-lock"></i>
                    <div style="font-weight: 600; font-size: 1.2rem">
                        All secure payment methods
                    </div>
                    <img src="{{ asset('feb/img/sslcommerz.png') }}" style="max-width: 100%; height: auto; margin: 15px 0"
                        alt="" />
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                    <i style="font-size: 1.5rem" class="fa fa-smile-o"></i>
                    <div style="font-weight: 600; font-size: 1.2rem">
                        Satisfaction guaranteed
                    </div>
                    <div style="margin: 15px 0">
                        Made with premium quality materials.<br /><b>Cozy yet lasts the test of time</b>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                    <i style="font-size: 1.5rem" class="fa fa-truck"></i>
                    <div style="font-weight: 600; font-size: 1.2rem">
                        Worldwide delivery
                    </div>
                    <img src="{{ asset('feb/img/delivery.png') }}" style="max-width: 100%; height: auto; margin: 15px 0" alt="" />
                </div>
            </div>
        </div>
        <div class="row"
            style="
            color: var(--lgray);
            font-size: 0.9rem;
            padding: 40px 0;
          ">
            <div class="ftbl" style="margin: 0 auto; display: block">
                <div class="col-lg-3 col-md-6 col-sm-12 text-left" style="margin-bottom: 20px">
                    <a class="no-style-link" href="{{ route('home') }}">
                        <img src="{{ $febLogoUrl ?? asset('feb/icons/fabrilife-icon-white.svg') }}" alt="Home Logo"
                            style="
                    display: inline;
                    height: 50px;
                    vertical-align: middle;
                    max-width: 100%;
                    margin-bottom: 30px;
                  " />
                    </a>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('about-us') }}">About febristudio</a>
                    </li>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('terms-and-conditions') }}">Terms & Conditions</a>
                    </li>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('privacy-policy') }}">Privacy Policy</a>
                    </li>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('return-policy') }}">Cancellation & Return Policy</a>
                    </li>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('faq') }}">FAQs</a>
                    </li>
                    <li class="sub-item-list">
                        <a class="no-style-link" href="{{ route('contact-us') }}">Contact Us</a>
                    </li>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 text-left">
                    <div class="pull-left text-left" style="width: 100%; margin-top: 20px">
                        <div class="ftbl-text" style="font-weight: 500">
                            <i style="
                      font-size: larger;
                      color: var(--orange);
                      margin-right: 8px;
                    "
                                class="fa fa-envelope-o"></i>GET SPECIAL DISCOUNTS IN YOUR INBOX
                        </div>
                        <div class="form-inline pull-left newsletter-form"
                            style="text-align: left; width: 100%; margin-left: 0px">
                            <input
                                style="
                      color: #fff;
                      background: transparent;
                      border: 0px;
                      border-bottom: 1px solid white;
                      border-radius: 0px;
                    "
                                class="form-control mail-subscribe email-submit-input" type="email" name="email"
                                placeholder="Enter email to get offers, discounts and more." required />
                            <button class="btn btn-sm btn-warning mail-subscribe-btn" type="submit">
                                Subscribe
                            </button>
                        </div>
                    </div>
                    <div class="pull-left text-left" style="width: 100%; margin-top: 20px; margin-bottom: 30px">
                        <div class="ftbl-text" style="font-weight: 500; width: 90%; margin-top: 18px">
                            <i style="
                      font-size: larger;
                      color: var(--orange);
                      margin-right: 8px;
                    "
                                class="fa fa-phone"></i>FOR ANY HELP YOU MAY CALL US AT
                        </div>
                        <div
                            style="
                    text-align: left;
                    color: #aaa;
                    margin-left: 20px;
                    margin-top: 20px;
                  ">
                            @forelse($footerPhones as $phone)
                                <a class="no-style-link" href="tel:{{ $phoneHref($phone) }}">{{ $phone }}</a>
                            @empty
                                <span>Customer Service</span>
                            @endforelse
                            @if($footerSettings?->contact_address)
                                <div class="footer-contact-address" style="margin-top: 12px">
                                    {!! $footerSettings->contact_address !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 text-left">
                    <div class="ftbl-text" style="font-weight: 500; width: 90%">
                        <i style="
                    font-size: larger;
                    color: var(--orange);
                    margin-right: 8px;
                  "
                            class="fa fa-check"></i>FOLLOW US
                    </div>
                    <br />
                    <div>
                        Stay updated on our latest arrivals, exclusive promotions and
                        events.
                    </div>
                    <style>
                        .fb-verify-card {
                            display: flex;
                            gap: 12px;
                            text-decoration: none;
                            background: #ffffffcc;
                            backdrop-filter: blur(6px);
                            border: 1px solid #e8e8e8;
                            border-radius: 14px;
                            padding: 12px 14px;
                            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                            margin: 16px 0px;
                            max-width: 320px;
                        }

                        .fb-verify-card:hover {
                            box-shadow: 0 10px 36px rgba(0, 0, 0, 0.16);
                            text-decoration: none;
                        }

                        .fb-verify-left {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            width: 40px;
                            height: 40px;
                            border-radius: 10px;
                            background: #f0f4ff;
                        }

                        .fb-verify-right {
                            display: flex;
                            flex-direction: column;
                        }

                        .fb-verify-title {
                            font-weight: 700;
                            color: #111;
                            display: flex;
                            align-items: center;
                        }

                        .fb-badge {
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            font-size: 12px;
                            color: #1877f2;
                            background: #eaf1ff;
                            border-radius: 999px;
                            margin: 0px 10px 0px 3px;
                        }

                        .fb-verify-sub {
                            font-size: 13px;
                            color: #666;
                        }

                        .fb-verify-subtitle {
                            font-size: 12px;
                            color: #1877f2;
                        }

                        .fb-follow-i {
                            font-size: 5px;
                            vertical-align: middle;
                            padding: 0px 4px;
                        }

                        .footer-social {
                            display: flex;
                            gap: 20px;
                            align-items: center;
                            justify-content: flex-start;
                            margin-top: 16px;
                        }

                        .footer-social .social {
                            display: inline-flex;
                            width: 32px;
                            height: 32px;
                            align-items: center;
                            justify-content: center;
                            border-radius: 8px;
                            transition:
                                transform 0.15s ease,
                                background-color 0.15s ease,
                                filter 0.15s ease;
                        }

                        .footer-social .social img {
                            width: 22px;
                            height: 22px;
                            display: block;
                            /* make mono-light on dark footers; adjust/remove for light footers */
                            filter: invert(1) brightness(1.2);
                        }

                        .footer-social .social i {
                            color: #fff;
                            font-size: 22px;
                            line-height: 1;
                        }

                        .footer-contact-address,
                        .footer-contact-address * {
                            color: #aaa !important;
                            background: transparent !important;
                            margin-bottom: 4px;
                        }

                        /* hover/focus states */
                        .footer-social .social:hover,
                        .footer-social .social:focus-visible {
                            transform: translateY(-1px);
                            background: rgba(255, 255, 255, 0.08);
                        }

                        /* optional brand-color hover (subtle ring) */
                        .footer-social .social[aria-label="Instagram"]:hover {
                            box-shadow: 0 0 0 2px #e4405f22 inset;
                        }

                        .footer-social .social[aria-label="TikTok"]:hover {
                            box-shadow: 0 0 0 2px #25f4ee22 inset;
                        }

                        .footer-social .social[aria-label="Facebook"]:hover {
                            box-shadow: 0 0 0 2px #1877f222 inset;
                        }

                        .footer-social .social[aria-label="X (Twitter)"]:hover {
                            box-shadow: 0 0 0 2px #cccccc22 inset;
                        }

                        .footer-social .social[aria-label="Pinterest"]:hover {
                            box-shadow: 0 0 0 2px #e6002322 inset;
                        }

                        .footer-social .social[aria-label="YouTube"]:hover {
                            box-shadow: 0 0 0 2px #ff000022 inset;
                        }

                        @media (max-width: 480px) {
                            .footer-social {
                                gap: 16px;
                            }

                            .footer-social .social {
                                width: 28px;
                                height: 28px;
                            }

                            .footer-social .social img {
                                width: 20px;
                                height: 20px;
                            }
                        }

                        @media (max-width: 768px) {
                            .fb-verify-card {
                                position: static;
                                margin-top: 16px;
                            }
                        }
                    </style>

                    @if($footerSocialLinks->isNotEmpty())
                        <div class="footer-social" aria-label="Follow us on social media">
                            @foreach($footerSocialLinks as $social)
                                <a class="social" href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                                    aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">
                                    <i class="fa {{ $social['icon'] }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if($footerSettings?->facebook_link)
                    <a class="fb-verify-card" href="{{ $footerSettings->facebook_link }}" target="_blank"
                        rel="noopener">
                        <div class="fb-verify-left">
                            <!-- FB app shape -->
                            <svg width="28" height="28" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#1877F2"
                                    d="M12 2C6.48 2 2 6.48 2 12a10 10 0 0 0 8.44 9.87v-6.99H8.08V12h2.36V9.8c0-2.33 1.38-3.62 3.5-3.62c1.01 0 2.07.18 2.07.18v2.28h-1.17c-1.15 0-1.51.71-1.51 1.44V12h2.57l-.41 2.88h-2.16v6.99A10 10 0 0 0 22 12c0-5.52-4.48-10-10-10" />
                            </svg>
                        </div>
                        <div class="fb-verify-right">
                            <div class="fb-verify-title">
                                febristudio
                                <span class="fb-badge" aria-label="Verified">
                                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" fill="#1877F2" />
                                        <path fill="#fff"
                                            d="M10.2 14.6L7.8 12.2l1.1-1.1l1.3 1.3l3.9-3.9l1.1 1.1z" />
                                    </svg>
                                </span>
                                <div class="fb-verify-subtitle">Follow</div>
                            </div>
                            <div class="fb-verify-sub">
                                Visit our official Facebook page
                            </div>
                        </div>
                    </a>
                    @endif
                    {{-- <div>
                        <a class="no-style-link"
                            href="https://play.google.com/store/apps/details?id=fabrilife.os.webview&hl=en&gl=US">
                            <img src="{{ asset('feb/img/newhome/playstorebadge.svg') }}"
                                alt="Fabrilife Playstore badge" />
                        </a>
                        <a class="no-style-link" href="https://apps.apple.com/app/fabrilife/id1672120838">
                            <img src="{{ asset('feb/img/newhome/fabrilifeappstore.svg') }}"
                                alt="Fabrilife Appstore badge" />
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row" style="background: #f3f3f3; font-size: 0.9rem">
            <div style="width: 80%; margin: 0 auto; display: block; padding: 20px 0">
                {{ trim(strip_tags(html_entity_decode(
                    $footerSettings?->footer_message ?: 'Your order is handled daily with a lot of ❤️️ and delivered worldwide!',
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                ))) }}
                <br />
                <br />
                <div style="color: #aaaaaa">
                    Copyright © {{ date('Y') }} febristudio. All Right Reserved
                </div>
            </div>
        </div>
    </div>
</div>
