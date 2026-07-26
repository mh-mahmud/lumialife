@extends('front.feb.layouts.master')

@section('title', $product->name)

@section('content')
    @php
        $productImages = array_values(array_filter([
            $product->img_path,
            $product->img_path_2,
            $product->img_path_3,
            $product->img_path_4,
            $product->img_path_5,
            $product->img_path_6,
        ]));
        $hasDiscount = $product->discount_price > 0 && $product->discount_price < $product->product_value;
        $currentPrice = $hasDiscount ? $product->discount_price : $product->product_value;
        $discountPercent = $hasDiscount
            ? round((($product->product_value - $product->discount_price) / $product->product_value) * 100)
            : 0;
        $stockQuantity = max(0, (int) $product->stock_quantity);
        $isInStock = $product->stock_status !== 'Out of Stock' && $stockQuantity > 0;
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500&display=swap');
    </style>

    <style media="screen">
        .wrapper {
            text-align: center;
        }

        .wrapper .icon {
            position: relative;
            margin: 0px 10px;
            font-size: 22px;
            display: inline-block;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            color: #333;
            text-decoration: none;
        }

        .wrapper .icon.facebook:hover span {
            color: #3b5999;
        }

        .wrapper .icon.twitter:hover span {
            color: #46c1f6;
        }

        .wrapper .icon.instagram:hover span {
            color: #e1306c;
        }

        .wrapper .icon.youtube:hover span {
            color: #de463b;
        }


        input[type="number"] {
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .number-input {
            display: inline-flex;
            border: 1px solid #a1a1a1;
        }

        .number-input,
        .number-input * {
            box-sizing: border-box;
        }

        .number-input button {
            outline: none;
            -webkit-appearance: none;
            background-color: transparent;
            border: none;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            cursor: pointer;
            margin: 0;
            position: relative;
            box-shadow: none;
            font-size: 12px;
            background: #FFF;
            color: #000;
            outline: none !important;
        }

        .number-input input[type=number] {
            max-width: 4rem;
            padding: 0.5rem;
            border: none;
            font-size: 1rem;
            height: 2rem;
            text-align: center;
        }

        .add2cartContainer {
            display: inline-flex;
        }

        .btnAddToCart {
            width: auto;
            margin-left: 20px;
            background: #333;
            color: #FFF;
        }

        .btnAddToCart:hover,
        .btnAddToCart:active,
        .btnAddToCart:focus {
            background-color: #111 !important;
        }

        .btnOutOfStock {
            width: auto;
            background: #9e9e9e;
            color: #FFF;
        }

        .btnOutOfStock:hover,
        .btnOutOfStock:active,
        .btnOutOfStock:focus {
            background-color: #9e9e9e !important;
        }

        /* .product-details {
        background: #f8f8f8;
        padding: 15px;
        border-radius: 5px;
        box-shadow: rgb(50 50 93 / 25%) 0px 6px 12px -2px, rgb(0 0 0 / 30%) 0px 3px 7px -3px;
        font-size: .9rem;
        border: 1px solid #0000000f;
    } */

        .product-details {
            background: unset;
            padding: 0px;
            font-size: .9rem;
        }

        .thumbswitch {
            margin-top: 10px;
            padding: 0px 30px;
            text-align: center;
        }

        .thumbswitch img {
            width: 100px;
        }

        .thumbswitch .thumb {
            border: 1px solid #000;
            display: inline-block;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            opacity: 1 !important;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .affiliate_link_container {
            margin-top: 10px;
        }

        #affiliate_link {
            width: 100%;
            margin: 10px 0px;
        }

        ul.size-selectors-container {
            background-color: unset !Important;
            border: none !Important;
            border-radius: 0px !important;
            padding: 0px;
        }

        div.size-selectors-container {
            border-bottom: unset !Important;
        }

        div.size-selectors-container:hover {
            background: unset !important;
        }

        .Premium_pTag {
            display: none;
        }

        .size-selector {
            width: auto;
            min-width: 60px;
            padding: 5px;
            text-align: center;
            margin: 0px 10px 5px 0 !important;
            background-color: #fff;
            border: 1px solid #9e9e9e;
            font-size: 14px;
            cursor: pointer;
            -webkit-transition: 0.4s ease-in-out;
            transition: 0.1s ease-in-out;
            border-radius: 0px !Important;
        }

        .size-selector-selected {
            width: auto;
            min-width: 60px;
            padding: 5px;
            text-align: center;
            margin: 0px 10px 5px 0 !important;
            background-color: #000000;
            border: 1px solid #9e9e9e;
            color: #FFF;
            font-size: 14px;
            cursor: pointer;
            -webkit-transition: 0.4s ease-in-out;
            transition: 0.1s ease-in-out;
            border-radius: 0px !Important;
        }

        /* .self-product-description table {
        background: #ffffff;
        width: 100% !important;
        font-size: 13px;
        border-color: #f8f8f8;
        border-style: solid;
    } */

        .self-product-description table {
            background: #f3f3f3;
            width: 100% !important;
            font-size: 13px;
            border-color: #ffffff;
            border-style: solid;
        }

        .self-product-description table td,
        tr,
        th {
            border-style: none solid solid none;
        }

        .product-image-container {
            width: 100%;
            margin-left: 0;
            overflow: visible;
        }

        .color_names_container {
            margin-bottom: 12px;
        }

        .color_container {
            display: flex;
            width: fit-content;
        }

        .color_container>div {
            height: 20px;
            line-height: 20px;
            font-size: 14px;
            padding: 0px 5px;
        }

        .badges {
            top: 15px;
            left: 30px;
            position: absolute;
        }

        .badges>div {
            margin-bottom: 5px;
        }

        .sale,
        .free_delivery,
        .out_of_stock {
            color: #fff;
            font-size: .9rem;
            padding: 0px 10px 0 10px;
            width: fit-content;
            font-weight: 500;
            text-transform: uppercase;
            box-shadow: 0px 0px 9px -3px #00000087;
        }

        .sale {
            background: #f44336;
        }

        .free_delivery {
            background: #ff9800;
        }

        .out_of_stock {
            background: #b1b1b1;
        }

        .campaign {
            font-family: monospace;
            background-color: #FF3CAC;
            background-image: linear-gradient(225deg, #FF3CAC 0%, #784BA0 50%, #2B86C5 100%);
        }

        .out_of_stock_btn {
            background-color: #b1b1b1 !important;
            color: #fff;
        }

        .priceDiv {
            line-height: 1rem;
        }

        @media(max-width: 996px) {
            .badges {
                top: 10px;
                left: 25px;
            }

            .sale,
            .free_delivery {
                font-size: .7rem;
            }

            .wrapper-div {
                padding-top: 14px;
            }

            .thumbswitch img {
                width: 55px;
            }
        }

        input.form-control.ref-url-input {
            font-size: small;
            height: 30px;
            margin-right: 5px;
        }

        button.btn.btn-primary.ref-url-button {
            height: 30px;
            font-size: small;
            padding-top: 6px;
        }

        .ref-url-label {
            width: 100%;
            display: block;
            font-size: small;
            font-weight: bold;
        }

        .ref-url-container {
            margin-bottom: 10px;
        }

        /* =====================================================
       Product Details Card - Enhanced UI
       ===================================================== */
        .product-details-card {
            font-size: .9rem;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 18px 20px;
            background-color: #fff;
            margin-bottom: 16px;
        }

        .product-title-row {
            margin-bottom: 8px;
        }

        .product-title-row h4 {
            margin-bottom: 6px;
            line-height: 1.3;
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Price Area */
        .price-area {
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .price-row-main {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 10px;
        }

        .price-now {
            font-weight: 700;
            font-size: 26px;
            color: #000;
        }

        .price-old {
            font-size: 15px;
            color: #999;
            text-decoration: line-through;
        }

        .price-badge-off {
            font-size: 12px;
            font-weight: 600;
            color: #c00;
            background: #ffe9e9;
            border-radius: 4px;
            padding: 3px 8px;
        }

        .price-note {
            font-size: 13px;
            color: #2e7d32;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .price-note i {
            font-size: 14px;
        }

        .product-stock-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 12px;
            padding: 6px 10px;
            border-radius: 4px;
            background: #eaf7ed;
            color: #218838;
            font-size: 14px;
            font-weight: 600;
        }

        .product-stock-status.out-of-stock {
            background: #fbeaea;
            color: #c82333;
        }

        /* Size Picker */
        .size-picker-block {
            margin-top: 16px;
            margin-bottom: 16px;
        }

        .size-picker-label {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .no-size-selected {
            font-size: 12px;
            font-weight: 500;
        }

        .color-picker-block {
            margin-top: 16px;
        }

        .product-color-selectors {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .product-color-selector {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 36px;
            padding: 5px 10px;
            border: 1px solid #cfcfcf;
            border-radius: 4px;
            background: #fff;
            color: #222;
            cursor: pointer;
            font-size: 13px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .product-color-selector:hover,
        .product-color-selector.color-selector-selected {
            border-color: #000;
            box-shadow: 0 0 0 1px #000;
        }

        .product-color-swatch {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 50%;
        }

        /* Add to Cart Row */
        .add-cart-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
            margin-top: 10px;
        }

        .add-to-cart-container {
            display: table;
            position: relative;
        }

        .btnAddToCart,
        .btnOutOfStock {
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            padding: 9px 16px;
            display: inline-flex;
            align-items: center;
            line-height: 1.2;
            background: #000;
            color: #fff;
            border: 1px solid #000;
        }

        .btnAddToCart i {
            font-size: 13px;
        }

        .btnAddToCart:disabled {
            cursor: wait;
            opacity: .7;
        }

        .product-cart-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10080;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 360px;
            padding: 12px 16px;
            border-radius: 6px;
            background: #1f2937;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
            font-size: 14px;
            animation: productCartToastIn .2s ease-out;
        }

        .product-cart-toast.success { background: #16803c; }
        .product-cart-toast.error { background: #c62828; }

        @keyframes productCartToastIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Section Divider */
        .section-divider {
            border-top: 1px solid #e5e5e5;
            margin: 16px 0;
        }

        /* Shipping / Returns Card */
        .shipping-card {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #fafafa;
            padding: 14px 16px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .shipping-toggle {
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .shipping-toggle .label-left {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #000;
        }

        .shipping-toggle .label-left i.check {
            color: #2e7d32;
            font-size: 13px;
        }

        .shipping-toggle .chevron {
            font-size: 14px;
            transition: transform .2s;
        }

        .shipping-toggle.open .chevron {
            transform: rotate(90deg);
        }

        .shipping-highlights {
            margin-top: 10px;
            margin-bottom: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            color: #444;
        }

        .shipping-highlights div {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            line-height: 1.3;
        }

        .shipping-highlights i {
            color: #2e7d32;
            font-size: 12px;
        }

        .shipping-policy {
            display: none;
            border-top: 1px solid #ddd;
            margin-top: 12px;
            padding-top: 12px;
            font-size: 12px;
            color: #444;
        }

        .shipping-policy p,
        .shipping-policy li {
            font-size: 12px;
            line-height: 1.5;
            color: #444;
            margin-bottom: 6px;
        }

        .shipping-policy ul {
            padding-left: 18px;
            margin-bottom: 10px;
        }

        .shipping-policy strong {
            color: #000;
        }

        .shipping-policy .policy-section {
            margin-bottom: 12px;
        }

        .shipping-policy .policy-section-title {
            font-weight: 600;
            color: #000;
            margin-bottom: 4px;
        }

        /* =====================================================
       Image Lightbox Zoom - Click to view detailed image
       ===================================================== */

        /* Zoom hint on product image */
        .product-image-container {
            position: relative;
            cursor: zoom-in;
        }

        .zoom-hint {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
            z-index: 10;
        }

        .product-image-container:hover .zoom-hint {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .zoom-hint {
                opacity: 0.9;
                padding: 5px 10px;
                font-size: 11px;
                bottom: 10px;
                right: 10px;
            }
        }

        /* Lightbox overlay */
        .image-lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .image-lightbox.active {
            display: flex;
        }

        /* Close button */
        .lightbox-close {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
            z-index: 100002;
        }

        .lightbox-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Image container with pan/zoom */
        .lightbox-image-wrapper {
            position: relative;
            max-width: 90vw;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-image {
            max-width: 90vw;
            max-height: 80vh;
            object-fit: contain;
            transform-origin: center center;
            transition: transform 0.15s ease-out;
            cursor: grab;
            user-select: none;
            -webkit-user-drag: none;
        }

        .lightbox-image.dragging {
            cursor: grabbing;
            transition: none;
        }

        /* Zoom controls */
        .lightbox-controls {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 100001;
        }

        .lightbox-controls button {
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-controls button:hover:not(:disabled) {
            background: #fff;
            transform: scale(1.05);
        }

        .lightbox-controls button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Zoom level indicator */
        .lightbox-zoom-level {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
            z-index: 100001;
        }

        .lightbox-zoom-level.visible {
            opacity: 1;
        }

        /* Mobile instructions */
        .lightbox-instructions {
            position: absolute;
            bottom: 130px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            text-align: center;
            pointer-events: none;
            animation: fadeInOut 4s ease-in-out forwards;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            85% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        /* Thumbnail navigation in lightbox */
        .lightbox-thumbnails {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
        }

        .lightbox-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 2px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.2s ease;
        }

        .lightbox-thumb:hover {
            opacity: 0.9;
        }

        .lightbox-thumb.active {
            border-color: #fff;
            opacity: 1;
        }

        /* Reset button */
        .lightbox-reset {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }

        .lightbox-reset:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        /* Wishlist Button - Inline Style for Product Details */
        .btn-wishlist-inline {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f8f8f8;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            transition: all 0.2s ease;
        }

        .btn-wishlist-inline:hover {
            background: #fff0f0;
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .btn-wishlist-inline.active {
            background: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
        }

        .btn-wishlist-inline i {
            font-size: 18px;
            pointer-events: none;
        }

        .related-discount-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 3;
            padding: 4px 8px;
            border-radius: 4px;
            background: #e53935;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
        }

        .related-cart-modal {
            position: fixed;
            inset: 0;
            z-index: 10060;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding: 30px 14px;
            background: rgba(0, 0, 0, .50);
        }

        .related-cart-modal.is-open {
            display: flex;
        }

        .related-cart-dialog {
            width: min(380px, 100%);
            max-height: calc(100vh - 60px);
            overflow-y: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 18px 55px rgba(0, 0, 0, .28);
        }

        .related-cart-header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 52px;
            padding: 10px 18px;
            border-bottom: 0;
            background: #26354a;
            color: #fff;
            text-align: center;
        }

        .related-cart-header strong {
            flex: 1;
            font-size: 15px;
        }

        .related-cart-close {
            position: absolute;
            top: 11px;
            right: 12px;
            border: 0;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .09);
            color: #fff;
            cursor: pointer;
            font-size: 19px;
        }

        .related-cart-body {
            padding: 0;
        }

        .related-cart-product {
            display: block;
            align-items: center;
            margin: 0;
            padding: 16px 20px 18px;
            border-bottom: 1px solid #e2e5e9;
            text-align: center;
        }

        .related-cart-product img {
            display: block;
            width: 240px;
            max-width: 100%;
            height: 240px;
            margin: 0 auto 10px;
            border: 0;
            border-radius: 6px;
            object-fit: contain;
        }

        .related-cart-product strong {
            display: block;
            color: #111827;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
        }

        .related-cart-options {
            padding: 2px 20px 18px;
        }

        .related-cart-option-group {
            margin-top: 17px;
        }

        .related-cart-option-group label {
            display: block;
            margin-bottom: 9px;
            color: #192235;
            font-size: 12px;
            font-weight: 500;
        }

        .related-cart-option-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .related-cart-option {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 39px;
            min-height: 36px;
            padding: 7px 11px;
            border: 1px solid #d2d7df;
            border-radius: 4px;
            background: #fff;
            color: #374151;
            cursor: pointer;
            font-size: 12px;
        }

        .related-cart-option.is-selected {
            border-color: #111827;
            background: #111827;
            color: #fff;
        }

        .related-cart-color-dot {
            display: inline-block;
            width: 18px;
            height: 18px;
            margin-right: 7px;
            border: 1px solid rgba(0, 0, 0, .10);
            border-radius: 50%;
            flex: 0 0 18px;
        }

        .related-cart-quantity {
            width: 100%;
            height: 44px;
            padding: 8px 11px;
            border: 1px solid #d2d7df;
            border-radius: 4px;
        }

        .related-cart-footer {
            display: block;
            padding: 14px 20px;
            border-top: 1px solid #e5e7eb;
        }

        .related-cart-confirm {
            width: 100%;
            min-height: 43px;
            border: 0;
            border-radius: 7px;
            padding: 10px 18px;
            background: #111827;
            color: #fff;
            cursor: pointer;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .btn-wishlist-inline {
                width: 38px;
                height: 38px;
            }

            .btn-wishlist-inline i {
                font-size: 16px;
            }
        }
    </style>
    <div class="wrapper-div">
        <div class="container">
        </div>
        <div class="container-fluid">

            <div class="home-element" style="margin-top: 30px">
                <div class="col-sm-12 col-md-6 col-lg-6 productfixed">
                    <div class="product-image-container">
                        @forelse($productImages as $imageIndex => $image)
                            <img data-img_url="{{ $image }}"
                                class="elZoom product-image {{ $imageIndex === 0 ? 'tee-ui-front' : '' }}"
                                style="{{ $imageIndex === 0 ? '' : 'display:none' }}"
                                src="{{ \App\Support\MediaStorage::url($image, 'products') }}"
                                alt="{{ $product->name }}" />
                        @empty
                            <img class="product-image tee-ui-front" src="{{ asset('uploads/blank.png') }}"
                                alt="{{ $product->name }}" />
                        @endforelse

                        @if($hasDiscount)
                            <div class="badges"><div class="sale"><span>{{ $discountPercent }}% OFF</span></div></div>
                        @endif
                        <div class="zoom-hint">
                            <i class="fa fa-search-plus"></i>
                            <span>Click to zoom</span>
                        </div>
                    </div>

                    <div class="thumbswitch">
                        @foreach($productImages as $image)
                            <div data-img_url="{{ $image }}" class="imageSwitch thumb">
                                <img src="{{ \App\Support\MediaStorage::url($image, 'products') }}" alt="{{ $product->name }}" />
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <div class="alert-container"></div>

                    {{-- <div class="product-related hidden-sm-down">
                        <h5 class="tiny-margin">Frequently Bought Together</h5>
                        <hr style="margin-top: -10px; border-top: 1px solid #000;">
                        <div class="row" style="margin: -7px; padding: 0px;">
                            <div class="" style="display:flex">
                                <a class="product-link"
                                    href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight">
                                    <img class="lazy" style="width: 170px"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                        alt="Premium Designer Edition Double PK Cotton Polo - Woodlight" loading="lazy">
                                </a>
                                <div style="margin-left: 20px">
                                    <div><a class="product-link"
                                            href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight">
                                            <h6>Premium Designer Edition Double PK Cotton Polo - Woodlight</h6>
                                    </div></a>
                                    <div>
                                        <div>
                                            <strong>৳ 1140.00</strong> <strike>৳
                                                1490.00</strike>
                                        </div>
                                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                                            style="margin-top: 15px"
                                            href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight"
                                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="73082"
                                            data-color="#ffffff"
                                            data-title="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="product-details">
                        <div class="product-details-card">

                            <div class="product-title-row"
                                style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                                <h4 class="tiny-margin" style="flex: 1; margin-bottom: 0;">{{ $product->name }}</h4>
                                <button class="btn-wishlist-inline" data-wishlist-btn data-product-id="{{ $product->id }}"
                                    title="Add to Wishlist">
                                    <i class="fa fa-heart-o"></i>
                                </button>
                            </div>




                            <div class="price-area">
                                <div class="price-row-main">
                                    <div class="price-now">
                                        <span class="price_field">{{ $febCurrency->format($currentPrice) }}</span>
                                    </div>
                                    @if($hasDiscount)
                                        <div class="price-old">
                                            <span class="regular_price_field">{{ $febCurrency->format($product->product_value) }}</span>
                                        </div>
                                        <div class="price-badge-off">{{ $discountPercent }}% Off</div>
                                    @endif
                                </div>


                            </div>

                            <div class="product-stock-status{{ $isInStock ? '' : ' out-of-stock' }}">
                                <i class="fa {{ $isInStock ? 'fa-check-circle' : 'fa-times-circle' }}"
                                    aria-hidden="true"></i>
                                @if($isInStock)
                                    <span>In Stock: {{ $stockQuantity }} item{{ $stockQuantity === 1 ? '' : 's' }} available</span>
                                @else
                                    <span>Out of Stock</span>
                                @endif
                            </div>


                            @if($product->productColors->isNotEmpty())
                                <div class="color-picker-block">
                                    <div class="size-picker-label">Select Color:</div>
                                    <div class="product-color-selectors">
                                        @foreach($product->productColors as $color)
                                            <button type="button" class="product-color-selector"
                                                data-color-id="{{ $color->id }}" data-color="{{ $color->hex_code ?: $color->name }}"
                                                aria-label="Select {{ $color->name }} color">
                                                <span class="product-color-swatch"
                                                    style="background-color: {{ $color->hex_code ?: '#f5f5f5' }};"></span>
                                                <span>{{ $color->name }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($product->productSizes->isNotEmpty())
                                <div class="size-picker-block">
                                    <div class="size-picker-label">Select Size:</div>
                                    <div class="size-selectors-container list-inline" style="padding: 0;">
                                        @foreach($product->productSizes as $size)
                                            <button type="button" class="size-selector list-inline-item"
                                                data-productid="{{ $product->id }}" data-size-id="{{ $size->id }}"
                                                data-size="{{ $size->name }}">
                                                {{ $size->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <span class="no-size-selected" style="color:rgb(230, 28, 28); display:none;">Please
                                        select a size &nbsp;<i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                                </div>
                            @endif


                            <div class="add-to-cart-container">
                                <div class="add-cart-row">
                                    @if($isInStock)
                                        <div class="number-input">
                                            <button class="quantity-selector-step"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()"><i
                                                    class="fa fa-minus"></i></button>
                                            <input class="quantity qty quantity-selector" data-productid="{{ $product->id }}"
                                                min="1" max="{{ $stockQuantity }}" name="quantity" value="1" type="number">
                                            <button class="quantity-selector-step"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()"><i
                                                    class="fa fa-plus"></i></button>
                                        </div>
                                        <button class="btnAddToCart btn btn-success btn-block main-btnAddToCart"
                                            data-photo="tee-ui-front" data-productid="{{ $product->id }}" data-color=""
                                            data-size="" data-quantity="1"><i class="fa fa-plus"></i> &nbsp;Add To
                                            Cart</button>
                                    @else
                                        <button class="btnOutOfStock out_of_stock_btn btn btn-block" type="button" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="section-divider"></div>


                            <div class="shipping-card">
                                <div class="shipping-toggle">
                                    <div class="label-left">
                                        <i class="fa fa-check check" aria-hidden="true"></i>
                                        <strong>Easy Returns & Exchange</strong>
                                    </div>
                                    <div class="chevron">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </div>
                                </div>


                                <div class="shipping-highlights">
                                    <div><i class="fa fa-check-circle"></i><span>Tell us within 7 days</span></div>
                                    <div><i class="fa fa-check-circle"></i><span>Free return shipping*</span></div>
                                    <div><i class="fa fa-check-circle"></i><span>Instant refund on receipt</span></div>
                                </div>


                                <div class="shipping-policy">
                                    <p>Your satisfaction is our priority. If something isn't right with your order,
                                        returning it is simple.</p>

                                    <div class="policy-section">
                                        <div class="policy-section-title">Return Window</div>
                                        <p>Request a return within <strong>7 days</strong> of receiving your order.</p>
                                    </div>

                                    <div class="policy-section">
                                        <div class="policy-section-title">Free Return Shipping</div>
                                        <p>We cover return shipping for defective products, size/color mismatch, print
                                            issues, or wrong item sent.</p>
                                    </div>

                                    <div class="policy-section">
                                        <div class="policy-section-title">How to Return</div>
                                        <ul>
                                            <li>Call our hotline <a href="tel:+8809677666888">+8809677666888</a>, email
                                                <a href="mailto:support@fabrilife.com">support@fabrilife.com</a>, or
                                                message us on <a href="https://www.facebook.com/fabrilife"
                                                    target="_blank">Facebook</a>
                                            </li>
                                            <li>Items must be unused, unwashed, with original tags and packaging</li>
                                            <li>We'll arrange pickup for eligible returns</li>
                                        </ul>
                                    </div>

                                    <div class="policy-section">
                                        <div class="policy-section-title">Refunds</div>
                                        <p>Once we receive your return, refunds are processed within <strong>1 business
                                                day</strong> to your original payment method.</p>
                                    </div>

                                    <div class="policy-section">
                                        <div class="policy-section-title">Our Promise</div>
                                        <p>We stand behind our products. In rare cases, we may issue a refund without
                                            requiring return — because your trust matters most.</p>
                                    </div>

                                    <p style="margin-top: 12px; text-align: center;"><a href="/refund-policy"
                                            style="color: #1a73e8; text-decoration: underline;">View Full Return &
                                            Refund Policy</a></p>
                                </div>
                            </div>

                            <div class="section-divider"></div>



                            <!-- Show description from json instead of from description -->
                            <div class='self-product-description type-description' style='padding: 0 0 15px 0'>
                                {!! $product->description !!}
                            </div>

                            @php
                                $sizeChartRows = collect($product->size_chart_rows ?? [])->filter(fn ($row) => !empty($row['size']));
                                $sizeChartColumns = $product->size_chart_columns ?? ['Size', 'Chest (round)', 'Length', 'Sleeve'];
                            @endphp
                            @if($sizeChartRows->isNotEmpty())
                                <div class="dynamic-size-chart" data-size-chart>
                                    <p class="dynamic-size-chart__title"><strong>{{ $product->size_chart_title ?: 'Size chart - In Inches (Expected Deviation < 3%)' }}</strong></p>
                                    <div class="dynamic-size-chart__tabs" role="tablist">
                                        <button type="button" class="active" data-size-unit="inch" aria-selected="true">INCH</button>
                                        <button type="button" data-size-unit="cm" aria-selected="false">CM</button>
                                    </div>
                                    <div class="dynamic-size-chart__table-wrap" data-size-panel="inch">
                                        <table class="dynamic-size-chart__table">
                                            <thead><tr>@foreach($sizeChartColumns as $column)<th>{{ $column }}</th>@endforeach</tr></thead>
                                            <tbody>
                                                @foreach($sizeChartRows as $row)
                                                    <tr>
                                                        <td>{{ $row['size'] }}</td>
                                                        @foreach(['chest', 'length', 'sleeve'] as $measurement)
                                                            <td>{{ rtrim(rtrim(number_format((float) $row[$measurement], 2, '.', ''), '0'), '.') }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="dynamic-size-chart__table-wrap" data-size-panel="cm" hidden>
                                        <table class="dynamic-size-chart__table">
                                            <thead><tr>@foreach($sizeChartColumns as $column)<th>{{ $column }}</th>@endforeach</tr></thead>
                                            <tbody>
                                                @foreach($sizeChartRows as $row)
                                                    <tr>
                                                        <td>{{ $row['size'] }}</td>
                                                        @foreach(['chest', 'length', 'sleeve'] as $measurement)
                                                            <td>{{ rtrim(rtrim(number_format((float) $row[$measurement] * 2.54, 1, '.', ''), '0'), '.') }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <style>
                                    .dynamic-size-chart{margin:18px 0 24px;max-width:100%}.dynamic-size-chart__title{margin:0 0 15px}.dynamic-size-chart__tabs{display:flex;align-items:end;border-bottom:1px solid #d7d7d7;margin-bottom:10px}.dynamic-size-chart__tabs button{min-width:58px;height:39px;border:1px solid #d7d7d7!important;border-bottom:0!important;border-radius:4px 4px 0 0;background:#fff!important;padding:0 14px;color:#333!important;font-size:12px;cursor:pointer}.dynamic-size-chart__tabs button+button{margin-left:4px}.dynamic-size-chart__tabs button.active{background:#eee!important;font-weight:700}.dynamic-size-chart__table-wrap[hidden]{display:none!important}.dynamic-size-chart__table-wrap{max-width:100%;overflow-x:auto}.dynamic-size-chart__table{width:100%;min-width:480px;border:0!important;border-collapse:separate!important;border-spacing:2px!important;font-size:12px}.dynamic-size-chart__table th,.dynamic-size-chart__table td{border:0!important;background:#f1f1f1;padding:8px 7px!important;text-align:left}.dynamic-size-chart__table th{background:#e9e9e9;font-weight:700}@media(max-width:575px){.dynamic-size-chart__title{font-size:13px}.dynamic-size-chart__table{min-width:430px}}
                                </style>
                                <script>
                                    document.querySelectorAll('[data-size-chart]').forEach(function (chart) {
                                        chart.querySelectorAll('[data-size-unit]').forEach(function (button) {
                                            button.addEventListener('click', function () {
                                                const unit = button.dataset.sizeUnit;
                                                chart.querySelectorAll('[data-size-unit]').forEach(function (item) {
                                                    const selected = item === button;
                                                    item.classList.toggle('active', selected);
                                                    item.setAttribute('aria-selected', selected ? 'true' : 'false');
                                                });
                                                chart.querySelectorAll('[data-size-panel]').forEach(panel => panel.hidden = panel.dataset.sizePanel !== unit);
                                            });
                                        });
                                    });
                                </script>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="wrapper" style="display:none">

                        <div class="fb-share-button"
                            data-href="https://fabrilife.com/product/{{ $product->slug }}"
                            data-layout="button" data-size="small"><a target="_blank"
                            href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse"
                            class="fb-xfbml-parse-ignore">Share</a></div>
                    <a href="#" class="icon twitter">
                        <span><i class="fa fa-twitter"></i></span>
                    </a>
                    <a href="#" class="icon instagram">
                        <span><i class="fa fa-instagram"></i></span>
                    </a>

                </div>


                <div class="product-related hidden-md-up">
                    <h5 class="tiny-margin">Frequently Bought Together</h5>
                    <hr style="margin-top: -10px; border-top: 1px solid #000;">
                    <div class="row" style="margin: -7px; padding: 0px;">
                        <div class="" style="display:flex">
                            <a class="product-link"
                                href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight">
                                <img class="lazy" style="width: 170px"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/66c1f1a693459-square.jpg') }}"
                                    alt="Premium Designer Edition Double PK Cotton Polo - Woodlight" loading="lazy">
                            </a>
                            <div style="margin-left: 20px">
                                <div><a class="product-link"
                                        href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight">
                                        <h6>Premium Designer Edition Double PK Cotton Polo - Woodlight</h6>
                                </div></a>
                                <div>
                                    <div>
                                        <strong>৳ 1140.00</strong> <strike>৳
                                            1490.00</strike>
                                    </div>
                                    <button class="add2cartModal related_product_view btn btn-black btn-sm"
                                        style="margin-top: 15px"
                                        href="/product/73082-premium-designer-edition-double-pk-cotton-polo-woodlight"
                                        data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="73082"
                                        data-color="#ffffff"
                                        data-title="Premium Designer Edition Double PK Cotton Polo - Woodlight"
                                        data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                            class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-related home-element" style="margin-top: 30px;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h5 class="tiny-margin">You may also like</h5>
                <hr style="margin-top: -10px; border-top: 1px solid #000;">
                <div class="row" style="margin: -7px; padding: 0px;">
                    @forelse($relatedProducts as $relatedProduct)
                        @php
                            $relatedHasDiscount = $relatedProduct->discount_price > 0
                                && $relatedProduct->discount_price < $relatedProduct->product_value;
                            $relatedDiscountPercent = $relatedHasDiscount
                                ? round((($relatedProduct->product_value - $relatedProduct->discount_price) / $relatedProduct->product_value) * 100)
                                : 0;
                        @endphp
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">
                            <div class="home-product">
                                @if($relatedHasDiscount)
                                    <span class="related-discount-badge">{{ $relatedDiscountPercent }}% OFF</span>
                                @endif
                                <a class="product-link" href="{{ route('single-product', $relatedProduct->slug) }}">
                                    <img class="lazy"
                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                        data-src="{{ \App\Support\MediaStorage::url($relatedProduct->img_path, 'products') }}"
                                        alt="{{ $relatedProduct->name }}" loading="lazy">
                                </a>
                                <div class="product-info">
                                    <div class="product-name">{{ $relatedProduct->name }}</div>
                                </div>
                                <div class="product-price">
                                    <div>
                                        <strong>{{ $febCurrency->format($relatedHasDiscount ? $relatedProduct->discount_price : $relatedProduct->product_value) }}</strong>
                                        @if($relatedHasDiscount)
                                            <strike>{{ $febCurrency->format($relatedProduct->product_value) }}</strike>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-black btn-sm related-add-cart"
                                style="margin-top: 15px; width: 100%"
                                data-product-id="{{ $relatedProduct->id }}"
                                data-title="{{ $relatedProduct->name }}"
                                data-image="{{ \App\Support\MediaStorage::url($relatedProduct->img_path, 'products') }}"
                                data-stock="{{ max(0, (int) $relatedProduct->stock_quantity) }}"
                                data-colors="{{ $relatedProduct->productColors->map(fn ($color) => ['id' => $color->id, 'name' => $color->name, 'hex_code' => $color->hex_code])->values()->toJson() }}"
                                data-sizes="{{ $relatedProduct->productSizes->map(fn ($size) => ['id' => $size->id, 'name' => $size->name])->values()->toJson() }}"
                                {{ $relatedProduct->stock_status === 'Out of Stock' || (int) $relatedProduct->stock_quantity < 1 ? 'disabled' : '' }}>
                                <i class="fa fa-plus"></i>&nbsp;
                                {{ $relatedProduct->stock_status === 'Out of Stock' || (int) $relatedProduct->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
                            </button>
                        </div>
                    @empty
                        <div class="col-12 text-center">No related products available.</div>
                    @endforelse

                    <div class="related-cart-modal" id="relatedCartModal" aria-hidden="true">
                        <div class="related-cart-dialog" role="dialog" aria-modal="true" aria-labelledby="relatedCartTitle">
                            <div class="related-cart-header">
                                <strong id="relatedCartTitle">Select Options</strong>
                                <button type="button" class="related-cart-close" data-related-cart-close>&times;</button>
                            </div>
                            <div class="related-cart-body" id="relatedCartBody"></div>
                            <div class="related-cart-footer">
                                <button type="button" class="related-cart-confirm" id="relatedCartConfirm">Add to Cart</button>
                            </div>
                        </div>
                    </div>

                    @if(false)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71910-single-jersey-knitted-cotton-polo-navy">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6526a0ac21c13-square.jpg') }}"
                                    alt="Single Jersey Knitted Cotton Polo - Navy" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Single Jersey Knitted Cotton Polo - Navy</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 750.00</strong> <strike>৳
                                        980.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71910-single-jersey-knitted-cotton-polo-navy"
                            data-photo="{{ asset('feb/products/6526a0ac21c13-square.jpg') }}" data-productid="71910" data-color="#ffffff"
                            data-title="Single Jersey Knitted Cotton Polo - Navy"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/72321-premium-elite-edition-double-pk-cotton-polo-green">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/683db934b64f2-square.jpg') }}"
                                    alt="Premium Elite Edition Double PK Cotton Polo - Green" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Premium Elite Edition Double PK Cotton Polo - Green</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1180.00</strong> <strike>৳
                                        1410.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/72321-premium-elite-edition-double-pk-cotton-polo-green"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="72321" data-color="#ffffff"
                            data-title="Premium Elite Edition Double PK Cotton Polo - Green"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/73084-premium-designer-edition-double-pk-cotton-polo-marine">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/650c5a9a10c63-square.jpg') }}"
                                    alt="Premium Designer Edition Double PK Cotton Polo - Marine" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Premium Designer Edition Double PK Cotton Polo - Marine</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1140.00</strong> <strike>৳
                                        1490.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/73084-premium-designer-edition-double-pk-cotton-polo-marine"
                            data-photo="{{ asset('feb/products/67285487491ac-square.jpg') }}" data-productid="73084" data-color="#ffffff"
                            data-title="Premium Designer Edition Double PK Cotton Polo - Marine"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/73081-premium-designer-edition-double-pk-cotton-polo-marooned">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6a2541ec656cf-square.jpg') }}"
                                    alt="Premium Designer Edition Double PK Cotton Polo - Marooned" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Premium Designer Edition Double PK Cotton Polo - Marooned
                                </div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1140.00</strong> <strike>৳
                                        1490.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/73081-premium-designer-edition-double-pk-cotton-polo-marooned"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="73081" data-color="#ffffff"
                            data-title="Premium Designer Edition Double PK Cotton Polo - Marooned"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/72876-mens-premium-pajama-stretch-white">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6a02c4fc163f9-square.jpg') }}"
                                    alt="Mens Premium Pajama [Stretch] - White" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Pajama [Stretch] - White</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1090.00</strong> <strike>৳
                                        1290.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/72876-mens-premium-pajama-stretch-white"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="72876" data-color="#ffffff"
                            data-title="Mens Premium Pajama [Stretch] - White"
                            data-sizes="[&quot;M(28-30)&quot;,&quot;L(32-34)&quot;,&quot;XL(36-38)&quot;,&quot;2XL(38-42)&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/73926-premium-designer-edition-double-pk-cotton-polo-altair">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6a2405312e0d6-square.jpg ') }}"
                                    alt="Premium Designer Edition Double PK Cotton Polo - Altair" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Premium Designer Edition Double PK Cotton Polo - Altair</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1260.00</strong> <strike>৳
                                        1600.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/73926-premium-designer-edition-double-pk-cotton-polo-altair"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="73926" data-color="#ffffff"
                            data-title="Premium Designer Edition Double PK Cotton Polo - Altair"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71491-mens-premium-blank-t-shirt-black">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6832e7b9ea976-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - Black" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Black</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71491-mens-premium-blank-t-shirt-black"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71491" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Black"
                            data-sizes="[&quot;XS&quot;,&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71485-mens-premium-blank-t-shirt-navy">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6a265c51279d1-square.jpg') }}" alt="Mens Premium Blank T-shirt - Navy"
                                    loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Navy</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71485-mens-premium-blank-t-shirt-navy"
                            data-photo="{{ asset('feb/products/61507e020791f-square.jpg') }}" data-productid="71485" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Navy"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71905-single-jersey-knitted-cotton-polo-black">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6571b0b9c97ae-square.jpg') }}"
                                    alt="Single Jersey Knitted Cotton Polo - Black" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Single Jersey Knitted Cotton Polo - Black</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 750.00</strong> <strike>৳
                                        980.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71905-single-jersey-knitted-cotton-polo-black"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71905" data-color="#ffffff"
                            data-title="Single Jersey Knitted Cotton Polo - Black"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71492-mens-premium-blank-t-shirt-white">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/699d46709e758-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - White" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - White</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71492-mens-premium-blank-t-shirt-white"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71492" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - White"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71671-mens-premium-blank-t-shirt-chocolate">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/699d46709e758-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt -Chocolate" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt -Chocolate</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71671-mens-premium-blank-t-shirt-chocolate"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71671" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt -Chocolate"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/73417-premium-designer-edition-double-pk-cotton-polo-lavish">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6939455cc3abe-square.png') }}"
                                    alt="Premium Designer Edition Double PK Cotton Polo - Lavish" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Premium Designer Edition Double PK Cotton Polo - Lavish</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1140.00</strong> <strike>৳
                                        1490.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/73417-premium-designer-edition-double-pk-cotton-polo-lavish"
                            data-photo="{{ asset('feb/products/68a71d21f22cf-square.jpg') }}" data-productid="73417" data-color="#ffffff"
                            data-title="Premium Designer Edition Double PK Cotton Polo - Lavish"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                            class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="product-related home-element" style="margin-top: 30px;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h5 class="tiny-margin">People also purchased</h5>
                <hr style="margin-top: -10px; border-top: 1px solid #000;">
                <div class="row" style="margin: -7px; padding: 0px;">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/72876-mens-premium-pajama-stretch-white">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                    alt="Mens Premium Pajama [Stretch] - White" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Pajama [Stretch] - White</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 1090.00</strong> <strike>৳
                                        1290.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/72876-mens-premium-pajama-stretch-white"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="72876" data-color="#ffffff"
                            data-title="Mens Premium Pajama [Stretch] - White"
                            data-sizes="[&quot;M(28-30)&quot;,&quot;L(32-34)&quot;,&quot;XL(36-38)&quot;,&quot;2XL(38-42)&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71491-mens-premium-blank-t-shirt-black">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/699d46709e758-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - Black" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Black</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71491-mens-premium-blank-t-shirt-black"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71491" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Black"
                            data-sizes="[&quot;XS&quot;,&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71485-mens-premium-blank-t-shirt-navy">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6939455cc3abe-square.png') }}" alt="Mens Premium Blank T-shirt - Navy"
                                    loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Navy</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71485-mens-premium-blank-t-shirt-navy"
                            data-photo="{{ asset('feb/products/61507e020791f-square.jpg') }}" data-productid="71485" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Navy"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71492-mens-premium-blank-t-shirt-white">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6571b0b9c97ae-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - White" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - White</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71492-mens-premium-blank-t-shirt-white"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71492" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - White"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71671-mens-premium-blank-t-shirt-chocolate">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/6526a0ac21c13-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt -Chocolate" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt -Chocolate</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71671-mens-premium-blank-t-shirt-chocolate"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71671" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt -Chocolate"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71672-mens-premium-blank-t-shirt-anthra-melange">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/659ec7ce127a4-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - Anthra-Melange" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Anthra-Melange</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71672-mens-premium-blank-t-shirt-anthra-melange"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71672" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Anthra-Melange"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/72946-mens-premium-antibacterial-boxer-brief-laurel-oak">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/68f4c0b04d1a5-square.jpg') }}"
                                    alt="Mens Premium Antibacterial Boxer Brief - Laurel Oak" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Antibacterial Boxer Brief - Laurel Oak</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 399.00</strong> <strike>৳
                                        520.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/72946-mens-premium-antibacterial-boxer-brief-laurel-oak"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="72946" data-color="#FFFFFF"
                            data-title="Mens Premium Antibacterial Boxer Brief - Laurel Oak"
                            data-sizes="[&quot;M&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i class="fa fa-plus"></i>&nbsp;
                            Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/72109-mens-premium-trouser-carbon">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/68f4c0b05ff3e-square.jpg') }}" alt="Mens Premium Trouser - Carbon"
                                    loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Trouser - Carbon</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 990.00</strong> <strike>৳
                                        1290.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/72109-mens-premium-trouser-carbon"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="72109" data-color="#ffffff"
                            data-title="Mens Premium Trouser - Carbon"
                            data-sizes="[&quot;M(28-30)&quot;,&quot;L(32-34)&quot;,&quot;XL(36-38)&quot;,&quot;2XL(38-42)&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link"
                                href="/product/72063-mens-premium-antibacterial-boxer-brief-stellar">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}"
                                    alt="Mens Premium Antibacterial Boxer Brief - Stellar" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Antibacterial Boxer Brief - Stellar</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 399.00</strong> <strike>৳
                                        520.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/72063-mens-premium-antibacterial-boxer-brief-stellar"
                            data-photo="{{ asset('feb/products/6a04348775a5e-square.jpg') }}" data-productid="72063" data-color="#FFFFFF"
                            data-title="Mens Premium Antibacterial Boxer Brief - Stellar"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71666-mens-premium-blank-t-shirt-olive">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/69af931d2ed9c-square.jpg') }}" alt="Mens Premium Blank T-shirt- Olive"
                                    loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt- Olive</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%" href="/product/71666-mens-premium-blank-t-shirt-olive"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71666" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt- Olive"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;]"><i class="fa fa-plus"></i>&nbsp;
                            Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71947-mens-premium-antibacterial-boxer-brief-black">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/69afaa906d865-square.jpg') }}"
                                    alt="Mens Premium Antibacterial Boxer Brief - Black" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Antibacterial Boxer Brief - Black</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 399.00</strong> <strike>৳
                                        520.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71947-mens-premium-antibacterial-boxer-brief-black"
                            data-photo="{{ asset('feb/products/6a04348775a5e-square.jpg') }}" data-productid="71947" data-color="#FFFFFF"
                            data-title="Mens Premium Antibacterial Boxer Brief - Black"
                            data-sizes="[&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-xs-6">

                        <div class="home-product">
                            <a class="product-link" href="/product/71490-mens-premium-blank-t-shirt-maroon">
                                <img class="lazy"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                    data-src="{{ asset('feb/products/69b5574a2e269-square.jpg') }}"
                                    alt="Mens Premium Blank T-shirt - Maroon" loading="lazy">
                            </a>
                            <div class="product-info">
                                <div class="product-name">Mens Premium Blank T-shirt - Maroon</div>
                            </div>
                            <div class="product-price">
                                <div>
                                    <strong>৳ 485.00</strong> <strike>৳
                                        640.00</strike>
                                </div>
                            </div>
                        </div>
                        <button class="add2cartModal related_product_view btn btn-black btn-sm"
                            style="margin-top: 15px; width: 100%"
                            href="/product/71490-mens-premium-blank-t-shirt-maroon"
                            data-photo="{{ asset('feb/products/66c1f1a693459-square.jpg') }}" data-productid="71490" data-color="#ffffff"
                            data-title="Mens Premium Blank T-shirt - Maroon"
                            data-sizes="[&quot;S&quot;,&quot;M&quot;,&quot;L&quot;,&quot;XL&quot;,&quot;2XL&quot;]"><i
                                class="fa fa-plus"></i>&nbsp; Add to Cart</button>
                    </div>
                </div>
            </div>
        </div> --}}

        <br><br>

        <!-- Image Lightbox Structure -->
        <div class="image-lightbox" id="imageLightbox">
            <button class="lightbox-close" id="lightboxClose" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
            <div class="lightbox-image-wrapper">
                <img class="lightbox-image" id="lightboxImage" src="" alt="Product Image" />
            </div>
            <div class="lightbox-zoom-level" id="lightboxZoomLevel">100%</div>
            <div class="lightbox-controls">
                <button class="lightbox-btn" id="lightboxZoomOut" title="Zoom out">
                    <i class="fa fa-minus"></i>
                </button>
                <button class="lightbox-reset" id="lightboxReset" title="Reset zoom">
                    <i class="fa fa-refresh"></i>
                </button>
                <button class="lightbox-btn" id="lightboxZoomIn" title="Zoom in">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="lightbox-thumbnails" id="lightboxThumbnails"></div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.shipping-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    var card = toggle.closest('.shipping-card');
                    var policy = card ? card.querySelector('.shipping-policy') : null;
                    if (!policy) return;

                    var isOpen = policy.style.display === 'block';
                    policy.style.display = isOpen ? 'none' : 'block';
                    toggle.classList.toggle('open', !isOpen);
                });
            });

            document.querySelectorAll('.imageSwitch img').forEach(function(thumb) {
                thumb.addEventListener('click', function(event) {
                    var wrapper = event.currentTarget.parentElement;
                    var imageName = wrapper ? wrapper.getAttribute('data-img_url') : '';

                    document.querySelectorAll('.product-image').forEach(function(image) {
                        var active = image.getAttribute('data-img_url') === imageName;
                        image.style.display = active ? 'block' : 'none';
                        image.classList.toggle('tee-ui-front', active);
                    });
                });
            });

            document.querySelectorAll('.product-details .size-selector').forEach(function(sizeButton) {
                sizeButton.addEventListener('click', function() {
                    document.querySelectorAll('.product-details .size-selector').forEach(function(item) {
                        item.classList.remove('size-selector-selected');
                    });

                    sizeButton.classList.add('size-selector-selected');
                    document.querySelectorAll('.product-details .btnAddToCart').forEach(function(button) {
                        button.setAttribute('data-size', sizeButton.getAttribute('data-size') || '');
                    });

                    var warning = document.querySelector('.product-details .no-size-selected');
                    if (warning) warning.style.display = 'none';
                });
            });

            document.querySelectorAll('.product-details .product-color-selector').forEach(function(colorButton) {
                colorButton.addEventListener('click', function() {
                    document.querySelectorAll('.product-details .product-color-selector').forEach(function(item) {
                        item.classList.remove('color-selector-selected');
                        item.setAttribute('aria-pressed', 'false');
                    });

                    colorButton.classList.add('color-selector-selected');
                    colorButton.setAttribute('aria-pressed', 'true');
                    document.querySelectorAll('.product-details .btnAddToCart').forEach(function(button) {
                        button.setAttribute('data-color', colorButton.getAttribute('data-color') || '');
                    });
                });
            });

            function showCartNotification(message, type) {
                document.querySelectorAll('.product-cart-toast').forEach(function(item) { item.remove(); });

                var toast = document.createElement('div');
                toast.className = 'product-cart-toast ' + (type === 'error' ? 'error' : 'success');
                toast.setAttribute('role', 'status');
                toast.innerHTML = '<i class="fa ' + (type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle') + '"></i>' +
                    '<span></span>';
                toast.querySelector('span').textContent = message;
                document.body.appendChild(toast);
                window.setTimeout(function() { toast.remove(); }, 3200);
            }

            function updateCartCount(count) {
                document.querySelectorAll('#cartBadge, .shopping-cart-badge').forEach(function(badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });

                if (window.cartDrawer && typeof window.cartDrawer.updateBadge === 'function') {
                    window.cartDrawer.updateBadge(count);
                }
            }

            var addToCartButton = document.querySelector('.product-details .main-btnAddToCart');
            if (addToCartButton) {
                addToCartButton.addEventListener('click', function() {
                    var selectedColor = document.querySelector('.product-details .product-color-selector.color-selector-selected');
                    var selectedSize = document.querySelector('.product-details .size-selector-selected');
                    var requiresColor = {{ $product->productColors->isNotEmpty() ? 'true' : 'false' }};
                    var requiresSize = {{ $product->productSizes->isNotEmpty() ? 'true' : 'false' }};

                    if (requiresColor && !selectedColor) {
                        showCartNotification('Please select a color.', 'error');
                        return;
                    }

                    if (requiresSize && !selectedSize) {
                        var warning = document.querySelector('.product-details .no-size-selected');
                        if (warning) warning.style.display = 'inline';
                        showCartNotification('Please select a size.', 'error');
                        return;
                    }

                    if (typeof window.axios === 'undefined') {
                        showCartNotification('Unable to connect. Please refresh the page.', 'error');
                        return;
                    }

                    var quantityInput = document.querySelector('.product-details .quantity-selector');
                    var quantity = Math.max(1, parseInt(quantityInput ? quantityInput.value : 1, 10) || 1);
                    var originalContent = addToCartButton.innerHTML;
                    addToCartButton.disabled = true;
                    addToCartButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Adding...';

                    window.axios.post('{{ route('ajax-add-to-cart') }}', {
                        product_id: {{ $product->id }},
                        quantity: quantity,
                        color_id: selectedColor ? selectedColor.getAttribute('data-color-id') : null,
                        size_id: selectedSize ? selectedSize.getAttribute('data-size-id') : null
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(function(response) {
                        if (response.data && response.data.success) {
                            updateCartCount(parseInt(response.data.cart_count, 10) || 0);
                            showCartNotification(response.data.message || 'Product added to cart.', 'success');
                        }
                    }).catch(function(error) {
                        var data = error.response && error.response.data ? error.response.data : {};
                        var message = data.message || 'Could not add this product to cart.';

                        if (data.errors) {
                            var firstError = Object.keys(data.errors)[0];
                            if (firstError && data.errors[firstError][0]) message = data.errors[firstError][0];
                        }

                        showCartNotification(message, 'error');
                    }).then(function() {
                        addToCartButton.disabled = false;
                        addToCartButton.innerHTML = originalContent;
                    });
                });
            }

            var relatedModal = document.getElementById('relatedCartModal');
            var relatedModalBody = document.getElementById('relatedCartBody');
            var relatedConfirm = document.getElementById('relatedCartConfirm');
            var activeRelatedButton = null;
            var activeRelatedProduct = null;

            function parseRelatedOptions(value) {
                try {
                    var parsed = JSON.parse(value || '[]');
                    return Array.isArray(parsed) ? parsed : [];
                } catch (error) {
                    return [];
                }
            }

            function escapeRelatedHtml(value) {
                var element = document.createElement('div');
                element.textContent = value == null ? '' : String(value);
                return element.innerHTML;
            }

            function closeRelatedModal() {
                if (!relatedModal) return;
                relatedModal.classList.remove('is-open');
                relatedModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                activeRelatedButton = null;
                activeRelatedProduct = null;
            }

            function setRelatedLoading(button, loading) {
                if (!button) return;
                if (loading) {
                    button.dataset.originalHtml = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Adding...';
                } else {
                    button.disabled = false;
                    button.innerHTML = button.dataset.originalHtml || '<i class="fa fa-plus"></i>&nbsp; Add to Cart';
                }
            }

            function addRelatedToCart(button, product, colorId, sizeId, quantity) {
                if (typeof window.axios === 'undefined') {
                    showCartNotification('Unable to connect. Please refresh the page.', 'error');
                    return;
                }

                setRelatedLoading(button, true);
                if (relatedConfirm) relatedConfirm.disabled = true;

                window.axios.post('{{ route('ajax-add-to-cart') }}', {
                    product_id: product.id,
                    quantity: quantity,
                    color_id: colorId || null,
                    size_id: sizeId || null
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(function(response) {
                    if (response.data && response.data.success) {
                        updateCartCount(parseInt(response.data.cart_count, 10) || 0);
                        closeRelatedModal();
                        showCartNotification(response.data.message || 'Product added to cart.', 'success');
                    }
                }).catch(function(error) {
                    var data = error.response && error.response.data ? error.response.data : {};
                    var message = data.message || 'Could not add this product to cart.';
                    if (data.errors) {
                        var firstError = Object.keys(data.errors)[0];
                        if (firstError && data.errors[firstError][0]) message = data.errors[firstError][0];
                    }
                    showCartNotification(message, 'error');
                }).then(function() {
                    setRelatedLoading(button, false);
                    if (relatedConfirm) relatedConfirm.disabled = false;
                });
            }

            function openRelatedModal(button, product) {
                activeRelatedButton = button;
                activeRelatedProduct = product;

                var optionHtml = '<div class="related-cart-product">' +
                    '<img src="' + escapeRelatedHtml(product.image) + '" alt="' + escapeRelatedHtml(product.title) + '">' +
                    '<strong>' + escapeRelatedHtml(product.title) + '</strong></div><div class="related-cart-options">';

                if (product.colors.length) {
                    optionHtml += '<div class="related-cart-option-group"><label>Select Color</label><div class="related-cart-option-list" data-related-options="color">';
                    product.colors.forEach(function(color) {
                        var dot = /^#[0-9a-fA-F]{6}$/.test(color.hex_code || '')
                            ? '<span class="related-cart-color-dot" style="background:' + color.hex_code + '"></span>'
                            : '<span class="related-cart-color-dot" style="background:#f3f4f6"></span>';
                        optionHtml += '<button type="button" class="related-cart-option" data-option-id="' + Number(color.id) + '">' + dot + escapeRelatedHtml(color.name) + '</button>';
                    });
                    optionHtml += '</div></div>';
                }

                if (product.sizes.length) {
                    optionHtml += '<div class="related-cart-option-group"><label>Select Size</label><div class="related-cart-option-list" data-related-options="size">';
                    product.sizes.forEach(function(size) {
                        optionHtml += '<button type="button" class="related-cart-option" data-option-id="' + Number(size.id) + '">' + escapeRelatedHtml(size.name) + '</button>';
                    });
                    optionHtml += '</div></div>';
                }

                optionHtml += '<div class="related-cart-option-group"><label>Quantity</label>' +
                    '<input class="related-cart-quantity" id="relatedCartQuantity" type="number" min="1" max="' + product.stock + '" value="1"></div></div>';

                relatedModalBody.innerHTML = optionHtml;
                relatedModal.classList.add('is-open');
                relatedModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            document.querySelectorAll('.related-add-cart').forEach(function(button) {
                button.addEventListener('click', function() {
                    var product = {
                        id: parseInt(button.getAttribute('data-product-id'), 10),
                        title: button.getAttribute('data-title') || '',
                        image: button.getAttribute('data-image') || '',
                        stock: Math.max(1, parseInt(button.getAttribute('data-stock'), 10) || 1),
                        colors: parseRelatedOptions(button.getAttribute('data-colors')),
                        sizes: parseRelatedOptions(button.getAttribute('data-sizes'))
                    };

                    if (!product.colors.length && !product.sizes.length) {
                        addRelatedToCart(button, product, null, null, 1);
                        return;
                    }

                    openRelatedModal(button, product);
                });
            });

            if (relatedModalBody) {
                relatedModalBody.addEventListener('click', function(event) {
                    var option = event.target.closest('.related-cart-option');
                    if (!option) return;
                    var list = option.closest('.related-cart-option-list');
                    list.querySelectorAll('.related-cart-option').forEach(function(item) {
                        item.classList.remove('is-selected');
                    });
                    option.classList.add('is-selected');
                });
            }

            if (relatedConfirm) {
                relatedConfirm.addEventListener('click', function() {
                    if (!activeRelatedProduct || !activeRelatedButton) return;

                    var colorOption = relatedModalBody.querySelector('[data-related-options="color"] .is-selected');
                    var sizeOption = relatedModalBody.querySelector('[data-related-options="size"] .is-selected');

                    if (activeRelatedProduct.colors.length && !colorOption) {
                        showCartNotification('Please select a color.', 'error');
                        return;
                    }
                    if (activeRelatedProduct.sizes.length && !sizeOption) {
                        showCartNotification('Please select a size.', 'error');
                        return;
                    }

                    var quantityInput = document.getElementById('relatedCartQuantity');
                    var quantity = Math.max(1, parseInt(quantityInput ? quantityInput.value : 1, 10) || 1);
                    addRelatedToCart(
                        activeRelatedButton,
                        activeRelatedProduct,
                        colorOption ? colorOption.getAttribute('data-option-id') : null,
                        sizeOption ? sizeOption.getAttribute('data-option-id') : null,
                        quantity
                    );
                });
            }

            document.querySelectorAll('[data-related-cart-close]').forEach(function(button) {
                button.addEventListener('click', closeRelatedModal);
            });
            if (relatedModal) {
                relatedModal.addEventListener('click', function(event) {
                    if (event.target === relatedModal) closeRelatedModal();
                });
            }

            function syncQuantity() {
                var input = document.querySelector('.product-details .quantity-selector');
                if (!input) return;

                document.querySelectorAll('.product-details .btnAddToCart').forEach(function(button) {
                    button.setAttribute('data-quantity', input.value || 1);
                });
            }

            document.querySelectorAll('.product-details .quantity-selector').forEach(function(input) {
                input.addEventListener('change', syncQuantity);
                input.addEventListener('keyup', syncQuantity);
            });

            document.querySelectorAll('.product-details .quantity-selector-step').forEach(function(button) {
                button.addEventListener('click', function() {
                    window.setTimeout(syncQuantity, 0);
                });
            });

            var lightbox = document.getElementById('imageLightbox');
            var lightboxImage = document.getElementById('lightboxImage');
            var closeButton = document.getElementById('lightboxClose');
            var zoomInButton = document.getElementById('lightboxZoomIn');
            var zoomOutButton = document.getElementById('lightboxZoomOut');
            var resetButton = document.getElementById('lightboxReset');
            var zoomLevel = document.getElementById('lightboxZoomLevel');
            var thumbnails = document.getElementById('lightboxThumbnails');
            var currentZoom = 1;

            function setZoom(value) {
                currentZoom = Math.max(1, Math.min(3, value));
                if (lightboxImage) {
                    lightboxImage.style.transform = 'scale(' + currentZoom + ')';
                }
                if (zoomLevel) {
                    zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
                    zoomLevel.classList.add('visible');
                    window.clearTimeout(setZoom.timer);
                    setZoom.timer = window.setTimeout(function() {
                        zoomLevel.classList.remove('visible');
                    }, 1200);
                }
            }

            function openLightbox(src) {
                if (!lightbox || !lightboxImage || !src) return;

                lightboxImage.src = src.split('?')[0];
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
                setZoom(1);

                if (thumbnails) {
                    thumbnails.innerHTML = '';
                    document.querySelectorAll('.product-image').forEach(function(image) {
                        var thumb = document.createElement('img');
                        thumb.src = image.src.split('?')[0];
                        thumb.className = 'lightbox-thumb';
                        thumb.addEventListener('click', function() {
                            lightboxImage.src = thumb.src;
                            setZoom(1);
                        });
                        thumbnails.appendChild(thumb);
                    });
                }
            }

            function closeLightbox() {
                if (!lightbox) return;
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
                setZoom(1);
            }

            document.querySelectorAll('.product-image-container .product-image').forEach(function(image) {
                image.addEventListener('click', function() {
                    openLightbox(image.src);
                });
            });

            if (closeButton) closeButton.addEventListener('click', closeLightbox);
            if (zoomInButton) zoomInButton.addEventListener('click', function() {
                setZoom(currentZoom + 0.25);
            });
            if (zoomOutButton) zoomOutButton.addEventListener('click', function() {
                setZoom(currentZoom - 0.25);
            });
            if (resetButton) resetButton.addEventListener('click', function() {
                setZoom(1);
            });
            if (lightbox) {
                lightbox.addEventListener('click', function(event) {
                    if (event.target === lightbox) closeLightbox();
                });
            }
        });
    </script>
@endsection
