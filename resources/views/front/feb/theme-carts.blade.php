@extends('front.feb.layouts.master')

@section('title')
    Cart
@endsection

@section('content')
    <style>
        /* ========================================
       CART PAGE STYLES - Modern Redesign
       ======================================== */

        /* Layout Grid - Responsive widths matching product page */
        .cart-page-container {
            margin: 0 auto;
            padding: 30px 15px;
            width: 100%;
        }

        @media (min-width: 961px) {
            .cart-page-container {
                width: 800px;
            }
        }

        @media (min-width: 1025px) {
            .cart-page-container {
                width: 970px;
            }
        }

        @media (min-width: 1281px) {
            .cart-page-container {
                width: 1100px;
            }
        }

        @media (min-width: 1401px) {
            .cart-page-container {
                width: 1200px;
            }
        }

        @media (min-width: 1601px) {
            .cart-page-container {
                width: 1400px;
            }
        }

        .cart-page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .cart-page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .cart-page-header .cart-count {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            align-items: start;
        }

        /* Cart Items Column */
        .cart-items-column {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .cart-items-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .cart-items-header h2 {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .cart-items-list {
            padding: 0;
        }

        /* Individual Cart Item Card */
        .cart-item {
            display: flex;
            gap: 16px;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
            transition: background 0.2s ease;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .cart-item-image {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            cursor: pointer;
            border-radius: 0;
            overflow: hidden;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cart-item-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin: 0;
            line-height: 1.4;
        }

        .cart-item-title a {
            color: #111827;
            text-decoration: none;
        }

        .cart-item-title a:hover {
            color: #333;
            text-decoration: underline;
        }

        .cart-item-designer {
            font-size: 13px;
            color: #6b7280;
        }

        .cart-item-designer a {
            color: #333;
            text-decoration: none;
        }

        .cart-item-designer a:hover {
            text-decoration: underline;
        }

        .cart-item-controls {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .cart-control-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .cart-control-group label {
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cart-select {
            padding: 8px 32px 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0;
            font-size: 14px;
            color: #1f2937;
            background: #ffffff;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            min-width: 80px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            height: auto !important;
            line-height: normal !important;
        }

        /* Override gcart styles for cart page selects */
        .cart-page-container .cartinput-size-select,
        .cart-page-container .cartinput-quantity {
            height: auto !important;
            line-height: normal !important;
        }

        .cart-select:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 0 2px rgba(51, 51, 51, 0.1);
        }

        .cart-select:hover {
            border-color: #9ca3af;
        }

        .cart-variant-value {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 7px 11px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #1f2937;
            font-size: 14px;
            cursor: default;
        }

        .cart-item-pricing {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            min-width: 100px;
        }

        .cart-item-unit-price {
            font-size: 13px;
            color: #6b7280;
        }

        .cart-item-unit-price .regular-price {
            text-decoration: line-through;
            color: #9ca3af;
            margin-right: 4px;
        }

        .cart-item-subtotal {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        .cart-item-subtotal .regular-subtotal {
            text-decoration: line-through;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 400;
            margin-right: 4px;
        }

        .cart-item-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .cart-btn {
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .cart-btn-add {
            background: #333;
            color: white;
        }

        .cart-btn-add:hover {
            background: #000;
        }

        .cart-btn-remove {
            background: #fee2e2;
            color: #dc2626;
        }

        .cart-btn-remove:hover {
            background: #dc2626;
            color: white;
        }

        .cart-btn-remove i {
            font-size: 14px;
        }

        .products-cart-button-loader {
            font-size: 1em;
        }

        .exceeds-limit-warning {
            font-size: 11px;
            color: #dc2626;
            margin-top: 2px;
        }

        /* Spinner for loading */
        .cart-item .spinner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .cart-item .spinner>div {
            background-color: #333;
            height: 30px;
            width: 4px;
            display: inline-block;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
            margin: 0 2px;
        }

        .cart-item .spinner .rect2 {
            animation-delay: -1.1s;
        }

        .cart-item .spinner .rect3 {
            animation-delay: -1.0s;
        }

        .cart-item .spinner .rect4 {
            animation-delay: -0.9s;
        }

        .cart-item .spinner .rect5 {
            animation-delay: -0.8s;
        }

        @keyframes sk-stretchdelay {

            0%,
            40%,
            100% {
                transform: scaleY(0.4);
            }

            20% {
                transform: scaleY(1.0);
            }
        }

        /* Order Summary Sidebar */
        .cart-summary-column {
            position: sticky;
            top: 80px;
        }

        .cart-summary-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .cart-summary-header {
            padding: 16px 20px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-summary-header h2 {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .cart-summary-content {
            padding: 20px;
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 14px;
            color: #6b7280;
        }

        .cart-summary-row .label {
            color: #374151;
        }

        .cart-summary-row .value {
            font-weight: 500;
            color: #1f2937;
        }

        .cart-summary-row.total {
            border-top: 2px solid #e5e7eb;
            margin-top: 10px;
            padding-top: 16px;
            font-size: 18px;
        }

        .cart-summary-row.total .label {
            font-weight: 600;
            color: #111827;
        }

        .cart-summary-row.total .value {
            font-weight: 700;
            color: #111827;
        }

        /* Override global checkout-total green color */
        .cart-summary-content .checkout-total {
            color: #111827;
            font-size: inherit;
            font-weight: inherit;
            font-family: inherit;
        }

        .cart-summary-row.total .prev-total {
            text-decoration: line-through;
            color: #9ca3af;
            font-weight: 400;
            font-size: 14px;
            margin-right: 8px;
        }

        .cart-summary-actions {
            padding: 0 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .cart-cta-primary {
            width: 100%;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 600;
            background: #333;
            color: white;
            border: none;
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .cart-cta-primary:hover {
            background: #000;
            color: white;
            text-decoration: none;
        }

        .cart-cta-international {
            width: 100%;
            padding: 12px 24px;
            font-size: 13px;
            font-weight: 500;
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .cart-cta-international:hover {
            background: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
            text-decoration: none;
        }

        .cart-cta-international i {
            margin-right: 6px;
        }

        .cart-cta-secondary {
            width: 100%;
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 500;
            background: #ffffff;
            color: #333;
            border: 1px solid #d1d5db;
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .cart-cta-secondary:hover {
            background: #f9fafb;
            border-color: #333;
            color: #333;
            text-decoration: none;
        }

        .cart-bulk-notice {
            margin: 20px;
            padding: 14px 16px;
            background: #f9fafb;
            border-left: 3px solid #333;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }

        .cart-bulk-notice strong {
            color: #111827;
        }

        /* Trust Badges */
        .cart-trust-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .trust-badge i {
            color: #059669;
        }

        /* Alert Container */
        .alert-container {
            position: fixed;
            left: calc(50% - 43%);
            top: 18%;
            width: 85%;
            z-index: 1000;
        }

        /* You May Also Like Section */
        .cart-related-section {
            margin-top: 50px;
            padding-top: 40px;
            border-top: 1px solid #e5e7eb;
        }

        .cart-related-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .cart-related-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 8px 0;
        }

        .cart-related-header p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .cart-related-products {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (min-width: 768px) {
            .cart-related-products {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .cart-related-products {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Related Product Card */
        .related-product-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .related-product-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #d1d5db;
        }

        .related-product-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .related-product-link:hover {
            text-decoration: none;
            color: inherit;
        }

        .related-product-image {
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .related-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-product-info {
            padding: 12px 14px;
            text-align: center;
        }

        .related-product-title {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
            margin: 0 0 8px 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 36px;
        }

        .related-product-price {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .related-product-action {
            padding: 0 14px 14px;
            text-align: center;
        }

        .view-product-btn {
            display: block;
            padding: 10px 16px;
            background: #333;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            transition: background 0.2s ease;
        }

        .related-product-card:hover .view-product-btn {
            background: #000;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .cart-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cart-summary-column {
                position: relative;
                top: 0;
                order: -1;
            }

            .cart-item {
                flex-wrap: wrap;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-pricing {
                width: 100%;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                margin-top: 8px;
                padding-top: 12px;
                border-top: 1px solid #f3f4f6;
            }

            .cart-item-controls {
                width: 100%;
            }

            .cart-control-group {
                flex: 1;
            }

            .cart-select {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .cart-page-container {
                padding: 15px 10px;
            }

            .cart-item {
                padding: 16px;
                position: relative;
            }

            .cart-item-image {
                width: 70px;
                height: 70px;
            }

            .cart-related-products {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .related-product-title {
                font-size: 12px;
                min-height: 32px;
            }

            .related-product-price {
                font-size: 14px;
            }

            .view-product-btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .cart-item-title {
                font-size: 14px;
                padding-right: 30px;
            }

            .cart-item-actions {
                flex-direction: column;
            }

            .cart-btn {
                width: 100%;
                justify-content: center;
            }

            /* Mobile delete button - small icon in top-right corner */
            .cart-btn-remove {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 28px;
                height: 28px;
                min-width: 28px;
                padding: 0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cart-btn-remove i {
                font-size: 12px;
                margin: 0;
            }

            .cart-btn-remove .cart-remove-text {
                display: none;
            }

            .cart-trust-badges {
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        /* =====================================================
       Price Modal Styles (for cart page Add More Size modal)
       ===================================================== */

        /* Modal Dialog */
        .pricemodal .modal-dialog {
            max-width: 380px;
            margin: 30px auto;
            transition: max-width 0.3s ease;
        }

        /* Expanded modal when size chart is visible */
        .pricemodal .modal-dialog.expanded {
            max-width: 580px;
        }

        .pricemodal .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
        }

        /* Modal Header */
        .pricemodal .modal-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 0;
        }

        /* Close button - absolutely positioned */
        .pricemodal .modal-header .close {
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 20;
            color: white;
            opacity: 0.9;
            text-shadow: none;
            font-size: 20px;
            font-weight: 400;
            padding: 0;
            margin: 0;
            width: 28px;
            height: 28px;
            background: rgba(0, 0, 0, 0.25);
            border-radius: 50%;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .pricemodal .modal-header .close span {
            display: block;
            margin-top: -2px;
        }

        .pricemodal .modal-header .close:hover {
            opacity: 1;
            background: rgba(0, 0, 0, 0.4);
        }

        .pricemodal .modal-title,
        .pricemodal .pricemodal-header {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            width: 100%;
        }

        .pricemodal .modal-title p,
        .pricemodal .pricemodal-header p {
            margin: 0;
            text-align: center;
        }

        /* Modal Content Layout */
        .pricemodal .pricemodal-content {
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        /* Top Row: Product + Size Chart side by side */
        .pricemodal .pricemodal-top-row {
            display: flex;
            flex-direction: row;
            border-bottom: 1px solid #e5e7eb;
            justify-content: center;
        }

        /* Product Card View (Left Side) - Full width by default */
        .pricemodal .card-view {
            width: 100% !important;
            height: auto !important;
            min-height: unset !important;
            max-height: none !important;
            padding: 16px !important;
            background: #f9fafb;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            border-right: none;
            transition: width 0.3s ease;
            flex-shrink: 0;
            float: none !important;
        }

        /* Card view shrinks when size chart is shown */
        .pricemodal .pricemodal-top-row.show-size-chart .card-view {
            width: 45% !important;
            border-right: 1px solid #e5e7eb;
        }

        .pricemodal .card-photo-container {
            width: 100% !important;
            max-width: 240px !important;
            height: auto !important;
            min-height: unset !important;
            overflow: hidden;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pricemodal .pricemodal-top-row.show-size-chart .card-photo-container {
            max-width: 180px !important;
        }

        .pricemodal img.card-photo {
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
            display: block !important;
            margin: 0 !important;
            object-fit: cover;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .pricemodal .card-info-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .pricemodal .card-title {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            line-height: 1.3;
            max-height: 36px;
            overflow: hidden;
        }

        /* Size Guide Toggle Link */
        .pricemodal .size-guide-toggle {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #4b5563;
            text-decoration: underline;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: color 0.2s ease;
        }

        .pricemodal .size-guide-toggle:hover {
            color: #111827;
        }

        .pricemodal .size-guide-toggle i {
            font-size: 14px;
        }

        .pricemodal .size-guide-toggle.active {
            color: #111827;
            font-weight: 600;
        }

        /* Size Chart (Right Side) - Hidden by default */
        .pricemodal .modal-size-chart {
            width: 0;
            padding: 0;
            display: none;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Show size chart when toggle is active */
        .pricemodal .pricemodal-top-row.show-size-chart .modal-size-chart {
            width: 55%;
            padding: 16px;
            display: flex;
        }

        /* Hide size chart when empty (no data available) */
        .pricemodal .modal-size-chart.empty {
            display: none !important;
            width: 0 !important;
            padding: 0 !important;
        }

        /* Hide the size guide toggle when no size chart data */
        .pricemodal .size-guide-toggle.no-data {
            display: none;
        }

        .pricemodal .modal-size-chart-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Size Chart Header with title and unit toggle */
        .pricemodal .size-chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Unit Toggle Buttons (CM/INCH) */
        .pricemodal .size-chart-unit-toggle {
            display: flex;
            gap: 2px;
            background: #f3f4f6;
            padding: 2px;
            border-radius: 4px;
        }

        .pricemodal .unit-btn {
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 600;
            background: transparent;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            color: #6b7280;
            transition: all 0.15s ease;
        }

        .pricemodal .unit-btn:hover {
            color: #374151;
        }

        .pricemodal .unit-btn.active {
            background: #111827;
            color: #fff;
        }

        /* Size Chart Tabs */
        .pricemodal .size-chart-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .pricemodal .size-chart-tab {
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 500;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            color: #6b7280;
            transition: all 0.15s ease;
        }

        .pricemodal .size-chart-tab:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .pricemodal .size-chart-tab.active {
            background: #111827;
            border-color: #111827;
            color: #fff;
        }

        .pricemodal .modal-size-chart-title i {
            color: #6b7280;
            font-size: 12px;
        }

        .pricemodal .modal-size-chart-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        .pricemodal .modal-size-chart-table th,
        .pricemodal .modal-size-chart-table td {
            padding: 6px 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .pricemodal .modal-size-chart-table th {
            background: #f3f4f6;
            font-weight: 600;
            color: #374151;
        }

        .pricemodal .modal-size-chart-table td {
            color: #4b5563;
        }

        .pricemodal .modal-size-chart-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        /* Size Selector (Full Width Below) */
        .pricemodal .modal-body.pricemodal-body {
            width: 100%;
            padding: 16px 20px;
            display: block;
            border: none;
            float: none;
        }

        .pricemodal .pricemodal-body .list-inline {
            margin-bottom: 6px;
            text-align: center;
        }

        .pricemodal .pricemodal-body .list-inline b {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        /* Size Selector Buttons */
        .pricemodal ul.size-selectors-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0;
            margin: 0;
            background: transparent !important;
            border: none !important;
            justify-content: center;
            list-style: none;
        }

        .pricemodal ul.size-selectors-container li {
            margin: 0 !important;
            padding: 0 !important;
        }

        .pricemodal ul.size-selectors-container.list-inline {
            position: relative;
            margin-top: 6px;
            text-align: center;
        }

        .pricemodal ul.size-selectors-container.list-inline.error:after {
            content: "Please Select Size";
            position: absolute;
            top: -24px;
            left: 0;
            font-size: 11px;
            color: #dc2626;
            font-weight: 500;
        }

        .pricemodal .size-selector,
        .pricemodal .size-selector-selected {
            min-width: 46px;
            height: 36px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pricemodal .size-selector {
            background: #fff;
            border: 1.5px solid #d1d5db;
            color: #374151;
        }

        .pricemodal .size-selector:hover {
            border-color: #111827;
            background: #f9fafb;
        }

        .pricemodal .size-selector-selected {
            background: #111827;
            border: 1.5px solid #111827;
            color: #fff;
        }

        .pricemodal .size-selector-selected:hover {
            background: #000;
        }

        /* Color Selector */
        .pricemodal ul.list-inline.color-seletcor-container {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .pricemodal ul.list-inline.color-seletcor-container.error:after {
            content: "Please Select Color";
            position: absolute;
            top: -8px;
            left: 0;
            font-size: 12px;
            color: #dc2626;
            font-weight: 500;
        }

        /* Price Box */
        .pricemodal .priceBox {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .pricemodal .modal-price-text {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .pricemodal .modal-discount-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .pricemodal .modal-discount-text del {
            color: #9ca3af;
        }

        /* Modal Footer */
        .pricemodal .modal-footer.pricemodal-footer {
            display: block !important;
            padding: 14px 20px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            width: 100%;
        }

        .pricemodal .modal-footer .btn {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.15s ease;
            margin: 0 !important;
            float: none !important;
        }

        /* Hide cancel button */
        .pricemodal .modal-footer .btn-danger {
            display: none;
        }

        .pricemodal .modal-footer .btn-success {
            background: #111827 !important;
            border: 1.5px solid #111827 !important;
            color: #fff !important;
        }

        .pricemodal .modal-footer .btn-success:hover,
        .pricemodal .modal-footer .btn-success:focus,
        .pricemodal .modal-footer .btn-success:active {
            background: #000 !important;
            border-color: #000 !important;
            color: #fff !important;
        }

        /* Premium Sizes */
        .pricemodal .Premium_pTag {
            display: none;
        }

        .pricemodal div.size-selectors-container {
            border: none !important;
            margin-top: 12px;
        }

        .pricemodal div.size-selectors-container:hover {
            background: transparent !important;
        }

        /* Add to Cart Button Spinner Icon */
        .pricemodal .addToCartBtn>i,
        .pricemodal .addToCartBtn>i.fa-3x,
        .pricemodal .modal-footer .btn-success>i,
        .pricemodal .modal-footer .btn-success>i.fa-3x {
            display: none;
            font-size: 14px !important;
            margin-left: 8px;
            width: auto;
            height: auto;
        }

        .pricemodal .addToCartBtn[disabled]>i,
        .pricemodal .addToCartBtn.loading>i,
        .pricemodal .modal-footer .btn-success[disabled]>i,
        .pricemodal .modal-footer .btn-success.loading>i {
            display: inline-block;
        }

        /* Card Text */
        .pricemodal .card-text.truncate {
            padding: 0;
            font-size: 12px;
            color: #6b7280;
            white-space: normal;
            line-height: 1.4;
            margin-top: 4px;
            height: auto;
            max-height: 32px;
            overflow: hidden;
        }

        /* Number Input (Quantity Selector) */
        .pricemodal .number-input {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-top: 8px;
            border: none;
        }

        .pricemodal .number-input .quantity-selector-step {
            width: 36px;
            height: 36px;
            border: 1px solid #d1d5db;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #374151;
            transition: all 0.15s ease;
        }

        .pricemodal .number-input .quantity-selector-step:first-child {
            border-radius: 6px 0 0 6px;
            border-right: none;
        }

        .pricemodal .number-input .quantity-selector-step:last-child {
            border-radius: 0 6px 6px 0;
            border-left: none;
        }

        .pricemodal .number-input .quantity-selector-step:hover {
            background: #f3f4f6;
        }

        .pricemodal .number-input input.quantity-selector {
            width: 50px;
            height: 36px;
            text-align: center;
            border: 1px solid #d1d5db;
            font-size: 14px;
            font-weight: 500;
            -moz-appearance: textfield;
        }

        .pricemodal .number-input input.quantity-selector::-webkit-outer-spin-button,
        .pricemodal .number-input input.quantity-selector::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .pricemodal .modal-dialog {
                margin: 15px;
                width: calc(100% - 30px);
                max-width: calc(100% - 30px) !important;
            }

            .pricemodal .modal-dialog.expanded {
                max-width: calc(100% - 30px) !important;
            }

            .pricemodal .modal-content {
                max-height: 90vh;
                overflow-y: auto;
            }

            /* Stack product and size chart vertically on mobile */
            .pricemodal .pricemodal-top-row {
                flex-direction: column;
            }

            /* Card view - full width, auto height on mobile - same as desktop */
            .pricemodal .card-view {
                width: 100% !important;
                height: auto !important;
                min-height: unset !important;
                max-height: none !important;
                padding: 16px !important;
                flex-direction: column !important;
                border-right: none;
                border-bottom: none;
            }

            /* Card view stays full width, no side-by-side with size chart */
            .pricemodal .pricemodal-top-row.show-size-chart .card-view {
                width: 100% !important;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            /* Card photo container - full width, auto height on mobile */
            .pricemodal .card-photo-container {
                width: 100% !important;
                max-width: 200px !important;
                height: auto !important;
                min-height: unset !important;
            }

            .pricemodal .pricemodal-top-row.show-size-chart .card-photo-container {
                max-width: 200px !important;
            }

            /* Card photo image - visible and properly sized */
            .pricemodal img.card-photo {
                width: 100% !important;
                height: auto !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .pricemodal .card-info-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .pricemodal .card-title {
                margin-top: 10px;
                text-align: center;
                font-size: 13px;
                max-height: none;
            }

            /* Size guide toggle on mobile */
            .pricemodal .size-guide-toggle {
                margin-top: 8px;
                padding: 6px 12px;
                font-size: 12px;
            }

            /* Mobile: size chart stacks below */
            .pricemodal .modal-size-chart {
                width: 100% !important;
                padding: 0;
                display: none;
            }

            .pricemodal .pricemodal-top-row.show-size-chart .modal-size-chart {
                width: 100% !important;
                padding: 14px 16px;
                display: flex;
                border-bottom: 1px solid #e5e7eb;
            }

            .pricemodal .modal-size-chart-table {
                font-size: 11px;
            }

            .pricemodal .modal-size-chart-table th,
            .pricemodal .modal-size-chart-table td {
                padding: 5px 6px;
            }

            .pricemodal .modal-body.pricemodal-body {
                padding: 14px 16px;
            }

            .pricemodal .size-selector,
            .pricemodal .size-selector-selected {
                min-width: 44px;
                height: 40px;
                padding: 8px 10px;
                font-size: 13px;
            }

            .pricemodal .modal-footer.pricemodal-footer {
                padding: 14px 16px;
                position: sticky;
                bottom: 0;
                background: #fff;
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            .pricemodal .modal-dialog {
                max-width: 380px;
            }

            .pricemodal .modal-dialog.expanded {
                max-width: 520px;
            }
        }

        /* =====================================================
       Other Modal Styles (cart-page specific)
       ===================================================== */
        .checkout-modal .modal-header,
        .szc .modal-header {
            background: #333;
            color: white;
            border-radius: 0;
        }

        .checkout-modal .modal-content,
        .szc .modal-content,
        .viewmodal .modal-content,
        .addToOrdermodal .modal-content {
            border-radius: 0;
            border: none;
        }

        .checkout-modal .btn-success {
            background: #333;
            border-color: #333;
        }

        .checkout-modal .btn-success:hover {
            background: #000;
            border-color: #000;
        }

        /* Admin Add to Order Button */
        .cart-admin-actions {
            padding: 0 20px 20px;
        }

        .cart-admin-btn {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0;
            cursor: pointer;
        }

        .cart-admin-btn:hover {
            background: #2563eb;
        }
    </style>

    <div class="wrapper-div">
        <div class="container">
        </div>
        <div class="container-fluid">
            <div class="cart-page-container">
                <div class="cart-page-header">
                    <h1>Your Shopping Cart</h1>
                    <p class="cart-count"><span id="cart-page-count">{{ $cartQuantity }}</span> {{ $cartQuantity === 1 ? 'item' : 'items' }}</p>
                </div>

                <div class="cart-grid">
                    <!-- Cart Items Column -->
                    <div class="cart-items-column">
                        <div class="cart-items-header">
                            <h2>Cart Items</h2>
                        </div>
                        <div class="cart-items-list">

                            @forelse($carts as $cart)
                                @php
                                    $cartProduct = $cart->product;
                                    $productUrl = $cartProduct
                                        ? route('single-product', $cartProduct->slug ?: $cartProduct->id)
                                        : '#';
                                    $stockLimit = $cartProduct ? max(1, (int) $cartProduct->stock_quantity) : (int) $cart->quantity;
                                    $quantityLimit = max((int) $cart->quantity, min(20, $stockLimit));
                                    $regularPrice = $cartProduct ? (float) $cartProduct->product_value : (float) $cart->unit_price;
                                @endphp
                                <div class="cart-item new-designed-element" data-cart-id="{{ $cart->id }}"
                                    data-price="{{ $cart->unit_price }}" data-prevqty="{{ $cart->quantity }}">
                                    <div class="cart-item-image">
                                        <a href="{{ $productUrl }}">
                                            <img src="{{ \App\Support\MediaStorage::url($cart->product_image, 'products') }}"
                                                alt="{{ $cart->product_name }}">
                                        </a>
                                    </div>

                                    <div class="cart-item-details">
                                        <h3 class="cart-item-title">
                                            <a href="{{ $productUrl }}">{{ $cart->product_name }}</a>
                                        </h3>

                                        <div class="cart-item-controls">
                                            @if($cart->product_color)
                                                <div class="cart-control-group">
                                                    <label>Color</label>
                                                    <span class="cart-variant-value">{{ $cart->product_color }}</span>
                                                </div>
                                            @endif
                                            @if($cart->product_size)
                                                <div class="cart-control-group">
                                                    <label>Size</label>
                                                    <span class="cart-variant-value">{{ $cart->product_size }}</span>
                                                </div>
                                            @endif
                                            <div class="cart-control-group">
                                                <label>Qty</label>
                                                <select class="cart-select cartinput-quantity" data-cart-quantity>
                                                    @for($quantity = 1; $quantity <= $quantityLimit; $quantity++)
                                                        <option value="{{ $quantity }}" {{ (int) $cart->quantity === $quantity ? 'selected' : '' }}>{{ $quantity }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="cart-item-actions">
                                            <button type="button" class="cart-btn cart-btn-remove" data-cart-remove
                                                aria-label="Remove {{ $cart->product_name }}">
                                                <i class="fa fa-trash-o"></i><span class="cart-remove-text">Remove</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="cart-item-pricing">
                                        <div class="cart-item-unit-price Price">
                                            @if($regularPrice > (float) $cart->unit_price)
                                                <span class="regular-price cart_item_regular_price">{{ $febCurrency->format($regularPrice) }}</span>
                                            @endif
                                            <span>{{ $febCurrency->format($cart->unit_price) }}</span>
                                        </div>
                                        <div class="cart-item-subtotal">
                                            <span class="cartinput-price" data-line-total>{{ $febCurrency->format($cart->total_price) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5" id="empty-cart-message">
                                    <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h3>Your cart is empty</h3>
                                    <p class="text-muted">Add products to see them here.</p>
                                    <a href="{{ route('shop-new') }}" class="cart-cta-primary d-inline-block">Start Shopping</a>
                                </div>
                            @endforelse

                            @if(false)
                            <div class="cart-item new-designed-element" data-color="#ffffff" data-type="mens polo t-shirt"
                                data-productkey="71911" data-price="750" data-prevsize="L" data-prevqty="2">


                                <div class="cart-item-image">
                                    <img src="{{ asset('feb/products/6a0ac6d6b9b25-square.jpg') }}" data-frontimg="6a0ac6d6b9b25-square.jpg"
                                        data-backimg="6a0ac6d6b9b25-square.jpg" onclick="imagePopup(event)"
                                        style="background-color:#ffffff;"
                                        alt="Single Jersey Knitted Cotton Polo - Chocolate" />
                                </div>


                                <div class="cart-item-details">
                                    <h3 class="cart-item-title">
                                        <a href="{{ route('single-product') }}">Single
                                            Jersey Knitted Cotton Polo - Chocolate</a>
                                    </h3>



                                    <div class="cart-item-controls">
                                        <div class="cart-control-group">
                                            <label>Size</label>
                                            <select class="cart-select cartinput-size-select" onchange="updateCart(event);">
                                                <option value="M">M</option>
                                                <option value="L" selected>L</option>
                                                <option value="XL">XL</option>
                                                <option value="2XL">2XL</option>
                                            </select>
                                        </div>

                                        <div class="cart-control-group">
                                            <label>Qty</label>
                                            <select class="cart-select cartinput-quantity" onchange="updateCart(event);">
                                                <option value="1">1</option>
                                                <option value="2" selected>2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="cart-item-actions">
                                        <button class="cart-btn cart-btn-add products-cart-button"
                                            data-title="Single Jersey Knitted Cotton Polo - Chocolate"
                                            data-image="{{ asset('feb/products/6a0ac6d6b9b25-square.jpg') }}" data-productkey="71911"
                                            data-color="#ffffff">
                                            <i class="fa fa-plus"></i> Add Size
                                            <i class="products-cart-button-loader fa fa-circle-o-notch fa-spin"
                                                style="display: none;"></i>
                                        </button>
                                        <button class="cart-btn cart-btn-remove" onclick="removeFromCart(event)">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </div>


                                <div class="cart-item-pricing">

                                    <div class="cart-item-unit-price Price">
                                        <span class="regular-price cart_item_regular_price">৳980</span>
                                        <span>৳750</span>
                                    </div>


                                    <div class="cart-item-subtotal">
                                        <span class="regular-subtotal cart_regular_price_field">৳1960</span>
                                        <span class="cartinput-price">৳1500</span>
                                    </div>
                                </div>
                            </div>

                            <div class="cart-item new-designed-element" data-color="#ffffff" data-type="mens polo t-shirt"
                                data-productkey="71911" data-price="750" data-prevsize="L" data-prevqty="2">


                                <div class="cart-item-image">
                                    <img src="{{ asset('feb/products/6a0ac6d6b9b25-square.jpg') }}" data-frontimg="6a0ac6d6b9b25-square.jpg"
                                        data-backimg="6a0ac6d6b9b25-square.jpg" onclick="imagePopup(event)"
                                        style="background-color:#ffffff;"
                                        alt="Single Jersey Knitted Cotton Polo - Chocolate" />
                                </div>


                                <div class="cart-item-details">
                                    <h3 class="cart-item-title">
                                        <a href="{{ route('single-product') }}">Single
                                            Jersey Knitted Cotton Polo - Chocolate</a>
                                    </h3>



                                    <div class="cart-item-controls">
                                        <div class="cart-control-group">
                                            <label>Size</label>
                                            <select class="cart-select cartinput-size-select" onchange="updateCart(event);">
                                                <option value="M">M</option>
                                                <option value="L" selected>L</option>
                                                <option value="XL">XL</option>
                                                <option value="2XL">2XL</option>
                                            </select>
                                        </div>

                                        <div class="cart-control-group">
                                            <label>Qty</label>
                                            <select class="cart-select cartinput-quantity" onchange="updateCart(event);">
                                                <option value="1">1</option>
                                                <option value="2" selected>2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="cart-item-actions">
                                        <button class="cart-btn cart-btn-add products-cart-button"
                                            data-title="Single Jersey Knitted Cotton Polo - Chocolate"
                                            data-image="{{ asset('feb/products/6a0ac6d6b9b25-square.jpg') }}" data-productkey="71911"
                                            data-color="#ffffff">
                                            <i class="fa fa-plus"></i> Add Size
                                            <i class="products-cart-button-loader fa fa-circle-o-notch fa-spin"
                                                style="display: none;"></i>
                                        </button>
                                        <button class="cart-btn cart-btn-remove" onclick="removeFromCart(event)">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </div>


                                <div class="cart-item-pricing">

                                    <div class="cart-item-unit-price Price">
                                        <span class="regular-price cart_item_regular_price">৳980</span>
                                        <span>৳750</span>
                                    </div>


                                    <div class="cart-item-subtotal">
                                        <span class="regular-subtotal cart_regular_price_field">৳1960</span>
                                        <span class="cartinput-price">৳1500</span>
                                    </div>
                                </div>
                            </div>

                            @endif


                        </div>
                    </div>


                    <div class="cart-summary-column">
                        <div class="cart-summary-card">
                            <div class="cart-summary-header">
                                <h2>Order Summary</h2>
                            </div>
                            <div class="cart-summary-content">
                                <div class="cart-summary-row">
                                    <span class="label">Subtotal (<span data-summary-count>{{ $cartQuantity }}</span> items)</span>
                                    <span class="value checkout-total">{{ $febCurrency->format($cartSubtotal) }}</span>
                                </div>
                                <div class="cart-summary-row">
                                    <span class="label">Shipping</span>
                                    <span class="value">Calculated at checkout</span>
                                </div>
                                <div class="cart-summary-row total">
                                    <span class="label">Total</span>
                                    <span class="value">
                                        <del class="prev-total"></del>
                                        <span class="checkout-total">{{ $febCurrency->format($cartSubtotal) }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="cart-summary-actions">
                                <a href="{{ $carts->isNotEmpty() ? route('theme-checkout') : '#' }}"
                                    class="cart-cta-primary btnproceed{{ $carts->isEmpty() ? ' disabled' : '' }}"
                                    @if($carts->isEmpty()) aria-disabled="true" onclick="return false;" @endif>
                                    Place Order
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                                <a href="{{ route('shop-new') }}" class="cart-cta-secondary">Continue Shopping</a>
                                <a href="#" class="cart-cta-international">
                                    <i class="fa fa-globe"></i> Ship Outside Bangladesh
                                </a>
                            </div>


                            {{-- <div class="cart-bulk-notice">
                                For bulk orders, please call <strong>+8809677666888</strong> or email
                                <strong>cs@fabrilife.com</strong>
                            </div> --}}

                            <div class="cart-trust-badges">
                                <div class="trust-badge">
                                    <i class="fa fa-shield"></i>
                                    <span>Secure Checkout</span>
                                </div>
                                <div class="trust-badge">
                                    <i class="fa fa-truck"></i>
                                    <span>Fast Delivery</span>
                                </div>
                                <div class="trust-badge">
                                    <i class="fa fa-refresh"></i>
                                    <span>Easy Returns</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="alert-container"></div>


                <div class="cart-related-section">
                    <div class="cart-related-header">
                        <h2>You May Also Like</h2>
                        <p>Explore more products based on your cart</p>
                    </div>
                    <div class="cart-related-products">
                        @forelse($relatedProducts as $relatedProduct)
                            @php
                                $relatedPrice = $relatedProduct->discount_price > 0 && $relatedProduct->discount_price < $relatedProduct->product_value
                                    ? $relatedProduct->discount_price
                                    : $relatedProduct->product_value;
                            @endphp
                            <div class="related-product-card">
                                <a href="{{ route('single-product', $relatedProduct->slug ?: $relatedProduct->id) }}" class="related-product-link">
                                    <div class="related-product-image">
                                        <img src="{{ \App\Support\MediaStorage::url($relatedProduct->img_path, 'products') }}"
                                            alt="{{ $relatedProduct->name }}">
                                    </div>
                                    <div class="related-product-info">
                                        <h4 class="related-product-title">{{ $relatedProduct->name }}</h4>
                                        <p class="related-product-price">{{ $febCurrency->format($relatedPrice) }}</p>
                                    </div>
                                    <div class="related-product-action"><span class="view-product-btn">View Product</span></div>
                                </a>
                            </div>
                        @empty
                            <p class="text-muted">No recommendations available.</p>
                        @endforelse

                        @if(false)
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/5eb191ab6d7b7.jpg') }}" alt="Mens Premium Platinum Polo - Dynasty"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Platinum Polo - Dynasty</h4>
                                    <p class="related-product-price">
                                        ৳990
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/5eb191ac092a4.jpg') }}" alt="Mens Premium Luxe Polo - Dominion"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Luxe Polo - Dominion</h4>
                                    <p class="related-product-price">
                                        ৳1090
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a0ac6d6b9b25-square.jpg') }}" alt="Mens Premium Luxe Polo - Catalyst"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Luxe Polo - Catalyst</h4>
                                    <p class="related-product-price">
                                        ৳1190
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a0da8d149173-square.jpg') }}"
                                        alt="Mens Premium Platinum Polo - Sterling" style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Platinum Polo - Sterling</h4>
                                    <p class="related-product-price">
                                        ৳990
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}"
                                class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a0da8d06a68e-square.jpg') }}"
                                        alt="Classical Edition Single Jersey Knitted Polo- Starlit"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Classical Edition Single Jersey Knitted Polo-
                                        Starlit</h4>
                                    <p class="related-product-price">
                                        ৳890
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}"
                                class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a005bc3e38e7-square.jpg') }}"
                                        alt="Mens Premium Limited Edition Polo - Regardz"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Limited Edition Polo - Regardz</h4>
                                    <p class="related-product-price">
                                        ৳1293
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a22c21f00acd-square.jpg') }}"
                                        alt="Mens Premium Platinum Polo - Vantage" style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Platinum Polo - Vantage</h4>
                                    <p class="related-product-price">
                                        ৳990
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a005bc3e38e7-square.jpg') }}"
                                        alt="Mens Premium Platinum Polo - Monumental" style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Platinum Polo - Monumental</h4>
                                    <p class="related-product-price">
                                        ৳990
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}"
                                class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a12d65e72966-square.jpg') }}"
                                        alt="Classical Edition Single Jersey Knitted Polo - Mevarick"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Classical Edition Single Jersey Knitted Polo -
                                        Mevarick</h4>
                                    <p class="related-product-price">
                                        ৳890
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}"
                                class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a30d1ab9a5ea-square.jpg') }}"
                                        alt="Mens Premium Limited Edition Polo - Zardish"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Limited Edition Polo - Zardish</h4>
                                    <p class="related-product-price">
                                        ৳1260
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}"
                                class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a22bd10adfa7-square.jpg') }}"
                                        alt="Mens Premium Limited Edition Polo - Edward"
                                        style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Limited Edition Polo - Edward</h4>
                                    <p class="related-product-price">
                                        ৳1260
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        <div class="related-product-card">
                            <a href="{{ route('single-product') }}" class="related-product-link">
                                <div class="related-product-image"
                                    style="background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);">
                                    <img src="{{ asset('feb/products/6a005bc2e203b-square.jpg') }}"
                                        alt="Mens Premium Limited Edition Polo - Beige" style="background-color:#ffffff;">
                                </div>
                                <div class="related-product-info">
                                    <h4 class="related-product-title">Mens Premium Limited Edition Polo - Beige</h4>
                                    <p class="related-product-price">
                                        ৳1390
                                    </p>
                                </div>
                                <div class="related-product-action">
                                    <span class="view-product-btn">View Product</span>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="modal fade addToOrdermodal" style="display:none">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title addToOrdermodal-header">Add Products to Order</h5>
                        </div>
                        <div class="modal-body addToOrdermodal-body">
                            <div>
                                <input autocomplete="off" class="form-control orderRefText" name="reference"
                                    placeholder="Enter order reference number" style="border-radius: 0;">
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="cart-cta-primary add-order-admin">Add to Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade viewmodal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title viewmodal-header">Product Image</h4>
                        </div>
                        <div class="modal-body viewmodal-body"></div>
                    </div>
                </div>
            </div>


            <div class="modal fade pricemodal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title pricemodal-header"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="pricemodal-content">
                            <div class="pricemodal-top-row">
                                <div class="card-view">
                                    <div class="card-photo-container">
                                        <img class="card-photo" src='' alt='Loading..' />
                                    </div>
                                    <div class="card-info-wrapper">
                                        <div class="card-title"></div>
                                        <button type="button" class="size-guide-toggle no-data"
                                            onclick="toggleSizeGuide()">
                                            <i class="fa fa-ruler-combined"></i> Size Guide
                                        </button>
                                    </div>
                                </div>
                                <div class="modal-size-chart empty">
                                    <!-- Content dynamically populated by populateModalSizeChart() in cart_new.js -->
                                </div>
                            </div>
                            <div class="modal-body pricemodal-body product-related">
                            </div>
                        </div>
                        <div class="modal-footer pricemodal-footer">
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade szc" style="z-index: 1200;">
                <div class="modal-dialog commonmodal-dialog" role="document">
                    <div class="modal-content" style="border: 0px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title szc-header" style="text-align: center;">Size Chart</h5>
                        </div>
                        <div class="modal-body szc-body">
                            <table class="table table-sm table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Size</th>
                                        <th class="text-center">Length</th>
                                        <th class="text-center">Width</th>
                                        <th class="text-center">Sleeve</th>
                                    </tr>
                                </thead>
                                <tbody class="szc-tbody"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer szc-footer" style="display: inline-table; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade commonmodal">
                <div class="modal-dialog" role="document" style="max-width: 500px; margin: 80px auto;">
                    <div class="modal-content"
                        style="border: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                        <div class="modal-header"
                            style="background: #222f3f; color: white; border: 0; padding: 18px 20px; position: relative;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); opacity: 1; color: #fff; font-size: 28px; font-weight: 300; text-shadow: none;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title commonmodal-header"
                                style="margin: 0; font-size: 18px; font-weight: 500; text-align: center; width: 100%;">
                            </h4>
                        </div>
                        <div class="modal-body commonmodal-body" style="padding: 20px; background: #fff;">
                        </div>
                        <div class="modal-footer commonmodal-footer"
                            style="display: inline-table; width: 100%; padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #eee;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var cartEndpoint = '{{ url('ajax/theme-carts') }}';
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function cartNotice(message, type) {
                document.querySelectorAll('.theme-cart-notice').forEach(function(item) { item.remove(); });
                var notice = document.createElement('div');
                notice.className = 'theme-cart-notice';
                notice.setAttribute('role', 'status');
                notice.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10080;display:flex;align-items:center;gap:10px;max-width:360px;padding:12px 16px;border-radius:6px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,.25);font-size:14px;background:' + (type === 'error' ? '#c62828' : '#16803c');
                notice.innerHTML = '<i class="fa ' + (type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle') + '"></i><span></span>';
                notice.querySelector('span').textContent = message;
                document.body.appendChild(notice);
                window.setTimeout(function() { notice.remove(); }, 3000);
            }

            function formatMoney(value) {
                return Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateCartPage(data) {
                var count = parseInt(data.cart_count, 10) || 0;
                document.querySelectorAll('.checkout-total').forEach(function(element) {
                    element.textContent = window.formatStoreCurrency(data.cart_subtotal);
                });
                document.querySelectorAll('[data-summary-count]').forEach(function(element) {
                    element.textContent = count;
                });

                var countLabel = document.querySelector('.cart-page-header .cart-count');
                if (countLabel) countLabel.innerHTML = '<span id="cart-page-count">' + count + '</span> ' + (count === 1 ? 'item' : 'items');

                document.querySelectorAll('#cartBadge, .shopping-cart-badge').forEach(function(badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            }

            document.querySelectorAll('[data-cart-quantity]').forEach(function(select) {
                select.addEventListener('change', function () {
                    var item = select.closest('[data-cart-id]');
                    var previousQuantity = item.getAttribute('data-prevqty');
                    select.disabled = true;

                    window.axios.patch(cartEndpoint + '/' + item.getAttribute('data-cart-id'), {
                        quantity: parseInt(select.value, 10)
                    }, {
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    }).then(function(response) {
                        var data = response.data;
                        item.setAttribute('data-prevqty', select.value);
                        item.querySelector('[data-line-total]').textContent = window.formatStoreCurrency(data.line_total);
                        updateCartPage(data);
                        cartNotice(data.message || 'Cart updated successfully.', 'success');
                    }).catch(function(error) {
                        select.value = previousQuantity;
                        var data = error.response && error.response.data ? error.response.data : {};
                        cartNotice(data.message || 'Could not update cart.', 'error');
                    }).then(function() {
                        select.disabled = false;
                    });
                });
            });

            document.querySelectorAll('[data-cart-remove]').forEach(function(button) {
                button.addEventListener('click', function () {
                    if (!window.confirm('Remove this product from cart?')) return;

                    var item = button.closest('[data-cart-id]');
                    button.disabled = true;
                    window.axios.delete(cartEndpoint + '/' + item.getAttribute('data-cart-id'), {
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    }).then(function(response) {
                        var data = response.data;
                        item.remove();
                        updateCartPage(data);
                        cartNotice(data.message || 'Product removed from cart.', 'success');
                        if ((parseInt(data.cart_count, 10) || 0) === 0) {
                            window.setTimeout(function() { window.location.reload(); }, 500);
                        }
                    }).catch(function(error) {
                        var data = error.response && error.response.data ? error.response.data : {};
                        cartNotice(data.message || 'Could not remove product.', 'error');
                        button.disabled = false;
                    });
                });
            });
        });
    </script>
@endsection
