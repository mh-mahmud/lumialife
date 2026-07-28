@extends('front.feb.layouts.master')

@section('title')
    Product List
@endsection

@section('content')
    @if (isset($selectedCategory))
        <script>
            window.selectedCategoryName = "{{ $selectedCategory->category_name }}";
        </script>
    @endif
    <style media="screen">
        /* =====================================================
           Price Modal - Redesigned UI (Global Styles)
           ===================================================== */

        /* Modal z-index override to appear above floating cart (z-index: 9999) */
        .pricemodal {
            z-index: 10050 !important;
        }

        .pricemodal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .pricemodal+.modal-backdrop,
        .modal-backdrop.show {
            z-index: 10040 !important;
        }

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

        /* Modal Content Layout - Two Column Top, Full Width Bottom */
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
            float: none !important;
            flex-shrink: 0;
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
            width: auto;
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

        /* Size Chart Tabs (for multiple charts) */
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

        .pricemodal .size-chart-unit {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 6px;
            text-align: right;
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

        /* Hide cancel button - X in header serves same purpose */
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

        .shop-option-group {
            margin-bottom: 16px;
        }

        .shop-option-label {
            display: block;
            margin-bottom: 8px;
            color: #111827;
            font-size: 13px;
            font-weight: 600;
        }

        .shop-option-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .shop-option-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 36px;
            padding: 7px 11px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background: #fff;
            color: #1f2937;
            cursor: pointer;
            font-size: 13px;
        }

        .shop-option-button.is-selected {
            border-color: #111827;
            background: #111827;
            color: #fff;
        }

        .shop-color-dot {
            width: 17px;
            height: 17px;
            border: 1px solid rgba(0, 0, 0, .18);
            border-radius: 50%;
        }

        .shop-modal-quantity {
            width: 100%;
            padding: 9px 11px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .shop-cart-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10080;
            display: flex;
            align-items: center;
            gap: 9px;
            max-width: 360px;
            padding: 12px 16px;
            border-radius: 6px;
            background: #16803c;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
            font-size: 14px;
        }

        .shop-cart-toast.error {
            background: #c62828;
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

        /* =====================================================
           Price Modal - Mobile Responsive
           ===================================================== */
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

            /* Card view - full width, auto height on mobile */
            .pricemodal .card-view {
                width: 100% !important;
                height: auto !important;
                min-height: unset !important;
                max-height: none !important;
                padding: 16px !important;
                flex-direction: column !important;
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

            /* Card photo image - visible and properly sized */
            .pricemodal img.card-photo {
                width: 100% !important;
                height: auto !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            /* Mobile: size chart stacks below product instead of side */
            .pricemodal .pricemodal-top-row.show-size-chart .modal-size-chart {
                width: 100% !important;
            }

            .pricemodal .modal-size-chart-table {
                font-size: 11px;
            }

            .pricemodal .modal-footer.pricemodal-footer {
                position: sticky;
                bottom: 0;
                background: #fff;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 577px) and (max-width: 768px) {
            .pricemodal .modal-dialog {
                max-width: 380px;
            }

            .pricemodal .modal-dialog.expanded {
                max-width: 520px;
            }
        }

        /* Number Input (Quantity Selector) - centered styling */
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

        /* Center align h5 title in modal body */
        .pricemodal .pricemodal-body h5 {
            text-align: center;
            margin-bottom: 12px;
        }

        /* =====================================================
           End of Price Modal Styles
           ===================================================== */

        #campaign .ais-RefinementList-item:nth-child(2) {
            display: none;
        }

        #campaign .ais-RefinementList-item:nth-child(1) div {
            font-size: 18px;
            color: #ff0000;
            font-weight: bold;
            font-family: monospace;
        }

        .searchbox .input-group-btn {
            background: #eee;
        }

        .campaign {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #FFF;
            font-size: .9rem;
            z-index: 9;
            font-weight: bold;
            line-height: 1.3rem;
            padding: 0px 12px;
            font-family: monospace;
            background-color: #0275d8;
            background-image: linear-gradient(225deg, #000000 0%, #5c0000 50%, #ff1c00 100%);
        }

        .products-cart-button.outofstockcartbtn {
            background: #8b8b8b;
            pointer-events: none;
        }

        .outofstock {
            position: absolute;
            background: #8b8b8b;
            font-weight: 500;
            text-align: center;
            color: #fff;
            font-size: 13px;
            border-radius: 0px;
            top: 35%;
            right: 50%;
            transform: translate(50%, -50%);
        }

        .outofstock>span {
            padding: 10px;
        }

        .free_delivery {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #fff;
            background: #000000;
            font-size: 0.8rem;
            line-height: 1.3rem;
            padding: 0px 10px 0 5px;
            font-style: italic;
        }

        .free_delivery img {
            height: 18px;
            margin-top: -4px;
            transform: skew(-12deg) rotate(0deg);
            -webkit-transform: skew(-12deg) rotate(0deg);
            -moz-transform: skew(-12deg) rotate(0deg);
        }

        .footer-container {
            display: block;
        }

        .home-nav-fixed {
            position: relative;
        }

        .ais-tag-item {
            cursor: pointer;
        }

        .products-like-button {
            width: auto;
            position: absolute;
            top: 5px;
            left: 14px;
            height: auto;
            border: 0px;
            background: unset;
        }

        .products-share-button {
            position: absolute;
            top: 0px;
            right: 15px;
            background: unset;
            width: auto;
            border: 0px;
        }

        .products-cart-button {
            width: 100%;
            text-align: center;
            background: #333333;
            padding-top: 5px;
            padding-bottom: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 13px;
            color: #FFF;
            position: relative;
            -webkit-transition: .3s ease;
            transition: .3s ease;
        }

        .card-view {
            width: 30%;
            font-size: .9rem;
            display: inline-block;
            float: left;
            padding: 0px 15px 0px 15px;
            margin: auto 0;
        }

        .card-title {
            text-align: center;
            font-weight: bold;
            margin: auto 0 7px 0;
            font-size: 13px;
        }

        img.card-photo {
            background: #FFF;
            width: 100%;
        }

        #tagbox {
            display: none !important;
        }

        .modal-body.pricemodal-body {
            width: 70%;
            display: inline-block;
            float: left;
            border-left: 1px solid #eee;
        }

        ul.size-selectors-container.list-inline {
            position: relative;
        }

        ul.size-selectors-container.list-inline.error:after {
            content: "Please Select Size";
            position: absolute;
            top: -23px;
            font-size: .9rem;
            color: red;
            width: 100%;
            left: 0;
            text-align: center;
        }

        ul.list-inline.color-seletcor-container {
            position: relative;
        }

        ul.list-inline.color-seletcor-container.error:after {
            content: "Please Select Color";
            position: absolute;
            top: -23px;
            font-size: .9rem;
            color: red;
            width: 100%;
            left: 0;
            text-align: center;
        }

        .addToCartBtn>i {
            display: none;
            font-size: 1rem;
            height: 1rem;
            width: 1rem;
            line-height: 1rem;
        }

        .modal-title>p {
            margin: 0px;
        }

        .card-text.truncate {
            padding: 0px 10px;
            font-size: 1rem;
            white-space: nowrap;
            line-height: 1.5rem;
            margin-top: 0px;
            height: 1.5rem;
        }

        .products-share-button {
            display: none;
        }

        .products-cart-button>i {
            padding-right: 10px;
            font-size: 1.0rem;
        }

        .products-cart-button-loader {
            display: none;
        }

        .products-cart-button-loader {
            font-size: .8rem !important;
            line-height: 1rem;
            padding: 0px !important;
            color: #818a91;
        }

        div#cats {
            margin-top: 15px;
        }



        .algo-search>input {
            z-index: 4 !important;
        }



        #pagination {
            height: 20px;
        }

        body.still {
            height: 100vh !important;
            overflow: hidden;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.mens:before {
            content: "Mens";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.sports:before {
            content: "Sports";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.womens:before {
            content: "Womens";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.kids:before {
            content: "Kids";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.mask:before {
            content: "Premium cotton Mask";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.wholesale:before {
            content: "Wholesale";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        ul.ais-RefinementList-list div.facet-item.checkitem.ppe:before {
            content: "PPE";
            display: block;
            font-weight: bold;
            color: #03A9F4;

            font-family: monospace;
        }

        .non_mediacal.show {
            display: block;
            width: 100%;
            text-align: center;
            font-size: .8rem;
            position: absolute;
            bottom: 125px;
            color: #000;
        }

        .non_mediacal.hide {
            display: none;
        }

        .discounted_amount {
            background: #333333;
            width: fit-content;
            position: absolute;
            left: 0;
            right: 0;
            margin-left: auto;
            color: #FFF;
            bottom: 91px;
            margin-right: auto;
            font-size: .8rem;
            line-height: 1.3rem;
        }

        .discounted_amount span {
            margin: 0px 7px 0px 7px;
        }

        .sale {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #FFF;
            background: #f44336;
            font-size: 0.8rem;
            line-height: 1.3rem;
            /* border-bottom-left-radius: 7px; */
        }

        .sale span {
            margin: 0px 15px;
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
            background-color: #2bb673;
            border: 1px solid #9e9e9e;
            color: #FFF;
            font-size: 14px;
            cursor: pointer;
            -webkit-transition: 0.4s ease-in-out;
            transition: 0.1s ease-in-out;
            border-radius: 0px !Important;
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

        .products-like-button {
            display: none;
        }

        .level1 {
            color: #00aeef !important;
            font-weight: bold;
        }

        .level2 {
            margin-left: 15px;
            font-weight: bold;

        }

        .level3 {
            margin-left: 30px;
            color: #555 !important;
        }

        .level4 {
            margin-left: 45px;
        }

        .newarrival {
            /* font-size: 1rem !important; */
            margin-bottom: 5px;
            /* color: #FF5722 !important; */
            /* font-style: italic; */
        }

        .newarrival:after {
            content: "\f0e7";
            font: normal normal normal 14px/1 FontAwesome;
            padding-left: 8px;
            color: #FF9800;
        }

        .cat-header {
            padding-left: 0px;
            margin-top: 0px;
        }

        .algo-search button {
            background: #FFF;
            height: 38px;
            border: 1px solid #ddd;
        }


        @media(max-width: 996px) {
            .cart-table tbody td.spinner {
                padding: 35% 0% !important;
            }

            .free_delivery {
                font-size: 0.7rem;
                line-height: 1.0rem;
                padding: 0px 7px 0 2px;
                font-style: italic;
            }

            .campaign {
                font-size: 0.7rem;
                line-height: 1.0rem;
                padding: 0px 7px 0 2px;
            }

            .free_delivery img {
                height: 16px;
            }


            .sale {
                font-size: 0.7rem;
                line-height: 1.0rem;
                padding: 0px;
            }

            .card-view {
                width: 100%;
                display: flex;
                padding: 0px 15px;
                height: 70px;
                background: #f8f8f8;
                overflow: hidden;
                color: #666666;
            }

            .card-title {
                width: 100%;
                margin: auto 0 auto 0;
            }

            .card-photo-container {
                width: 25%;
            }

            .card-photo {
                height: 100%;
                width: auto !important;
            }

            .modal-body.pricemodal-body {
                width: 100%;
                display: inline-block;
                float: left;
                border: 0px;
            }

            .card-text {
                font-size: .8em;
                line-height: 1.2rem;
            }

            .card-text.truncate {
                height: 1.9rem !important;
                font-size: .7em !important;
                white-space: unset !important;
                word-break: break-word;
                overflow: hidden;
                line-height: 1rem !important;
                margin-bottom: 4px !important;
            }

            .products-like-button {
                top: 0px;
                left: 9px;
            }

            #hits {
                margin-left: 0px;
            }

            .algo-search button {
                height: 35px;
                line-height: 30px;
                padding: 0px 10px;
                background: #FFF;
                border: 1px solid #ddd;
                padding-bottom: 2px;
            }

            .discounted_amount {
                bottom: 77px;
                font-size: .7rem;
                line-height: 1.0rem;
            }

            .non_mediacal.show {
                bottom: 110px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.1.1/themes/algolia-min.css"
        integrity="sha256-nkldBwBn2NQqRL1mod7BqHsJ6cEOn6u/ln6F/lI4CFo=" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('feb/css/tees.css?v=82480') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        .shop-category-list,
        .shop-subcategory-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .shop-category-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none !important;
            transition: background-color .15s ease, color .15s ease;
        }

        .shop-category-link:hover,
        .shop-category-link.is-active {
            color: #6366f1;
            background: #eef2ff;
        }

        .shop-category-link .facet-count {
            margin-left: auto;
            margin-right: 0;
        }

        .shop-subcategory-list .shop-category-link {
            padding-left: 30px;
            color: #4b5563;
            font-size: 13px;
            font-weight: 500;
        }

        .shop-subcategory-list .shop-subcategory-list .shop-category-link {
            padding-left: 44px;
        }

        .quick-filter-item.server-category-filter {
            text-decoration: none;
        }

        .quick-filter-item.server-category-filter.is-active {
            background: #6366f1;
            border-color: #6366f1;
            color: #fff;
        }

        .shop-collection-title {
            margin: 0 12px 8px;
            color: #1f2937;
            font-size: 24px;
            font-weight: 700;
        }

        @media (min-width: 768px) {
            .feb-shop-page .teescontainer {
                padding-left: 32px !important;
            }

            .feb-shop-page .quick-filters-section {
                padding-top: 0 !important;
            }
        }


        #pagination {
            padding-top: 50px;
            padding-bottom: 80px
        }

        /* Screenshot-matched shop listing */
        .feb-shop-page {
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }
        .feb-shop-page > .container-fluid { padding: 0; }
        .feb-shop-page .teescontainer {
            width: min(1144px, calc(100% - 32px)) !important;
            max-width: 1144px !important;
            min-height: 90vh;
            margin: 0 auto !important;
            padding: 0 !important;
            float: none !important;
        }
        .feb-shop-page .resultset {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            float: none !important;
        }
        .shop-listing-toolbar {
            position: relative;
            display: grid;
            grid-template-columns: 150px minmax(0, 1fr) 150px;
            align-items: center;
            min-height: 114px;
            padding-top: 10px;
        }
        .shop-listing-title {
            margin: 0;
            color: #050505;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.1;
            text-align: center;
            text-transform: uppercase;
        }
        .shop-filter-trigger,
        .shop-sort-select {
            height: 35px;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            background: #fff;
            color: #222;
            font-size: 13px;
            font-weight: 600;
        }
        .shop-filter-trigger {
            width: 104px;
            padding: 0 12px;
            text-align: left;
            cursor: pointer;
        }
        .shop-filter-trigger i { margin-right: 7px; }
        .shop-sort-form { justify-self: end; }
        .shop-sort-select {
            width: 104px;
            padding: 0 10px;
            cursor: pointer;
        }
        .feb-shop-page #results-topbar,
        .feb-shop-page .shop-collection-title,
        .feb-shop-page #searchbox,
        .feb-shop-page .quick-filters-section,
        .feb-shop-page .main-refinement {
            display: none !important;
        }
        .feb-shop-page #hits { padding: 0 !important; }
        .feb-shop-page .shop-empty-state {
            grid-column: 1 / -1;
            display: flex;
            min-height: 390px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin: 0;
            padding: 55px 20px;
            border: 1px solid #ededed !important;
            background: linear-gradient(180deg, #fff 0%, #fafafa 100%);
            color: #111;
            text-align: center;
        }
        .feb-shop-page .shop-empty-icon {
            display: flex;
            width: 82px;
            height: 82px;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            border-radius: 50%;
            background: #f1f1f1;
            color: #222;
            font-size: 30px;
        }
        .feb-shop-page .shop-empty-state h2 {
            margin: 0 0 10px;
            color: #090909;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 25px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .feb-shop-page .shop-empty-state p {
            max-width: 500px;
            margin: 0 0 25px;
            color: #6f6f6f;
            font-size: 14px;
            line-height: 1.6;
        }
        .feb-shop-page .shop-empty-state a {
            min-width: 175px;
            border-radius: 24px;
            background: #171717;
            padding: 12px 24px;
            color: #fff !important;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .6px;
            text-decoration: none !important;
            transition: background .2s ease, transform .2s ease;
        }
        .feb-shop-page .shop-empty-state a:hover {
            transform: translateY(-2px);
            background: #ed1c24;
        }
        .feb-shop-page #hits .ais-InfiniteHits-list {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            column-gap: 22px !important;
            row-gap: 20px !important;
        }
        .feb-shop-page .ais-InfiniteHits-item { overflow: visible !important; }
        .feb-shop-page .product-card {
            display: block;
            height: auto;
            overflow: visible;
            border: 0;
            border-radius: 0;
            background: #fff;
        }
        .feb-shop-page .gallerythumbWrapper {
            aspect-ratio: 1 / 1.32;
            background: #f5f5f5;
        }
        .feb-shop-page .gallerythumb { object-fit: cover; }
        .feb-shop-page .product-card:hover .gallerythumb { transform: none; }
        .feb-shop-page .product-details {
            display: block;
            min-height: 94px;
            padding: 10px 0 0;
            background: #fff;
        }
        .feb-shop-page .product-name {
            min-height: 42px;
            margin: 0 0 5px;
            color: #090909;
            font-size: 15px;
            font-weight: 700;
            line-height: 21px;
            text-align: left !important;
            text-transform: uppercase;
        }
        .feb-shop-page .product-info-row,
        .feb-shop-page .product-info-left,
        .feb-shop-page .product-price-row {
            justify-content: flex-start;
            text-align: left !important;
        }
        .feb-shop-page .price-current {
            color: #090909;
            font-size: 16px;
            font-weight: 700;
        }
        .feb-shop-page .price-original { font-size: 12px; }
        .feb-shop-page .outofstock-badge {
            width: auto;
            min-width: 132px;
            padding: 12px 18px;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
            white-space: nowrap;
        }
        .feb-shop-page .btn-zoom-image,
        .feb-shop-page .rating-badge,
        .feb-shop-page .badge-discount {
            display: none !important;
        }
        .feb-shop-page .btn-wishlist,
        .feb-shop-page .btn-add-cart {
            position: absolute;
            right: 15px;
            z-index: 7;
            display: flex !important;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            transform: translateX(8px);
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .97);
            color: #050505;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .16);
            opacity: 0;
            cursor: pointer;
            transition: opacity .18s ease, transform .18s ease, color .18s ease,
                background-color .18s ease;
        }
        .feb-shop-page .btn-wishlist {
            top: 20px;
            bottom: auto;
        }
        .feb-shop-page .btn-add-cart {
            top: 68px;
            bottom: auto;
            padding: 0;
        }
        .feb-shop-page .btn-wishlist i,
        .feb-shop-page .btn-add-cart i {
            margin: 0;
            color: inherit;
            font-size: 20px;
            line-height: 1;
        }
        .feb-shop-page .product-card:hover .btn-wishlist,
        .feb-shop-page .product-card:hover .btn-add-cart,
        .feb-shop-page .btn-wishlist.active {
            transform: translateX(0);
            opacity: 1;
        }
        .feb-shop-page .btn-wishlist:hover,
        .feb-shop-page .btn-add-cart:hover {
            transform: translateX(0) scale(1.04);
            background: #fff;
            color: #e31b23;
        }
        .feb-shop-page .btn-add-cart:disabled {
            display: none !important;
        }
        .feb-shop-page .refineset {
            position: fixed !important;
            top: 0;
            left: 0;
            z-index: 11020;
            display: block !important;
            width: min(360px, 88vw) !important;
            height: 100vh;
            overflow-y: auto;
            transform: translateX(-105%);
            background: #fff;
            box-shadow: 8px 0 30px rgba(0, 0, 0, .18);
            transition: transform .25s ease;
        }
        .feb-shop-page .refineset.is-open { transform: translateX(0); }
        .feb-shop-page .refineset:not(.is-open) { transform: translateX(-105%) !important; }
        .shop-filter-overlay {
            position: fixed;
            inset: 0;
            z-index: 11010;
            display: none;
            background: rgba(0, 0, 0, .4);
        }
        .shop-filter-overlay.is-open { display: block; }
        .feb-shop-page .algo-close-filter {
            display: block !important;
            padding: 16px;
            cursor: pointer;
        }
        .feb-shop-page #pagination {
            padding: 28px 0 70px;
        }
        .feb-shop-page #pagination .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 0;
        }
        .feb-shop-page #pagination .previous .page-link,
        .feb-shop-page #pagination .next .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .feb-shop-page #pagination .pagination-arrow {
            display: block;
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        @media (max-width: 991px) {
            .feb-shop-page #hits .ais-InfiniteHits-list {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 16px !important;
            }
        }
        @media (max-width: 767px) {
            .feb-shop-page .teescontainer {
                width: calc(100% - 20px) !important;
            }
            .shop-listing-toolbar {
                grid-template-columns: 80px 1fr 80px;
                min-height: 82px;
                padding-top: 8px;
            }
            .shop-listing-title { font-size: 19px; }
            .shop-filter-trigger,
            .shop-sort-select {
                width: 76px;
                height: 32px;
                padding-inline: 7px;
                font-size: 11px;
            }
            .feb-shop-page #hits .ais-InfiniteHits-list {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 14px 8px !important;
            }
            .feb-shop-page .gallerythumbWrapper { aspect-ratio: 1 / 1.3; }
            .feb-shop-page .product-name {
                min-height: 32px;
                font-size: 11px;
                line-height: 15px;
            }
            .feb-shop-page .price-current { font-size: 13px; }
            .feb-shop-page .product-details { min-height: 68px; }
            .feb-shop-page .shop-empty-state { min-height: 330px; padding: 42px 16px; }
            .feb-shop-page .shop-empty-icon { width: 68px; height: 68px; margin-bottom: 20px; font-size: 25px; }
            .feb-shop-page .shop-empty-state h2 { font-size: 20px; }
            .feb-shop-page .shop-empty-state p { font-size: 13px; }
            .feb-shop-page .btn-wishlist,
            .feb-shop-page .btn-add-cart {
                right: 8px;
                width: 34px;
                height: 34px;
                transform: none;
                opacity: 1;
            }
            .feb-shop-page .btn-wishlist { top: 8px; }
            .feb-shop-page .btn-add-cart { top: 48px; }
            .feb-shop-page .btn-wishlist i,
            .feb-shop-page .btn-add-cart i { font-size: 16px; }
        }

        /* Match the homepage New In option modal */
        .pricemodal .modal-dialog { width: min(840px, 94vw); max-width: 840px; margin: 5vh auto; }
        .pricemodal .modal-content { overflow: visible; border: 0; border-radius: 0; box-shadow: 0 20px 60px rgba(0,0,0,.24); }
        .pricemodal .modal-header { position: static; min-height: 0; border: 0; background: #fff; padding: 0; }
        .pricemodal .modal-header .pricemodal-header { display: none; }
        .pricemodal .modal-header .close { top: 18px; right: 24px; z-index: 20; width: auto; height: auto; border-radius: 0; background: transparent; color: #111; font-size: 32px; opacity: 1; }
        .pricemodal .pricemodal-content { display: grid; grid-template-columns: minmax(0,1fr) minmax(320px,.95fr); padding: 54px 48px; background: #fff; }
        .pricemodal .card-view { display: flex !important; width: auto !important; padding: 0 38px 0 0 !important; border: 0 !important; background: #fff; }
        .pricemodal .card-photo-container { width: 100% !important; max-width: none !important; border-radius: 0; box-shadow: none; background: #f2f2f2; }
        .pricemodal img.card-photo { width: 100% !important; max-height: 520px; object-fit: contain; }
        .pricemodal .shop-modal-details { align-self: center; font-family: Arial,Helvetica,sans-serif; }
        .pricemodal .card-title { width: 100%; max-height: none; margin: 0 0 14px; color: #080808; font-size: 22px; font-weight: 500; letter-spacing: 1.5px; line-height: 1.3; text-align: left; text-transform: uppercase; }
        .pricemodal .shop-modal-price { display: flex; align-items: center; gap: 14px; margin-bottom: 26px; }
        .pricemodal .shop-modal-price strong { color: #ef3038; font-size: 20px; font-weight: 500; }
        .pricemodal .shop-modal-price del { color: #666; font-size: 14px; }
        .pricemodal .size-guide-toggle { margin: 0 0 20px; padding: 0; color: #ef3038; }
        .pricemodal .modal-body.pricemodal-body { width: 100%; padding: 0; float: none; }
        .pricemodal .shop-option-group { margin-bottom: 20px; }
        .pricemodal .shop-option-label { margin-bottom: 10px; color: #111; font-size: 16px; font-weight: 400; letter-spacing: 1px; }
        .pricemodal .shop-option-button { min-width: 48px; min-height: 42px; border-radius: 0; border-color: #aaa; padding: 10px 13px; color: #111; }
        .pricemodal .shop-option-button.is-selected { border-color: #111; outline: 1px solid #111; background: #fff; color: #111; }
        .pricemodal .shop-modal-quantity { width: 134px; height: 42px; border-radius: 0; text-align: center; }
        .pricemodal .modal-footer.pricemodal-footer { margin-top: 26px; padding: 0; border: 0; background: #fff; }
        .pricemodal .modal-footer .btn-success { border: 0 !important; border-radius: 26px; background: #171717 !important; padding: 14px 20px; font-size: 15px; font-weight: 400; }
        .pricemodal .shop-modal-view-details { display: inline-block; margin-top: 26px; border-bottom: 1px solid #111; color: #111; font-size: 15px; text-decoration: none; }
        @media (max-width: 767px) {
            .pricemodal .modal-dialog { width: min(480px,96vw); margin: 2vh auto; }
            .pricemodal .pricemodal-content { grid-template-columns: 1fr; padding: 45px 20px 28px; }
            .pricemodal .card-view { padding: 0 35px 22px !important; }
            .pricemodal img.card-photo { max-height: 300px; }
            .pricemodal .card-title { font-size: 17px; }
        }
    </style>
    <div class="wrapper-div feb-shop-page">
        <div class="container">
        </div>
        <div class="container-fluid">




            <div class="teescontainer col-sm-12 col-md-11 col-lg-11">
                <aside class="scrollbar refineset" style="padding-bottom: 50px">
                    <section class="facet-wrapper">
                        <i class="fa fa-times algo-close-filter" onClick="closeFilterSidebar()"></i>


                        <div class="sidebar-trending-section">
                            <h4 class="sidebar-section-title trending-title">Special Offers</h4>
                            <div class="trending-list">
                                <a href="{{ route('shop-new', ['collection' => 'new-collection']) }}"
                                    class="trending-item {{ $selectedCollection === 'new-collection' ? 'active' : '' }}"
                                    data-server-filter="true">
                                    <i class="fa fa-clock-o"></i> New Collection
                                </a>
                                <a href="{{ route('shop-new', ['collection' => 'trending']) }}"
                                    class="trending-item {{ $selectedCollection === 'trending' ? 'active' : '' }}"
                                    data-server-filter="true">
                                    <i class="fa fa-fire"></i> Trending Products
                                </a>
                                <a href="{{ route('shop-new', ['collection' => 'lifestyle']) }}"
                                    class="trending-item {{ $selectedCollection === 'lifestyle' ? 'active' : '' }}"
                                    data-server-filter="true">
                                    <i class="fa fa-leaf"></i> Lifestyle Products
                                </a>
                                <a href="{{ route('shop-new', ['collection' => 'best-deal']) }}"
                                    class="trending-item {{ $selectedCollection === 'best-deal' ? 'active' : '' }}"
                                    data-server-filter="true">
                                    <i class="fa fa-tags"></i> Best Deal
                                </a>
                            </div>
                        </div>


                        <div class="sidebar-section sidebar-categories-section">
                            <h4 class="sidebar-section-title categories-title-desktop">
                                <span>Categories</span>
                                <div id="aside-clear-refinements" class="categories-clear-btn"></div>
                            </h4>


                            <!-- custom cats start -->
                            <div class="sidebar-section-content">
                                <div id="catsP">
                                    <div class="ais-Panel">
                                        <div class="ais-Panel-body">
                                            <div>
                                                <div class="ais-RefinementList">
                                                    <ul class="shop-category-list">
                                                        @include(
                                                            'front.feb.components.shop-category-tree',
                                                            [
                                                                'categoryItems' => $categories,
                                                                'selectedCategory' => $selectedCategory,
                                                                'activeCategoryPath' => $activeCategoryPath,
                                                            ]
                                                        )
                                                    </ul>
                                                    <ul class="ais-RefinementList-list" hidden aria-hidden="true">
                                                        <li class="ais-RefinementList-item" data-parent-category="Mens">
                                                            <div>
                                                                <div class="facet-item checkitem level1"
                                                                    data-category-value="Mens" data-is-refined="false">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens">Mens <span
                                                                        class="facet-count">1264</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt">Half Sleeve
                                                                    T-shirt <span class="facet-count">348</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Blank"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Blank">Blank
                                                                    <span class="facet-count">51</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Cut &amp; Stitch"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Cut &amp; Stitch">Cut
                                                                    &amp; Stitch <span class="facet-count">50</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Cut &amp; Stitch (Designer Edition)"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Cut &amp; Stitch (Designer Edition)">Cut
                                                                    &amp; Stitch (Designer Edition) <span
                                                                        class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Drop Shoulder T-shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Drop Shoulder T-shirt">Drop
                                                                    Shoulder T-shirt <span class="facet-count">26</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Printed"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Printed">Printed
                                                                    <span class="facet-count">145</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Raglan"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Raglan">Raglan
                                                                    <span class="facet-count">14</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Half Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Half Sleeve T-shirt &gt; Sports"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Half Sleeve T-shirt &gt; Sports">Sports
                                                                    <span class="facet-count">57</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt">Full Sleeve
                                                                    T-shirt <span class="facet-count">143</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Blank"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Blank">Blank
                                                                    <span class="facet-count">46</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Cut &amp; Stitch"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Cut &amp; Stitch">Cut
                                                                    &amp; Stitch <span class="facet-count">46</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Cut &amp; Stitch (Designer Edition)"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Cut &amp; Stitch (Designer Edition)">Cut
                                                                    &amp; Stitch (Designer Edition) <span
                                                                        class="facet-count">7</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Printed"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Printed">Printed
                                                                    <span class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Raglan"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Raglan">Raglan
                                                                    <span class="facet-count">23</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Full Sleeve T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Full Sleeve T-shirt &gt; Sports"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Full Sleeve T-shirt &gt; Sports">Sports
                                                                    <span class="facet-count">19</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Maggie">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Maggie"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Maggie">Maggie <span
                                                                        class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Shirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shirt">Shirt <span
                                                                        class="facet-count">72</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Shirt &gt; Casual Shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shirt &gt; Casual Shirt">Casual
                                                                    Shirt <span class="facet-count">49</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Shirt &gt; Formal Shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shirt &gt; Formal Shirt">Formal
                                                                    Shirt <span class="facet-count">23</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Polo T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Polo T-shirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Polo T-shirt">Polo T-shirt <span
                                                                        class="facet-count">147</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Polo T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Polo T-shirt &gt; Classic"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Polo T-shirt &gt; Classic">Classic
                                                                    <span class="facet-count">62</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Polo T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Polo T-shirt &gt; Cut &amp; Stitch"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Polo T-shirt &gt; Cut &amp; Stitch">Cut
                                                                    &amp; Stitch <span class="facet-count">74</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Polo T-shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Polo T-shirt &gt; Printed"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Polo T-shirt &gt; Printed">Printed
                                                                    <span class="facet-count">9</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Hoodie">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Hoodie"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Hoodie">Hoodie <span
                                                                        class="facet-count">30</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Jacket">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Jacket"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Jacket">Jacket <span
                                                                        class="facet-count">30</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Joggers">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Joggers"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Joggers">Joggers <span
                                                                        class="facet-count">17</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Sweatshirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Sweatshirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Sweatshirt">Sweatshirt <span
                                                                        class="facet-count">15</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Comfy Trouser">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Comfy Trouser"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Comfy Trouser">Comfy Trouser <span
                                                                        class="facet-count">48</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Sports Trouser">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Sports Trouser"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Sports Trouser">Sports Trouser
                                                                    <span class="facet-count">22</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Shorts">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Shorts"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shorts">Shorts <span
                                                                        class="facet-count">38</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Shorts">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Shorts &gt; Chino Shorts"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shorts &gt; Chino Shorts">Chino
                                                                    Shorts <span class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Shorts">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Shorts &gt; Sports Shorts"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Shorts &gt; Sports Shorts">Sports
                                                                    Shorts <span class="facet-count">30</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Underwear">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Underwear"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Underwear">Underwear <span
                                                                        class="facet-count">54</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Underwear">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Underwear &gt; Boxer Brief"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Underwear &gt; Boxer Brief">Boxer
                                                                    Brief <span class="facet-count">19</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Underwear">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Underwear &gt; Boxer Shorts"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Underwear &gt; Boxer Shorts">Boxer
                                                                    Shorts <span class="facet-count">10</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Underwear">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Underwear &gt; Trunk"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Underwear &gt; Trunk">Trunk <span
                                                                        class="facet-count">17</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Underwear">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Underwear &gt; Woven Shorts"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Underwear &gt; Woven Shorts">Woven
                                                                    Shorts <span class="facet-count">8</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Socks">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Socks"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Socks">Socks <span
                                                                        class="facet-count">36</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Socks">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Socks &gt; Classic"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Socks &gt; Classic">Classic <span
                                                                        class="facet-count">11</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Socks">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Socks &gt; Sports"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Socks &gt; Sports">Sports <span
                                                                        class="facet-count">7</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Socks">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Socks &gt; Urban"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Socks &gt; Urban">Urban <span
                                                                        class="facet-count">18</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Panjabi">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Panjabi"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Panjabi">Panjabi <span
                                                                        class="facet-count">143</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Tupi">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Tupi"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Tupi">Tupi <span
                                                                        class="facet-count">5</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Jeans">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Jeans"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Jeans">Jeans <span
                                                                        class="facet-count">20</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Pajama">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Pajama"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Pajama">Pajama <span
                                                                        class="facet-count">5</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Accesorries">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Accesorries"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Accesorries">Accesorries <span
                                                                        class="facet-count">48</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Accesorries">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Accesorries &gt; Belt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Accesorries &gt; Belt">Belt <span
                                                                        class="facet-count">11</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Accesorries">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Accesorries &gt; Cap"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Accesorries &gt; Cap">Cap <span
                                                                        class="facet-count">13</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Accesorries">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Mens &gt; Accesorries &gt; Wallet"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Accesorries &gt; Wallet">Wallet
                                                                    <span class="facet-count">24</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Chino Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Chino Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Chino Pants">Chino Pants <span
                                                                        class="facet-count">12</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Cargo Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Cargo Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Cargo Pants">Cargo Pants <span
                                                                        class="facet-count">19</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Formal Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Formal Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Formal Pants">Formal Pants <span
                                                                        class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Mens"
                                                            data-level2-category="Waistcoats">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Mens &gt; Waistcoats"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Mens &gt; Waistcoats">Waistcoats <span
                                                                        class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-parent-category="Womens">
                                                            <div>
                                                                <div class="facet-item checkitem level1"
                                                                    data-category-value="Womens" data-is-refined="false">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens">Womens <span
                                                                        class="facet-count">183</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="T-Shirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; T-Shirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; T-Shirt">T-Shirt <span
                                                                        class="facet-count">26</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Comfy Trouser">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Comfy Trouser"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Comfy Trouser">Comfy Trouser
                                                                    <span class="facet-count">19</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Kurti Tunic And Tops">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Kurti Tunic And Tops"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Kurti Tunic And Tops">Kurti
                                                                    Tunic And Tops <span class="facet-count">64</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Pajamas">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Pajamas"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Pajamas">Pajamas <span
                                                                        class="facet-count">12</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Pants">Pants <span
                                                                        class="facet-count">5</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Palazzo">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Palazzo"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Palazzo">Palazzo <span
                                                                        class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Leggings">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Leggings"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Leggings">Leggings <span
                                                                        class="facet-count">7</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Hoodie">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Hoodie"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Hoodie">Hoodie <span
                                                                        class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Sweatshirt">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Sweatshirt"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Sweatshirt">Sweatshirt <span
                                                                        class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Cargo Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Cargo Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Cargo Pants">Cargo Pants <span
                                                                        class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Shrug">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Shrug"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Shrug">Shrug <span
                                                                        class="facet-count">5</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Co-ords">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Co-ords"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Co-ords">Co-ords <span
                                                                        class="facet-count">10</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Tops">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Tops"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Tops">Tops <span
                                                                        class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Kurti">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Kurti"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Kurti">Kurti <span
                                                                        class="facet-count">10</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="2pc Salwar Kameez">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; 2pc Salwar Kameez"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; 2pc Salwar Kameez">2pc Salwar
                                                                    Kameez <span class="facet-count">8</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="3pc Salwar Kameez">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; 3pc Salwar Kameez"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; 3pc Salwar Kameez">3pc Salwar
                                                                    Kameez <span class="facet-count">13</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Womens"
                                                            data-level2-category="Denim Pants">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Womens &gt; Denim Pants"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Womens &gt; Denim Pants">Denim Pants <span
                                                                        class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-parent-category="Teens">
                                                            <div>
                                                                <div class="facet-item checkitem level1"
                                                                    data-category-value="Teens" data-is-refined="false">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens">Teens <span
                                                                        class="facet-count">72</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Teens"
                                                            data-level2-category="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Teens &gt; Boys"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys">Boys <span
                                                                        class="facet-count">38</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Blank T-shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Blank T-shirt">Blank
                                                                    T-shirt <span class="facet-count">8</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Design T-shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Design T-shirt">Design
                                                                    T-shirt <span class="facet-count">3</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Drop Shoulder"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Drop Shoulder">Drop
                                                                    Shoulder <span class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Full Sleeve T-shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Full Sleeve T-shirt">Full
                                                                    Sleeve T-shirt <span class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Hoodie"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Hoodie">Hoodie <span
                                                                        class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Jacket"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Jacket">Jacket <span
                                                                        class="facet-count">3</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Joggers"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Joggers">Joggers <span
                                                                        class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Panjabi"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Panjabi">Panjabi <span
                                                                        class="facet-count">6</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Sports Trouser"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Sports Trouser">Sports
                                                                    Trouser <span class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Sweatshirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Sweatshirt">Sweatshirt
                                                                    <span class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to-level2="Boys">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Boys &gt; Trouser"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Boys &gt; Trouser">Trouser <span
                                                                        class="facet-count">9</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item" data-belongs-to="Teens"
                                                            data-level2-category="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level2"
                                                                    data-category-value="Teens &gt; Girls"
                                                                    data-is-refined="false" data-click-bound="true">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls">Girls <span
                                                                        class="facet-count">34</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Girls &gt; Comfy Trouser"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls &gt; Comfy Trouser">Comfy
                                                                    Trouser <span class="facet-count">4</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Girls &gt; Full Sleeve T-shirt"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls &gt; Full Sleeve T-shirt">Full
                                                                    Sleeve T-shirt <span class="facet-count">4</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Girls &gt; Hoodie"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls &gt; Hoodie">Hoodie <span
                                                                        class="facet-count">2</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Girls &gt; Kurti"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls &gt; Kurti">Kurti <span
                                                                        class="facet-count">1</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="ais-RefinementList-item"
                                                            data-belongs-to-level2="Girls">
                                                            <div>
                                                                <div class="facet-item checkitem level3"
                                                                    data-category-value="Teens &gt; Girls &gt; Kurti Tunic And Tops"
                                                                    data-is-refined="false" style="display: none;">
                                                                    <input type="checkbox"
                                                                        class="ais-RefinementList-checkbox usual-checkbox"
                                                                        value="Teens &gt; Girls &gt; Kurti Tunic And Tops">Kurti
                                                                    Tunic And Topsundefined<span
                                                                        class="facet-count">20</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- custom cats end -->
                        </div>


                        <div class="sidebar-section sidebar-rating-section rating-section-mobile">
                            <div id="rating" class="facet"></div>
                        </div>


                        <div id="price-mobile" class="facet price-section-mobile"></div>

                        <div id="aside-tagbox"></div>
                    </section>
                </aside>
                <div class="resultset col-sm-12 col-md-8 col-lg-9">
                    @php
                        $shopPageTitle = $selectedCategory?->category_name
                            ?? $selectedCollectionTitle
                            ?? 'Summer Collection';
                    @endphp
                    <div class="shop-listing-toolbar">
                        <button type="button" class="shop-filter-trigger" id="shopFilterOpen">
                            <i class="fa fa-sliders"></i>Filter
                        </button>
                        <h1 class="shop-listing-title">{{ $shopPageTitle }}</h1>
                        <form class="shop-sort-form" method="GET" action="{{ route('shop-new') }}">
                            @foreach(request()->except(['sort', 'page']) as $key => $value)
                                @if(is_scalar($value))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <select class="shop-sort-select" name="sort" aria-label="Sort products"
                                onchange="this.form.submit()">
                                <option value="newest" {{ $selectedSort === 'newest' ? 'selected' : '' }}>Sort</option>
                                <option value="price-low" {{ $selectedSort === 'price-low' ? 'selected' : '' }}>Price: Low</option>
                                <option value="price-high" {{ $selectedSort === 'price-high' ? 'selected' : '' }}>Price: High</option>
                            </select>
                        </form>
                    </div>
                    @if ($selectedCollection)
                        <h2 class="shop-collection-title">{{ $selectedCollectionTitle }}</h2>
                    @else
                        <div id="searchbox"></div>
                    @endif


                    <div class="quick-filters-section">
                        {{-- <div class="quick-filters-bar">
                    @php
                        $categoryIcons = ['fa-male', 'fa-female', 'fa-child', 'fa-user', 'fa-tags'];
                    @endphp
                    @foreach ($categories as $index => $mainCategory)
                        @php
                            $mainCategoryValue = $mainCategory->category_slug ?: $mainCategory->id;
                            $isMainCategoryActive = $activeCategoryPath->contains($mainCategory->id);
                        @endphp
                        <a href="{{ route('shop-new', ['category' => $mainCategoryValue]) }}"
                           class="quick-filter-item server-category-filter {{ $isMainCategoryActive ? 'is-active' : '' }}"
                           data-server-filter="true"
                           data-category="{{ \Illuminate\Support\Str::slug($mainCategory->category_name) }}"
                           data-filter="{{ $mainCategory->category_name }}">
                            <i class="fa {{ $categoryIcons[$index % count($categoryIcons)] }}"></i>
                            {{ $mainCategory->category_name }}
                        </a>
                    @endforeach
                </div> --}}

                        <div class="subcategory-filters-bar" style="display: none;"></div>
                    </div>

                    <div class="main-refinement refinement-area">
                        <div class="refinement-box">
                            <div id="current-refinements"></div>
                            <div id="clear-refinements"></div>
                        </div>
                        <div id="tagbox"></div>
                    </div>
                    <div class="results-wrapper">
                        <section id="results-topbar">
                            <div class="sort-by">
                                <div id="sort-by"></div>
                            </div>

                            <div id="stats" class="text-muted"></div>
                        </section>
                        <main id="hits">






                            <div class="ais-InfiniteHits">
                                <ol class="ais-InfiniteHits-list">
                                    @forelse($products as $product)
                                        @php
                                            $hasDiscount =
                                                $product->discount_price > 0 &&
                                                $product->discount_price < $product->product_value;
                                            $discountPercent = $hasDiscount
                                                ? round(
                                                    (($product->product_value - $product->discount_price) /
                                                        $product->product_value) *
                                                        100,
                                                )
                                                : 0;
                                        @endphp
                                        <li class="ais-InfiniteHits-item">
                                            <div class="product-grid-single">
                                                <div class="product-card" data-has-multiple="0">
                                                    <a class="product-card-link"
                                                        href="{{ route('single-product', $product->slug) }}">
                                                        <div class="product-image-wrap">
                                                            <div class="product-carousel"
                                                                data-images="[{&amp;quot;src&amp;quot;:&amp;quot;{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}&amp;quot;,&amp;quot;type&amp;quot;:&amp;quot;product&amp;quot;}]">
                                                                <div class="carousel-track">
                                                                    <div class="carousel-slide active">
                                                                        <div
                                                                            class="gallerythumbWrapper gallerythumbWrapperLoaded">
                                                                            <img class="gallerythumb gallerythumbLoaded"
                                                                                src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                                                width="100%"
                                                                                alt="{{ $product->name }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="carousel-dots" style="display:none"></div>
                                                            </div>
                                                            <button class="btn-zoom-image"
                                                                data-images="[{&amp;quot;src&amp;quot;:&amp;quot;{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}&amp;quot;,&amp;quot;type&amp;quot;:&amp;quot;product&amp;quot;}]">
                                                                <i class="fa fa-search-plus"></i>
                                                            </button>
                                                            <button class="btn-wishlist" data-wishlist-btn=""
                                                                data-product-id="{{ $product->id }}">
                                                                <i class="fa fa-heart-o"></i>
                                                            </button>
                                                            @if ($hasDiscount)
                                                                <span class="badge-discount"
                                                                    style="display:block">-{{ $discountPercent }}%</span>
                                                            @endif
                                                            @if ($product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0)
                                                                <div class="outofstock-badge" style="display:block">Out
                                                                    of Stock</div>
                                                            @endif
                                                        </div>
                                                        <div class="product-details">
                                                            <h3 class="product-name">{{ $product->name }}</h3>
                                                            <div class="product-info-row">
                                                                <div class="product-info-left">
                                                                    @if ($hasDiscount)
                                                                        <div class="product-rating">
                                                                            <span class="rating-badge">
                                                                                <i class="fa fa-tag"></i> Save
                                                                                {{ $febCurrency->format($product->product_value - $product->discount_price, 0) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                    <div class="product-price-row">
                                                                        <span
                                                                            class="price-current">{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value, 0) }}</span>
                                                                        @if ($hasDiscount)
                                                                            <span class="price-original"
                                                                                style="display:block">{{ $febCurrency->format($product->product_value, 0) }}</span>
                                                                            <span class="price-off"
                                                                                style="display:block">-{{ $discountPercent }}%</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <button type="button" class="btn-add-cart shop-add-cart-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        data-title="{{ $product->name }}"
                                                        data-image="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}"
                                                        data-url="{{ route('single-product', $product->slug) }}"
                                                        data-price="{{ $febCurrency->format($hasDiscount ? $product->discount_price : $product->product_value) }}"
                                                        data-original-price="{{ $hasDiscount ? $febCurrency->format($product->product_value) : '' }}"
                                                        data-stock="{{ max(0, (int) $product->stock_quantity) }}"
                                                        data-colors="{{ $product->productColors->map(fn($color) => ['id' => $color->id, 'name' => $color->name, 'hex_code' => $color->hex_code])->values()->toJson() }}"
                                                        data-sizes="{{ $product->productSizes->map(fn($size) => ['id' => $size->id, 'name' => $size->name])->values()->toJson() }}"
                                                        {{ $product->stock_status === 'Out of Stock' || (int) $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="shop-empty-state">
                                            <span class="shop-empty-icon" aria-hidden="true">
                                                <i class="fa fa-shopping-bag"></i>
                                            </span>
                                            <h2>No products found</h2>
                                            <p>
                                                @if ($selectedCollectionTitle)
                                                    There are currently no products available in {{ $selectedCollectionTitle }}.
                                                @elseif ($selectedCategory)
                                                    There are currently no products available in {{ $selectedCategory->category_name }}.
                                                @else
                                                    We couldn't find any products matching your selection.
                                                @endif
                                            </p>
                                            <a href="{{ route('shop-new') }}">CONTINUE SHOPPING</a>
                                        </li>
                                    @endforelse
                                </ol>
                                <button class="ais-InfiniteHits-loadMore" style="display:none">Show more
                                    results</button>
                            </div>
                        </main>
                        <section id="pagination">
                            <div class="basic-pagination text-center">
                                <nav>
                                    @include('components.front_pagination', ['paginator' => $products])
                                </nav>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="shop-filter-overlay" id="shopFilterOverlay"></div>

            <div class="modal fade pricemodal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title pricemodal-header">
                                <p style="text-align:center">Select Options</p>
                            </h5>
                        </div>
                        <div class="pricemodal-content">
                            <div class="card-view">
                                <div class="card-photo-container">
                                    <img class="card-photo" src="" alt="Product Image" />
                                </div>
                            </div>
                            <div class="shop-modal-details">
                                <div class="card-title"></div>
                                <div class="shop-modal-price"><strong></strong><del></del></div>
                                <button type="button" class="size-guide-toggle no-data" onclick="toggleSizeGuide()">Size Guide</button>
                                <div class="modal-size-chart empty"></div>
                                <div class="modal-body pricemodal-body" id="shopCartOptions"></div>
                                <div class="modal-footer pricemodal-footer">
                                    <button type="button" class="btn btn-success" id="shopModalAddToCart">
                                        <i class="fa fa-spinner fa-spin" style="display:none"></i> ADD TO CART
                                    </button>
                                </div>
                                <a class="shop-modal-view-details" href="#">View details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade szc" style="z-index: 1200;">
                <div class="modal-dialog commonmodal-dialog" role="document">
                    <div class="modal-content" style="border: 0px;">
                        <div class="modal-header" style="background: #2bb673; color: white;">
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
                                <tbody class="szc-tbody">

                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer szc-footer" style="display: inline-table; width: 100%;">

                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="modal fade ck-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="ck-modal-header" style="background: #2bb673; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" style="text-align : center;">
                            My Shipping address is
                        </h4>
                    </div>
                    <div class="modal-body ck-modal-body">
                        <div class="row">

                            <div class="offset-sm-1 col-sm-5">
                                <a href="#" class="btn btn-info btn-block">Inside Bangladesh</a><br />
                            </div>
                            <div class="col-sm-5">
                                <a href="#" class="btn btn-block btn-danger">Outside Bangladesh</a><br />
                            </div>
                        </div>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@3.2.0"
        integrity="sha256-/8usMtTwZ01jujD7KAZctG0UMk2S2NDNirGFVBbBZCM=" crossorigin="anonymous"></script>
    @unless ($selectedCollection)
        <script src="{{ asset('feb/js/tees.js?var=category-db-20260713') }}" charset="utf-8"></script>
    @endunless
    <script src="{{ asset('feb/js/product-carousel.js?v=98971') }}" charset="utf-8"></script>
    <script src="{{ asset('feb/js/product-lightbox.js?v=26045') }}" charset="utf-8"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var currentProduct = null;
            var currentCardButton = null;
            var selectedColorId = null;
            var selectedSizeId = null;
            var modalElement = document.querySelector('.pricemodal');
            var modalBody = document.getElementById('shopCartOptions');
            var modalAddButton = document.getElementById('shopModalAddToCart');

            function parseOptions(value) {
                try {
                    return JSON.parse(value || '[]');
                } catch (error) {
                    return [];
                }
            }

            function escapeHtml(value) {
                var element = document.createElement('div');
                element.textContent = value == null ? '' : String(value);
                return element.innerHTML;
            }

            function showShopCartToast(message, type) {
                document.querySelectorAll('.shop-cart-toast').forEach(function(item) {
                    item.remove();
                });
                var toast = document.createElement('div');
                toast.className = 'shop-cart-toast' + (type === 'error' ? ' error' : '');
                toast.setAttribute('role', 'status');
                toast.innerHTML = '<i class="fa ' + (type === 'error' ? 'fa-exclamation-circle' :
                    'fa-check-circle') + '"></i><span></span>';
                toast.querySelector('span').textContent = message;
                document.body.appendChild(toast);
                window.setTimeout(function() {
                    toast.remove();
                }, 3200);
            }

            function updateShopCartBadges(count) {
                count = parseInt(count, 10) || 0;
                document.querySelectorAll('#cartBadge, .shopping-cart-badge').forEach(function(badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            }

            function setButtonLoading(button, loading) {
                if (!button) return;
                button.disabled = loading;
                var icon = button.querySelector('i');
                if (icon) icon.className = loading ? 'fa fa-spinner fa-spin' : 'fa fa-shopping-cart';
            }

            function addProductToCart(product, quantity, colorId, sizeId, sourceButton, fromModal) {
                if (typeof window.axios === 'undefined') {
                    showShopCartToast('Unable to connect. Please refresh the page.', 'error');
                    return;
                }

                setButtonLoading(sourceButton, true);
                if (fromModal) {
                    modalAddButton.disabled = true;
                    modalAddButton.querySelector('i').style.display = 'inline-block';
                }

                window.axios.post('{{ route('ajax-add-to-cart') }}', {
                    product_id: product.id,
                    quantity: quantity,
                    color_id: colorId,
                    size_id: sizeId
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                }).then(function(response) {
                    var data = response.data || {};
                    updateShopCartBadges(data.cart_count);
                    showShopCartToast(data.message || 'Product added to cart.', 'success');
                    if (fromModal && window.jQuery) window.jQuery(modalElement).modal('hide');
                }).catch(function(error) {
                    var data = error.response && error.response.data ? error.response.data : {};
                    var message = data.message || 'Could not add this product to cart.';
                    if (data.errors) {
                        var firstKey = Object.keys(data.errors)[0];
                        if (firstKey && data.errors[firstKey][0]) message = data.errors[firstKey][0];
                    }
                    showShopCartToast(message, 'error');
                }).then(function() {
                    setButtonLoading(sourceButton, false);
                    if (fromModal) {
                        modalAddButton.disabled = false;
                        modalAddButton.querySelector('i').style.display = 'none';
                    }
                });
            }

            function renderProductOptions(product) {
                selectedColorId = null;
                selectedSizeId = null;
                var html = '';

                if (product.colors.length) {
                    html +=
                        '<div class="shop-option-group"><span class="shop-option-label">Select Color</span><div class="shop-option-list">';
                    product.colors.forEach(function(color) {
                        html += '<button type="button" class="shop-option-button" data-shop-color="' + color
                            .id + '">' +
                            '<span class="shop-color-dot" style="background:' + escapeHtml(color.hex_code ||
                                '#f5f5f5') + '"></span>' +
                            escapeHtml(color.name) + '</button>';
                    });
                    html += '</div></div>';
                }

                if (product.sizes.length) {
                    html +=
                        '<div class="shop-option-group"><span class="shop-option-label">Select Size</span><div class="shop-option-list">';
                    product.sizes.forEach(function(size) {
                        html += '<button type="button" class="shop-option-button" data-shop-size="' + size
                            .id + '">' + escapeHtml(size.name) + '</button>';
                    });
                    html += '</div></div>';
                }

                html +=
                    '<div class="shop-option-group"><label class="shop-option-label" for="shopModalQuantity">Quantity</label>' +
                    '<input class="shop-modal-quantity" id="shopModalQuantity" type="number" min="1" max="' +
                    product.stock + '" value="1"></div>';
                modalBody.innerHTML = html;

                document.querySelector('.pricemodal .card-photo').src = product.image;
                document.querySelector('.pricemodal .card-title').textContent = product.title;
                document.querySelector('.pricemodal .shop-modal-price strong').textContent = product.price || '';
                var oldPrice = document.querySelector('.pricemodal .shop-modal-price del');
                oldPrice.textContent = product.originalPrice || '';
                oldPrice.style.display = product.originalPrice ? '' : 'none';
                document.querySelector('.pricemodal .shop-modal-view-details').href = product.url || '#';

                modalBody.querySelectorAll('[data-shop-color]').forEach(function(button) {
                    button.addEventListener('click', function() {
                        modalBody.querySelectorAll('[data-shop-color]').forEach(function(item) {
                            item.classList.remove('is-selected');
                        });
                        button.classList.add('is-selected');
                        selectedColorId = button.getAttribute('data-shop-color');
                    });
                });

                modalBody.querySelectorAll('[data-shop-size]').forEach(function(button) {
                    button.addEventListener('click', function() {
                        modalBody.querySelectorAll('[data-shop-size]').forEach(function(item) {
                            item.classList.remove('is-selected');
                        });
                        button.classList.add('is-selected');
                        selectedSizeId = button.getAttribute('data-shop-size');
                    });
                });
            }

            document.querySelectorAll('.shop-add-cart-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var product = {
                        id: parseInt(button.getAttribute('data-product-id'), 10),
                        title: button.getAttribute('data-title'),
                        image: button.getAttribute('data-image'),
                        url: button.getAttribute('data-url'),
                        price: button.getAttribute('data-price'),
                        originalPrice: button.getAttribute('data-original-price'),
                        stock: parseInt(button.getAttribute('data-stock'), 10) || 0,
                        colors: parseOptions(button.getAttribute('data-colors')),
                        sizes: parseOptions(button.getAttribute('data-sizes'))
                    };

                    if (!product.colors.length && !product.sizes.length) {
                        addProductToCart(product, 1, null, null, button, false);
                        return;
                    }

                    currentProduct = product;
                    currentCardButton = button;
                    renderProductOptions(product);
                    if (window.jQuery) window.jQuery(modalElement).modal('show');
                });
            });

            modalAddButton.addEventListener('click', function() {
                if (!currentProduct) return;
                if (currentProduct.colors.length && !selectedColorId) {
                    showShopCartToast('Please select a color.', 'error');
                    return;
                }
                if (currentProduct.sizes.length && !selectedSizeId) {
                    showShopCartToast('Please select a size.', 'error');
                    return;
                }

                var quantityInput = document.getElementById('shopModalQuantity');
                var quantity = Math.max(1, parseInt(quantityInput.value, 10) || 1);
                addProductToCart(currentProduct, quantity, selectedColorId, selectedSizeId,
                    currentCardButton, true);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sidebar = document.querySelector('.feb-shop-page .refineset');
            var openButton = document.getElementById('shopFilterOpen');
            var overlay = document.getElementById('shopFilterOverlay');
            var closeButton = sidebar ? sidebar.querySelector('.algo-close-filter') : null;

            function setFilterOpen(open) {
                if (!sidebar || !overlay) return;
                sidebar.classList.toggle('is-open', open);
                overlay.classList.toggle('is-open', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }

            if (openButton) openButton.addEventListener('click', function () { setFilterOpen(true); });
            if (overlay) overlay.addEventListener('click', function () { setFilterOpen(false); });
            if (closeButton) {
                closeButton.removeAttribute('onclick');
                closeButton.addEventListener('click', function () { setFilterOpen(false); });
            }
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') setFilterOpen(false);
            });
        });
    </script>
@endsection
