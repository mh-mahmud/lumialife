<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="icon" href="{{ $febFaviconUrl }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/bootstrap-social.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/tether.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/bootstrap-reboot.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/rangeslider.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/w2ui-1.5.rc1.min.css') }}" />

    <link href="https://fonts.googleapis.com/css?family=Rubik" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Fjalla+One&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('feb/css/new_app.css?v=6a4b5f5f3b91f') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/rangeslider.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/swal.css') }}" />
    <link rel="stylesheet" href="{{ asset('feb/css/bootstrap-datepicker.css') }}" />

    <link rel="stylesheet" href="{{ asset('feb/css/mobile-nav.css?v=6a4b5f5f3b922') }}" />

    <link rel="stylesheet" href="{{ asset('feb/css/desktop-header.css?v=6a4b5f5f3b924') }}" />

    <link rel="stylesheet" href="{{ asset('feb/css/lazy-load.css?v=1.1') }}" />

    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" />
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css" />

    <style>
        /* SweetAlert z-index override to appear above side cart (z-index: 9999) */
        .swal2-container {
            z-index: 10070 !important;
        }

        .swal2-popup {
            z-index: 10071 !important;
        }

        /* SweetAlert v1 (legacy) */
        .sweet-overlay {
            z-index: 10069 !important;
        }

        .sweet-alert {
            z-index: 10070 !important;
        }

        /* Common swal wrapper classes */
        .swal-overlay {
            z-index: 10069 !important;
        }

        .swal-modal {
            z-index: 10070 !important;
        }

        .mobile-login-toggle {
            height: auto !important;
            max-height: 1000px !important;
            transition: all 0.25s ease-in !important;
            opacity: 1 !important;
        }

        .mobile-nav-link {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        .wishlist-count {
            flex: none !important;
        }


        .list-hidden {
            display: none;
        }

        .cats {
            margin-left: 13px;
        }

        .cats a {
            text-decoration: none;
        }

        .viewall {
            font-style: italic;
        }

        .cat1_bold {
            font-weight: bold;
        }

        .cat3_name .cat-name {
            color: #888;
        }

        .cat1_childs,
        .cat2_childs {
            /* max-height: 1000px; */
            transition: all 0.25s ease-in;
            opacity: 1;
        }

        .cat_hide {
            opacity: 0;
            max-height: 0;
            transition: all 0.15s ease-out;
            overflow: hidden;
        }

        .cat1_name,
        .cat2_name,
        .cat3_name {
            display: flex;
            padding: 8px 0px;
        }

        .cat-name {
            width: 100%;
            font-size: 15px;
            color: #000;
            line-height: 15px;
        }

        .cat-plus {
            padding: 0px 14px 0px 0px;
            line-height: .7rem;
        }

        .cat-header {
            padding-left: 10px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 2px;
            font-size: 1.1rem;
        }

        .jas-menu:hover .sub-menu {
            opacity: 1;
            visibility: visible;
        }

        .campaign {
            color: black;
        }

        .sub-menu {
            position: absolute !important;
            min-width: 100% !important;
            left: 0 !important;
            right: 0 !important;
            margin-top: 0;
            transition: none;
            display: table;
            opacity: 0;
            visibility: hidden;
            text-align: left;
            z-index: 10;
            padding: 0;
            box-shadow: 3px 3px 6px 0 rgb(0 0 0 / 10%);
            white-space: nowrap;
            background: #fff !important;
            height: 100% !important;
            width: calc(50vw);
            top: 85px;
        }

        .jas-menu>li:not(:last-child) {
            border-bottom: 0 solid;
        }

        .sub-menu>li:nth-child(2n) {
            background: #f7f7f7;
            height: 100%;
        }

        .sub-menu li a {
            font-weight: 400;
            font-size: 14px;
            color: #666;
            position: relative;
            text-decoration: none;
        }

        .sub-menu>li>a {
            font-weight: 500;
            font-size: 15px;
            color: #000;
        }

        .jas-menu>li>ul>.sub-column-item {
            display: table-cell;
        }

        .jas-menu ul li {
            line-height: 16px;
            padding: 10px 15px;
        }

        .sub-menu>li {
            display: table-cell;
            padding-left: 10px;
            min-width: 120px;
        }

        .sub-menu li {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            list-style: none;
        }

        .jas-menu li {
            font-size: 14px;
            list-style: none;
            position: relative;
        }

        .jas-menu .sub-column-item>a {
            color: #222;
            text-transform: uppercase;
            font-weight: 500;
            padding: 6px 0;
        }

        .sub-column-item .sub-column {
            padding: 0;
            left: 250px;
            top: 20%;
            padding-bottom: 15px;
        }

        .jas-menu ul li:not(:last-child) {
            border-bottom: 0 solid;
        }

        .sub-column-item .sub-column>li {
            padding: 6px 0;
        }

        .sub-menu li {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }









        .account_ul {
            text-align: left;
            background: #484848;
            list-style: none;
            position: absolute;
            z-index: 9;
            width: 150px;
            right: 23px;
            transition: .3s ease-in;
            display: none;
            padding-left: 15px;
            z-index: 999;
        }

        .account_ul>li:not(:last-child) {
            border-bottom: 1px solid #76daff;
        }

        .account-item {
            color: #FFF;
        }

        .account-item:hover {
            color: #FFF;
        }

        .fab-account {
            cursor: pointer;
        }

        .nav-sidebar-logged-in {
            display: block;
            float: left;
            width: 100%;
            background: #666;
            color: #eee;
            padding-top: 10px;
            padding-bottom: 10px;
            overflow-y: scroll;
            /* position: absolute; */
            top: 140px;
            bottom: 0px;
        }

        .nav-dash-item::after {
            after\ \{\ display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-weight: normal;
            font-size: 14px;
            line-height: 1;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            content: "\f054";
            float: right;
            vertical-align: middle;
            line-height: 35px;
            margin-right: 12px;
            font-weight: lighter;
            font-size: small;
            color: #b5b5b5;
        }

        .nav-dash-item {
            color: #FFF;
            line-height: 35px;
            padding-left: 10%;
        }

        .nav-dash-item>a {
            color: #E8E8E8;
            font-size: .9em;
        }

        .nav-dash-item i {
            margin-right: 5px;
            padding: 6px;
            font-size: .8em;
            border-radius: 1em;
            background: #4d4d4d;
            border-top: 1px solid #000;
            border-left: 1px solid #222;
            width: 24px;
            height: 24px;
        }

        .nav-seller {
            padding-bottom: 40px;
        }

        .search-home-mobile {
            background: #ddd;
            position: fixed;
            top: 60px;
            width: 100%;
            margin: 0 -10px !important;
            display: none;
            line-height: unset !important;
            white-space: nowrap;
        }

        .mobile-search-bar {
            width: 100%;
            position: relative;
            padding-bottom: 4px;
        }

        .mobile-search-bar span {
            top: 95% !important;
            margin: 0 auto;
            padding: 0px 10px;
            width: 100%;
            font-size: .9em;
        }

        .mobile-search-bar input {
            border-radius: 5px !important;
            border: 0px !important;
        }

        /* Mobile Navigation Body Padding - Only on mobile viewports */
        @media (max-width: 991px) {
            body {
                padding-bottom: calc(var(--mobile-bottom-nav-height, 60px) + 20px + var(--safe-area-bottom, 0px));
            }

            /* Footer should have extra margin above bottom nav */
            .footer-container {
                padding-bottom: 20px;
            }
        }

        .sub-menu li a:hover {
            color: #04aeef;
        }

        #app-promo-modal .close {
            opacity: 1;
            position: absolute;
            right: 21px;
            top: 10px;
        }

        #app-promo-modal .close span {
            color: #282828;
            font-weight: bold;
            font-size: 40px;
        }

        @media(max-width: 996px) {
            #app-promo-modal .modal-dialog {
                margin-top: 100px;
            }

            #app-promo-modal .close span {
                font-size: 30px;
            }

            #app-promo-modal .close {
                right: 18px;
            }

        }

        .fb-share-button>span {
            height: 24px !important;
        }
        #footer{
            background-color: #214268 ! important;
        }
    </style>

    <script src="{{ asset('feb/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/Joe12387/detectIncognito@v1.7.0/dist/es5/detectIncognito.min.js"></script>

    <script type="text/javascript">
        if (!NodeList.prototype.forEach) {
            NodeList.prototype.forEach = Array.prototype.forEach;
        }

    </script>
    <script src="{{ asset('feb/js/intersection-observer.js') }}"></script>
    <!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-6304636058067473",
            enable_page_level_ads: true
        });
    </script> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('feb/css/newhome.css?v=55418561') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('feb/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('feb/css/slick-theme.css') }}" />


</head>

<body>

    <script type="text/javascript">
        var eventMethod = window.addEventListener ?
            "addEventListener" :
            "attachEvent";
        var eventer = window[eventMethod];
        var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

        // Listen to message from child window
        eventer(
            messageEvent,
            function(e) {
                if (e.data === "Iframe Login Done") {
                    $(".commonmodal").modal("toggle");
                    window.loggedInSuccess = true;
                }
            },
            false,
        );

        function inIframe() {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }

        function hideNavAndFooter() {
            if (inIframe()) {
                //console.log(window.location.href);
                if (
                    window.location.href.includes("/login") ||
                    window.location.href.includes("/register")
                ) {
                    document.querySelectorAll(".site-header-stack").forEach(function(el) {
                        el.style.display = "none";
                    });
                    var footerContainer = document.querySelector(".footer-container");
                    if (footerContainer) footerContainer.style.display = "none";
                }
            }
        }

        function invokeLogin() {
            document.querySelector(".commonmodal-header").textContent =
                "Save to Wishlist";
            document.querySelector(".commonmodal-body").style.overflow = "hidden";
            document.querySelector(".commonmodal-body").innerHTML =
                '<div style="text-align: center;">' +
                '<div style="width: 80px; height: 80px; background: #f8f8f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">' +
                '<i class="fa fa-heart" style="font-size: 36px; color: #ff3f6c;"></i>' +
                "</div>" +
                '<h5 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #222;">Login to Save Your Favorites</h5>' +
                '<p style="margin: 0 0 28px; font-size: 14px; color: #666; line-height: 1.6;">Create a wishlist to save items you love and access them anytime.</p>' +
                '<a href="{{ route('theme-login') }}" style="display: block; background: #222f3f; color: #fff; padding: 16px; border-radius: 8px; font-size: 16px; font-weight: 500; text-decoration: none; margin-bottom: 16px;">Login Now</a>' +
                '<div style="font-size: 13px; color: #888;">New here? <a href="{{ route('theme-register') }}" style="color: #ff3f6c; text-decoration: none; font-weight: 500;">Create an account</a></div>' +
                "</div>";
            $(".commonmodal").modal("show");
        }
        window.onload = hideNavAndFooter;
    </script>

    @include('front.feb.components.header')

    @yield('content')

    @include('front.feb.components.side-cart')

    @include('front.feb.components.footer')

    @if ($febSettings?->app_promo_enabled ?? true)
    <div class="modal fade" id="app-promo-modal" tabindex="-1" role="dialog" aria-labelledby="app-promo-modal-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 40vw">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0); border: none">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @php
                        $defaultAppPromoLink = '/shop?refinementList%5Bcats%5D%5B0%5D=Sports%20%3E%20Football%20Jersey';
                        $appPromoLink = $febSettings?->app_promo_link ?: $defaultAppPromoLink;
                    @endphp
                    <a class="no-style-link app-promo-slide" data-slide-index="0"
                        data-slide-link="{{ $appPromoLink }}"
                        href="{{ $appPromoLink }}" target="_blank"
                        style="">
                        <img src="{{ $febSettings?->app_promo_image
                            ? \App\Support\MediaStorage::url($febSettings->app_promo_image, 'settings', '')
                            : asset('feb/image-gallery/6a203def13e1a-square.jpeg') }}" width="100%"
                            alt="App Promo Image" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        (function() {
            // Cookie helpers (duplicated minimally so this partial is self-contained
            // even if the layout's own helpers haven't loaded yet).
            function _setCookie(n, v, d) {
                var e = new Date();
                e.setTime(e.getTime() + d * 24 * 60 * 60 * 1000);
                document.cookie =
                    n + "=" + v + ";expires=" + e.toUTCString() + ";path=/";
            }

            function _getCookie(n) {
                var c = document.cookie.split(";");
                for (var i = 0; i < c.length; i++) {
                    var p = c[i].trim();
                    if (p.indexOf(n + "=") === 0) return p.substring(n.length + 1);
                }
                return "";
            }

            var slideCount = 1;
            if (slideCount < 1) return;

            function ready(fn) {
                if (window.jQuery) {
                    jQuery(fn);
                } else if (document.readyState !== "loading") {
                    fn();
                } else {
                    document.addEventListener("DOMContentLoaded", fn);
                }
            }

            ready(function() {
                // 24h cooldown after a full round
                if (_getCookie("popup_cooldown")) return;

                var idx = parseInt(_getCookie("popup_index") || "0", 10);
                if (isNaN(idx) || idx < 0 || idx >= slideCount) idx = 0;

                var $modal = window.jQuery ? jQuery("#app-promo-modal") : null;
                if (!$modal || !$modal.length) return;

                // Hide all slides, show the selected one
                var slides = $modal.find(".app-promo-slide");
                slides.hide();
                var $active = slides.filter('[data-slide-index="' + idx + '"]');
                if (!$active.length) $active = slides.eq(0);
                $active.show();

                // Platform-aware link only when slide has no explicit link
                // (campaigns disabled, or legacy default_image-only mode).
                var slideLink = $active.attr("data-slide-link");
                if (!slideLink) {
                    var ua = navigator.userAgent.toLowerCase();
                    if (ua.indexOf("android") > -1) {
                        $active.attr(
                            "href",
                            "https://play.google.com/store/apps/details?id=fabrilife.os.webview&hl=en&gl=US",
                        );
                    } else if (ua.indexOf("iphone") > -1) {
                        $active.attr(
                            "href",
                            "https://apps.apple.com/app/fabrilife/id1672120838",
                        );
                    } else {
                        $active.attr("href", "/shop");
                    }
                }

                setTimeout(function() {
                    $modal.modal("toggle");
                }, 1000);

                // Advance rotation. After last slide in the round, set 24h cooldown
                // and reset index so the next round starts fresh.
                var nextIdx = idx + 1;
                if (nextIdx >= slideCount) {
                    _setCookie("popup_index", 0, 7);
                    _setCookie("popup_cooldown", 1, 1);
                } else {
                    _setCookie("popup_index", nextIdx, 7);
                }
            });
        })();
    </script>
    @endif
    <script src="{{ asset('feb/js/localforage.min.js') }}"></script>

    <script src="{{ asset('feb/js/jquery-latest.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>

    <script src="{{ asset('feb/js/tether.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('feb/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('feb/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('feb/js/tinymce.min.js') }}"></script>
    <script src="{{ asset('feb/js/rangeslider.min.js') }}"></script>
    <script src="{{ asset('feb/js/w2ui-1.5.rc1.min.js') }}"></script>
    <script src="{{ asset('feb/js/usp-polyfill.js') }}"></script>
    <script src="{{ asset('feb/js/notify.min.js') }}"></script>
    <script src="{{ asset('feb/js/swal.js') }}" charset="utf-8"></script>

    @php
        $storeCurrencyConfig = [
            'code' => $febCurrency->code(),
            'symbol' => $febCurrency->symbol(),
            'myrToBdtRate' => $febCurrency->rate(),
            'countryDetected' => $febCurrency->countryDetected(),
            'ipLookupRequired' => $febCurrency->ipLookupRequired(),
            'detectedCountry' => $febCurrency->detectedCountry(),
        ];
    @endphp
    <script>
        window.isLoggedIn = @json(Auth::check());
        window.storeCurrency = @json($storeCurrencyConfig);
        if (!window.storeCurrency.countryDetected && window.Intl) {
            try {
                if (Intl.DateTimeFormat().resolvedOptions().timeZone !== 'Asia/Dhaka') {
                    window.storeCurrency.code = 'MYR';
                    window.storeCurrency.symbol = 'RM';
                }
            } catch (error) {
                // Keep the safe BDT fallback when browser timezone is unavailable.
            }
        }
        window.convertStoreCurrency = function (bdtAmount) {
            var amount = Number(bdtAmount || 0);
            return window.storeCurrency.code === 'MYR'
                ? amount / window.storeCurrency.myrToBdtRate
                : amount;
        };
        window.formatStoreCurrency = function (bdtAmount, decimals) {
            var value = window.convertStoreCurrency(bdtAmount);
            return window.storeCurrency.symbol + ' ' + value.toLocaleString('en-US', {
                minimumFractionDigits: decimals == null ? 2 : decimals,
                maximumFractionDigits: decimals == null ? 2 : decimals
            });
        };
        window.convertLegacyBdtPrices = function (root) {
            if (window.storeCurrency.code !== 'MYR') return;

            var walker = document.createTreeWalker(root || document.body, NodeFilter.SHOW_TEXT);
            var nodes = [];
            while (walker.nextNode()) {
                var parent = walker.currentNode.parentElement;
                if (parent && !parent.closest('script, style, textarea, input, option')) {
                    nodes.push(walker.currentNode);
                }
            }
            nodes.forEach(function (node) {
                node.nodeValue = node.nodeValue.replace(/৳\s*([\d,]+(?:\.\d+)?)/g, function (_, amount) {
                    return window.formatStoreCurrency(Number(amount.replace(/,/g, '')));
                });
            });
        };
        document.addEventListener('DOMContentLoaded', function () {
            window.convertLegacyBdtPrices(document.body);

            if (window.storeCurrency.ipLookupRequired && window.fetch) {
                fetch('https://api.country.is/', { mode: 'cors' })
                    .then(function (response) {
                        if (!response.ok) throw new Error('Country lookup failed');
                        return response.json();
                    })
                    .then(function (data) {
                        var country = String(data.country || '').toUpperCase();
                        if (!/^[A-Z]{2}$/.test(country)) return;

                        document.cookie = 'store_country=' + country + ';path=/;max-age=21600;SameSite=Lax';
                        var expectedCurrency = country === 'BD' ? 'BDT' : 'MYR';
                        if (expectedCurrency !== window.storeCurrency.code) {
                            window.location.reload();
                        }
                    })
                    .catch(function () {
                        // Country/timezone fallback already selected a safe currency.
                    });
            }
        });
        window.csrfToken = @json(csrf_token());
        window.wishlistLoginUrl = @json(route('theme-login'));
        window.wishlistDataUrl = @json(route('wishlist.data'));
        window.wishlistToggleUrl = @json(route('wishlist.add'));
    </script>
    <script src="{{ asset('feb/js/wishlist.js?v=20260714') }}"></script>
    <script src="{{ asset('feb/js/mobile-nav.js?v=6a4b5f5f3d003') }}"></script>

    <script src="{{ asset('feb/js/lazy-load.js?v=1.1') }}"></script>

    <script>
        $(document).on("click", ".cat1_name", function() {
            children = $(this).parent().find(".cat1_childs");
            if ($(children).hasClass("cat_hide")) {
                $(children).removeClass("cat_hide");
                $(children)
                    .find(".cat2_childs")
                    .each(function(index, elem) {
                        $(elem).addClass("cat_hide");
                    });
                $(this)
                    .find(".cat-plus")
                    .html('<i class="fa fa-minus" aria-hidden="true"></i>');
                $(".cat1_name .cat-name").addClass("cat1_bold");
            } else {
                $(children).addClass("cat_hide");
                $(children)
                    .find(".cat2_childs")
                    .each(function(index, elem) {
                        $(elem).addClass("cat_hide");
                    });
                $(this)
                    .parent()
                    .find(".cat-plus")
                    .each(function(index, elem) {
                        $(elem).html('<i class="fa fa-plus" aria-hidden="true"></i>');
                    });
                if ($(".cats .cat1_childs:not(.cat_hide)").length == 0)
                    $(".cat1_name .cat-name").removeClass("cat1_bold");
            }
        });

        $(document).on("click", ".cat2_name", function() {
            children = $(this).parent().find(".cat2_childs");
            if ($(children).hasClass("cat_hide")) {
                $(children).removeClass("cat_hide");
                $(children)
                    .find(".cat3_childs")
                    .each(function(index, elem) {
                        $(elem).addClass("cat_hide");
                    });
                $(this)
                    .find(".cat-plus")
                    .html('<i class="fa fa-minus" aria-hidden="true"></i>');
            } else {
                $(children).addClass("cat_hide");
                $(children)
                    .find(".cat3_childs")
                    .each(function(index, elem) {
                        $(elem).addClass("cat_hide");
                    });
                $(this)
                    .parent()
                    .find(".cat-plus")
                    .each(function(index, elem) {
                        $(elem).html('<i class="fa fa-plus" aria-hidden="true"></i>');
                    });
            }
        });

    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
        function goToPage(page) {
            window.location.href = "/" + page;
        }
        //carosal
        function carousel_sleek(item_name) {
            $("." + item_name).slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });
        }

        var carousel_html = "";
        $(document).ready(function() {
            var partnerCarousel = document.getElementById("print_type_carousel");
            if (partnerCarousel) {
                carousel_html = partnerCarousel.innerHTML;
                if (!$(partnerCarousel).hasClass('slick-initialized')) {
                    carousel_sleek("carousel-items");
                }
                $(partnerCarousel).show();
            }
        });
    </script>
    <script type="text/javascript" src="{{ asset('feb/js/newhome.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.your-class').not('.slick-initialized').each(function() {
                var $slider = $(this);
                var itemCount = $slider.children().length;
                var isNewArrivalSlider = $slider.hasClass("new-arrival-products-slider");
                var desktopSlides = isNewArrivalSlider ? 4 : 6;

                if (!itemCount) {
                    return;
                }

                $slider.slick({
                    dots: itemCount > 6,
                    arrows: itemCount > 6,
                    infinite: itemCount > 6,
                    speed: 300,
                    slidesToShow: Math.min(desktopSlides, itemCount),
                    slidesToScroll: Math.min(desktopSlides, itemCount),
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: Math.min(4, itemCount),
                                slidesToScroll: Math.min(4, itemCount),
                                infinite: itemCount > 4,
                                dots: itemCount > 4
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: Math.min(3, itemCount),
                                slidesToScroll: Math.min(3, itemCount),
                                infinite: itemCount > 3,
                                dots: false,
                                arrows: itemCount > 3
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: Math.min(2, itemCount),
                                slidesToScroll: Math.min(2, itemCount),
                                infinite: itemCount > 2,
                                dots: false,
                                arrows: itemCount > 2
                            }
                        }
                    ]
                });
            });
        });
    </script>

</body>

</html>
