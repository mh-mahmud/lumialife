 @php
     $topbarSettings = $febSettings ?? \App\Models\Settings::first();
     $topbarPhones = collect([
         $topbarSettings?->office_phone_number,
         $topbarSettings?->phone_number_2,
         $topbarSettings?->phone_number_3,
     ])->filter()->unique()->values();
     $topbarPhone = $topbarPhones->first();
     $topbarPhoneHref = fn ($phone) => preg_replace('/[^0-9+]/', '', (string) $phone);
     $topbarPromoText = 'Shop more, save more — enjoy free delivery nationwide on qualifying orders.';
 @endphp

 <div class="site-header-stack site-header-stack--mobile">
     <div class="site-topbar site-topbar--mobile">
         <div class="site-topbar-marquee">
             <div class="site-topbar-marquee-track">
                 <span>{{ $topbarPromoText }}</span>
                 <span aria-hidden="true">{{ $topbarPromoText }}</span>
             </div>
         </div>
     </div>

     <header class="mobile-top-nav">
         <div class="top-nav-left">
             <button class="top-nav-btn search-btn" id="openSearchModal" aria-label="Search">
                 <i class="fa fa-search"></i>
             </button>
         </div>

         <div class="top-nav-center">
             <a href="{{ route('home') }}" class="top-nav-logo">
                 <img src="{{ $febLogoUrl }}" alt="Site Logo" />
             </a>
         </div>

         <div class="top-nav-right">
             <span class="mobile-currency-flag" aria-hidden="true">{{ $febCurrency->code() === 'BDT' ? '🇧🇩' : '🇲🇾' }}</span>
             <select class="store-currency-select mobile-currency-select" aria-label="Choose currency">
                 <option value="BDT" {{ $febCurrency->code() === 'BDT' ? 'selected' : '' }}>BDT</option>
                 <option value="MYR" {{ $febCurrency->code() === 'MYR' ? 'selected' : '' }}>MYR</option>
             </select>
             <button class="top-nav-btn menu-btn" id="openMobileMenu" aria-label="Open menu">
                 <i class="fa fa-bars"></i>
             </button>
         </div>
     </header>
 </div>

 <div class="mobile-search-modal" id="searchModal">
     <div class="search-modal-header">
         <button class="search-modal-back" id="closeSearchModal">
             <i class="fa fa-arrow-left"></i>
         </button>
         <div class="search-input-wrapper">
             <i class="fa fa-search search-icon"></i>
             <input type="search" id="mobileSearchInput" placeholder="Search products..." autocomplete="off"
                 autofocus />
             <button class="search-clear-btn" id="clearSearch" style="display: none">
                 <i class="fa fa-times"></i>
             </button>
         </div>
     </div>
     <div class="search-suggestions" id="searchSuggestions">
         <div class="search-section recent-searches" id="recentSearches">
             <div class="search-section-header">
                 <span>Recent Searches</span>
                 <button class="clear-recent" id="clearRecentSearches">Clear</button>
             </div>
             <div class="search-section-items" id="recentSearchItems"></div>
         </div>

         <div class="search-section popular-searches">
             <div class="search-section-header">
                 <span>Popular Searches</span>
             </div>
             <div class="search-section-items">
                 <a href="#" class="search-tag">T-Shirt</a>
                 <a href="#" class="search-tag">Polo</a>
                 <a href="#" class="search-tag">Hoodie</a>
                 <a href="#" class="search-tag">Joggers</a>
                 <a href="#" class="search-tag">Kurti</a>
                 <a href="#" class="search-tag">Kids</a>
             </div>
         </div>

         <div class="search-results" id="liveSearchResults" style="display: none"></div>
     </div>
 </div>

 <div class="mobile-side-menu" id="sideMenu">
     <div class="side-menu-header">
         <div class="guest-greeting">
             <span class="welcome-text">{{ Auth::check() ? 'Hello, ' . Auth::user()->first_name : 'Welcome to Fabrilife' }}</span>
             <div class="auth-buttons">
                 @auth
                     <a href="{{ route('wishlist') }}" class="btn-login">Wishlist</a>
                     <a href="{{ route('customer-logout') }}" class="btn-register">Logout</a>
                 @else
                     <a href="{{ route('theme-login') }}" class="btn-login">Login</a>
                     <a href="{{ route('theme-register') }}" class="btn-register">Register</a>
                 @endauth
             </div>
         </div>
         <button class="side-menu-close" id="closeSideMenu">
             <i class="fa fa-times"></i>
         </button>
     </div>

     <div class="side-menu-content">
         <div class="side-menu-section">
             <a href="{{ route('home') }}" class="side-menu-item">
                 <i class="fa fa-home"></i>
                 <span>Home</span>
             </a>
             <a href="{{ route('shop-new') }}" class="side-menu-item">
                 <i class="fa fa-th-large"></i>
                 <span>Shop All</span>
             </a>
             <a href="#" class="side-menu-item highlight">
                 <i class="fa fa-star"></i>
                 <span>New Arrivals</span>
             </a>
             <a href="#"
                 class="side-menu-item highlight free-delivery">
                 <i class="fa fa-truck"></i>
                 <span>Free Delivery</span>
             </a>
             <a href="{{ route('shop-new') }}" class="side-menu-item">
                 <i class="fa fa-fire"></i>
                 <span>Top Selling</span>
             </a>
         </div>

         <div class="side-menu-divider"></div>

         <div class="side-menu-section">
             <div class="side-menu-section-title">Categories</div>
             @foreach($febMenuCategories as $menuCategory)
                 <a href="{{ route('shop-new', ['category' => $menuCategory->category_slug ?: $menuCategory->id]) }}"
                     class="side-menu-item">
                     <i class="fa fa-tags"></i>
                     <span>{{ $menuCategory->category_name }}</span>
                     <i class="fa fa-chevron-right chevron"></i>
                 </a>
             @endforeach
         </div>

         <div class="side-menu-divider"></div>

         <div class="side-menu-section">
             <div class="side-menu-section-title">My Account</div>
             <a href="{{ route('wishlist') }}" class="side-menu-item">
                 <i class="fa fa-heart"></i>
                 <span>My Wishlist</span>
                 <span class="side-menu-badge wishlist-count">0</span>
             </a>
         </div>

         <div class="side-menu-divider"></div>

         <div class="side-menu-section">
             <a href="{{ route('order-tracking') }}" class="side-menu-item">
                 <i class="fa fa-truck"></i>
                 <span>Track Order</span>
             </a>
             <a href="#" class="side-menu-item">
                 <i class="fa fa-phone"></i>
                 <span>Contact Us</span>
             </a>
             <a href="{{ route('outlets') }}" class="side-menu-item">
                 <i class="fa fa-map-marker"></i>
                 <span>Store Locations</span>
             </a>
         </div>
     </div>
 </div>
 <div class="side-menu-overlay" id="sideMenuOverlay"></div>
 <nav class="mobile-bottom-nav" id="mobileBottomNav">
     <a href="#" class="bottom-nav-item active" data-nav="home">
         <div class="nav-icon">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                 <polyline points="9 22 9 12 15 12 15 22"></polyline>
             </svg>
         </div>
         <span class="nav-label">Home</span>
     </a>

     <button class="bottom-nav-item" id="openCategoryMenu" data-nav="category">
         <div class="nav-icon">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <rect x="3" y="3" width="7" height="7"></rect>
                 <rect x="14" y="3" width="7" height="7"></rect>
                 <rect x="14" y="14" width="7" height="7"></rect>
                 <rect x="3" y="14" width="7" height="7"></rect>
             </svg>
         </div>
         <span class="nav-label">Category</span>
     </button>

     <a href="{{ route('theme-carts') }}" class="bottom-nav-item cart-nav-item js-side-cart-open" data-nav="cart">
         <div class="nav-icon cart-icon-wrapper">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <circle cx="9" cy="21" r="1"></circle>
                 <circle cx="20" cy="21" r="1"></circle>
                 <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
             </svg>
             <span class="cart-badge" id="cartBadge" style="display: {{ $febCartCount > 0 ? 'flex' : 'none' }}">
                 {{ $febCartCount > 99 ? '99+' : $febCartCount }}
             </span>
         </div>
         <span class="nav-label">Cart</span>
     </a>

     <button class="bottom-nav-item chat-nav-item" id="openMobileChat" data-nav="chat">
         <div class="nav-icon">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
             </svg>
         </div>
         <span class="nav-label">Chat</span>
     </button>

     <a href="#" class="bottom-nav-item" data-nav="account">
         <div class="nav-icon">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                 <circle cx="12" cy="7" r="4"></circle>
             </svg>
         </div>
         <span class="nav-label">Login</span>
     </a>
 </nav>

 <style>
     .desktop-currency-picker {
         position: relative;
         display: inline-block;
         align-self: center;
         width: auto;
         min-width: 0;
         margin-right: 4px;
     }
     .desktop-currency-trigger {
         display: flex;
         min-width: 112px;
         height: 42px;
         align-items: center;
         justify-content: space-between;
         gap: 8px;
         border: 1px solid #e2e5e9;
         border-radius: 7px;
         background: #fff;
         padding: 5px 9px;
         color: #222f3f;
         cursor: pointer;
         transition: border-color .18s ease, box-shadow .18s ease;
     }
     .desktop-currency-trigger:hover,
     .desktop-currency-picker.is-open .desktop-currency-trigger {
         border-color: #222f3f;
         box-shadow: 0 3px 12px rgba(34, 47, 63, .10);
     }
     .desktop-currency-trigger-copy { display: flex; min-width: 0; flex-direction: column; text-align: left; line-height: 1.15; }
     .desktop-currency-country {
         color: #4b5563;
         font-size: 9px;
         font-weight: 500;
         white-space: nowrap;
     }
     .desktop-currency-current { margin-top: 2px; font-size: 11px; font-weight: 800; white-space: nowrap; }
     .desktop-currency-trigger .fa { font-size: 10px; transition: transform .18s ease; }
     .desktop-currency-picker.is-open .desktop-currency-trigger .fa { transform: rotate(180deg); }
     .desktop-currency-menu {
         position: absolute;
         top: calc(100% + 7px);
         right: 0;
         z-index: 10050;
         display: none;
         width: 178px;
         overflow: hidden;
         border: 1px solid #e5e7eb;
         border-radius: 8px;
         background: #fff;
         padding: 5px;
         box-shadow: 0 12px 30px rgba(15, 23, 42, .16);
     }
     .desktop-currency-picker.is-open .desktop-currency-menu { display: block; }
     .desktop-currency-option {
         display: flex;
         width: 100%;
         align-items: center;
         gap: 9px;
         border: 0;
         border-radius: 6px;
         background: transparent;
         padding: 8px;
         color: #222f3f;
         text-align: left;
         cursor: pointer;
     }
     .desktop-currency-option:hover { background: #f3f5f7; }
     .desktop-currency-option.is-selected { background: #edf5ff; color: #0969da; }
     .desktop-currency-flag { font-size: 17px; line-height: 1; }
     .desktop-currency-option-copy { display: flex; flex: 1; flex-direction: column; line-height: 1.2; }
     .desktop-currency-option-copy strong { font-size: 11px; }
     .desktop-currency-option-copy small { margin-top: 2px; color: #6b7280; font-size: 9px; }
     .desktop-currency-check { font-size: 10px; opacity: 0; }
     .desktop-currency-option.is-selected .desktop-currency-check { opacity: 1; }
     .mobile-currency-select {
         border: 0;
         outline: 0;
         background: transparent;
         color: inherit;
         font-size: 12px;
         font-weight: 700;
         cursor: pointer;
     }
     .mobile-currency-select {
         max-width: 58px;
         padding: 4px 1px;
         color: #222f3f;
     }
     @media (min-width: 992px) and (max-width: 1199px) {
         .desktop-header-myntra .nav-category-link { padding-inline: 9px; }
     }

     /* Sticky topbar + header stack */
     .site-header-stack {
         position: sticky;
         top: 0;
         z-index: 1000;
         background: #fff;
     }
     .site-header-stack .mobile-top-nav,
     .site-header-stack .desktop-header-myntra {
         position: static !important;
         top: auto !important;
     }
     .site-header-stack--mobile { display: block; }
     .site-header-stack--desktop { display: none; }
     @media (min-width: 768px) {
         .site-header-stack--mobile { display: none; }
         .site-header-stack--desktop { display: block; }
         .site-header-stack .desktop-header-myntra {
             height: 126px;
             overflow: visible;
             background: #fff;
         }
     }

     /* Top utility/promo bar */
     .site-topbar {
         background: #111318;
         color: #fff;
         overflow: hidden;
     }
     .site-topbar-marquee {
         flex: 1;
         min-width: 0;
         overflow: hidden;
     }
     .site-topbar-marquee-track {
         display: inline-flex;
         white-space: nowrap;
         animation: site-topbar-scroll 18s linear infinite;
     }
     .site-topbar-marquee-track span {
         padding-right: 60px;
     }
     @keyframes site-topbar-scroll {
         from { transform: translateX(0); }
         to { transform: translateX(-50%); }
     }
     @media (prefers-reduced-motion: reduce) {
         .site-topbar-marquee-track { animation: none; }
     }

     .site-topbar--mobile {
         height: 28px;
         display: flex;
         align-items: center;
         padding: 0 12px;
     }
     .site-topbar--mobile .site-topbar-marquee-track span {
         font-size: 11px;
         font-weight: 600;
         letter-spacing: 0.2px;
         opacity: 0.9;
     }

     .site-topbar--desktop {
         height: var(--topbar-height-desktop, 36px);
     }
     .site-topbar-inner {
         max-width: 1350px;
         margin: 0 auto;
         height: 100%;
         display: flex;
         align-items: center;
         gap: 24px;
         padding: 0 24px;
         font-family: var(--header-font, 'Assistant', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
         font-size: 12px;
     }
     .site-topbar-links {
         display: flex;
         align-items: center;
         gap: 20px;
         flex-shrink: 0;
     }
     .site-topbar-links a {
         color: #fff;
         text-decoration: none;
         font-weight: 600;
         letter-spacing: 0.3px;
         text-transform: uppercase;
         opacity: 0.85;
     }
     .site-topbar-links a:hover { opacity: 1; text-decoration: underline; }
     .site-topbar--desktop .site-topbar-marquee-track span { font-weight: 500; opacity: 0.9; }
     .site-topbar-contact { flex-shrink: 0; }
     .site-topbar-contact a {
         display: inline-flex;
         align-items: center;
         gap: 6px;
         color: #fff;
         text-decoration: none;
         font-weight: 700;
     }
     .site-topbar-contact a:hover { opacity: 0.85; }

     /* Split desktop header into a main row (logo/search/actions) and a category row */
     .desktop-header-row--main { height: 64px; }
     .desktop-header-row--main .desktop-header-inner { height: 100%; }
     .desktop-header-row--categories {
         height: 44px;
         box-shadow: inset 0 1px 0 var(--header-border, #f5f5f6);
     }
     .desktop-header-inner--categories { height: 100%; }
     @media (max-width: 1199px) {
         .site-topbar-links { gap: 14px; }
     }

     /* Reference header layout */
     .mobile-top-nav {
         height: 68px;
         padding: 0 14px;
         border-bottom: 1px solid #ececec;
         background: #fff;
         box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
     }
     .mobile-top-nav .top-nav-left,
     .mobile-top-nav .top-nav-right { flex: 1; }
     .mobile-top-nav .top-nav-right {
         justify-content: flex-end;
         gap: 3px;
     }
     .mobile-top-nav .top-nav-btn {
         width: 38px;
         height: 38px;
         border: 1px solid #dedede;
         border-radius: 6px;
         background: #fff;
         color: #111;
     }
     .mobile-top-nav .top-nav-logo img {
         width: auto;
         max-width: 132px;
         height: 30px;
         object-fit: contain;
     }
     .mobile-currency-flag { font-size: 18px; line-height: 1; }
     .mobile-top-nav .mobile-currency-select {
         width: 17px;
         padding: 0;
         color: transparent;
         font-size: 0;
     }

     @media (min-width: 768px) {
         .desktop-header-myntra .desktop-header-inner {
             width: 100%;
             max-width: 1400px;
             margin-right: auto;
             margin-left: auto;
             padding-inline: clamp(24px, 4.3vw, 80px);
         }
         .desktop-header-row--main { height: 88px; }
         .desktop-header-row--main .desktop-header-inner {
             display: grid;
             grid-template-columns: minmax(270px, 1fr) auto minmax(270px, 1fr);
             gap: 28px;
         }
         .desktop-header-myntra .header-search {
             grid-column: 1;
             grid-row: 1;
             width: min(100%, 270px);
             margin: 0;
             justify-self: start;
         }
         .desktop-header-myntra .header-search-form {
             height: 40px;
             overflow: hidden;
             border: 1px solid #bfc1c4;
             border-radius: 22px;
             background: #fff;
         }
         .desktop-header-myntra .header-search-input {
             height: 40px;
             border: 0;
             background: transparent;
             padding-left: 20px;
         }
         .desktop-header-myntra .header-search-icon { display: none; }
         .desktop-header-myntra .header-search-btn {
             display: flex;
             width: 45px;
             align-items: center;
             justify-content: center;
             border: 0;
             background: transparent;
             color: #9da3aa;
         }
         .desktop-header-myntra .header-logo {
             grid-column: 2;
             grid-row: 1;
             margin: 0;
             justify-self: center;
         }
         .desktop-header-myntra .header-logo img {
             width: auto;
             max-width: 270px;
             height: 52px;
             object-fit: contain;
         }
         .desktop-header-myntra .header-actions {
             grid-column: 3;
             grid-row: 1;
             justify-self: end;
             gap: 13px;
         }
         .desktop-header-myntra .header-action-label { display: none; }
         .desktop-header-myntra .header-action-item[href*="outlets"] { display: none; }
         .desktop-header-myntra .header-action-icon {
             width: 25px;
             height: 25px;
         }
         .desktop-header-myntra .desktop-currency-trigger {
             min-width: 86px;
             border: 0;
             box-shadow: none;
         }
         .desktop-header-myntra .desktop-currency-country { display: none; }
         .desktop-currency-current-flag {
             margin-right: 4px;
             font-size: 16px;
             vertical-align: middle;
         }
         .desktop-header-row--categories { height: 40px; }
         .desktop-header-inner--categories { justify-content: center; }
         .desktop-header-myntra .nav-category-list {
             width: 100%;
             justify-content: center;
             gap: 34px;
         }
         .desktop-header-myntra .nav-category-link {
             padding: 10px 5px;
             border: 0;
             color: #707070;
             font-family: Arial, Helvetica, sans-serif;
             font-size: 14px;
             font-weight: 700;
             letter-spacing: 0;
             line-height: 18px;
             text-transform: uppercase;
             white-space: nowrap;
         }
         .desktop-header-myntra .nav-category-item:hover .nav-category-link,
         .desktop-header-myntra .nav-category-link:hover {
             border: 0;
             color: #111;
         }
         .desktop-header-myntra .nav-category-item:first-child .nav-category-link {
             color: #087a12;
         }
         .desktop-header-myntra .nav-category-caret {
             margin-left: 7px;
             color: currentColor;
             font-size: 9px;
             transition: transform .16s ease;
         }
         .desktop-header-myntra .nav-category-item:hover .nav-category-caret {
             transform: rotate(180deg);
         }

         /* Compact category dropdown from the reference */
         .desktop-header-myntra .nav-category-item::before,
         .desktop-header-myntra .nav-category-item::after {
             display: none;
         }
         .desktop-header-myntra .nav-category-item .mega-menu {
             position: absolute;
             top: 100%;
             left: 0;
             width: 230px;
             max-height: none;
             overflow: visible;
             transform: translateY(8px);
             border: 1px solid #eeeeee;
             border-radius: 7px;
             background: rgba(255, 255, 255, .98);
             box-shadow: 0 5px 14px rgba(0, 0, 0, .13);
             opacity: 0;
             visibility: hidden;
             transition: opacity .15s ease, transform .15s ease, visibility .15s ease;
         }
         .desktop-header-myntra .nav-category-item:hover .mega-menu {
             transform: translateY(0);
             opacity: 1;
             visibility: visible;
         }
         .desktop-header-myntra .mega-menu-inner {
             display: block;
             padding: 7px 0;
         }
         .desktop-header-myntra .mega-menu-categories,
         .desktop-header-myntra .mega-menu-column {
             display: block;
             min-width: 0;
             max-width: none;
         }
         .desktop-header-myntra .mega-menu-heading,
         .desktop-header-myntra .mega-menu-products,
         .desktop-header-myntra .mega-menu-view-all {
             display: none;
         }
         .desktop-header-myntra .mega-menu-links {
             margin: 0;
             padding: 0;
         }
         .desktop-header-myntra .mega-menu-links li {
             margin: 0;
         }
         .desktop-header-myntra .mega-menu-links a {
             display: block;
             width: 100%;
             padding: 14px 20px;
             color: #707070;
             font-family: Arial, Helvetica, sans-serif;
             font-size: 14px;
             font-weight: 400;
             line-height: 18px;
             text-transform: uppercase;
             white-space: nowrap;
         }
         .desktop-header-myntra .mega-menu-links a::after {
             display: none;
         }
         .desktop-header-myntra .mega-menu-links a:hover {
             background: #f7f7f7;
             color: #111;
             font-weight: 400;
             text-decoration: none;
         }
         .desktop-header-row--categories {
             height: 38px;
             position: relative;
             z-index: 2;
             border-top: 0;
             background: #fff;
             box-shadow: none;
         }
         .desktop-header-inner--categories,
         .desktop-header-myntra .header-nav-main,
         .desktop-header-myntra .nav-category-list,
         .desktop-header-myntra .nav-category-item {
             height: 100%;
         }
         .desktop-header-myntra .header-action-icon,
         .desktop-header-myntra .header-action-icon svg {
             color: #172033;
             stroke: #172033;
         }
         .desktop-header-myntra .header-search-input {
             color: #172033;
             font-family: Arial, Helvetica, sans-serif;
             font-size: 12px;
         }
         .desktop-header-myntra .header-search-input::placeholder {
             color: #bcc2cb;
             opacity: 1;
         }
         .desktop-header-myntra .header-search-btn {
             color: #8b98aa;
         }
     }

     @media (min-width: 768px) and (max-width: 1050px) {
         .site-topbar-links { display: none; }
         .desktop-header-row--main .desktop-header-inner {
             grid-template-columns: minmax(210px, 1fr) auto minmax(210px, 1fr);
             gap: 16px;
         }
         .desktop-header-myntra .header-logo img { max-width: 210px; }
         .desktop-header-myntra .header-actions { gap: 7px; }
         .desktop-header-myntra .nav-category-list {
             justify-content: flex-start;
             overflow-x: auto;
             scrollbar-width: none;
         }
     }
 </style>

 <script>
 document.addEventListener('DOMContentLoaded', function () {
     const locationBlock = document.querySelector('[data-delivery-location]');
     if (!locationBlock || locationBlock.dataset.countryHeader === '1') return;

     let isBangladesh = false;
     try {
         const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
         const languages = (navigator.languages || [navigator.language || '']).join(',').toLowerCase();
         isBangladesh = timezone === 'Asia/Dhaka' || languages.includes('bn-bd');
     } catch (error) {
         isBangladesh = false;
     }

     const country = locationBlock.querySelector('[data-delivery-country]');
     if (country) country.textContent = isBangladesh ? 'Bangladesh' : 'Malaysia';
 });
 </script>
 <div class="mobile-mega-menu" id="megaMenu">
     <div class="mega-menu-backdrop" id="megaMenuBackdrop"></div>

     <div class="mega-menu-container">
         <div class="mega-menu-handle">
             <div class="handle-bar"></div>
         </div>

         <div class="mega-menu-header">
             <h3 class="mega-menu-title" id="megaMenuTitle">Shop by Category</h3>
             <button class="mega-menu-close" id="closeMegaMenu">
                 <i class="fa fa-times"></i>
             </button>
         </div>

         <div class="mega-menu-content">
             <div class="mega-menu-view" id="mainCategoriesView">
                 <div class="mega-menu-section quick-links">
                     <a href="#"
                         class="quick-link-card new-arrival">
                         <i class="fa fa-star"></i>
                         <span>New Arrivals</span>
                     </a>
                     <a href="#"
                         class="quick-link-card top-selling">
                         <i class="fa fa-fire"></i>
                         <span>Top Selling</span>
                     </a>

                     <a href="#"
                         class="quick-link-card free-delivery">
                         <i class="fa fa-truck"></i>
                         <span>Free Delivery</span>
                     </a>
                 </div>

                 <div class="mega-menu-section category-list">
                     <div class="section-title">All Categories</div>

                     @foreach($febMenuCategories as $menuCategory)
                         @php
                             $mobileMenuCategoryValue = $menuCategory->category_slug ?: $menuCategory->id;
                         @endphp
                         <a href="{{ route('shop-new', ['category' => $mobileMenuCategoryValue]) }}"
                             class="category-item main-category" style="text-decoration: none;">
                             <div class="category-icon all">
                                 <i class="fa fa-tags"></i>
                             </div>
                             <div class="category-info">
                                 <span class="category-name">{{ $menuCategory->category_name }}</span>
                                 <span class="category-count">
                                     {{ $menuCategory->children->isNotEmpty() ? $menuCategory->children->count() . ' subcategories' : 'View products' }}
                                 </span>
                             </div>
                             <i class="fa fa-chevron-right"></i>
                         </a>
                     @endforeach
                 </div>
             </div>
         </div>
     </div>
 </div>

 <div class="site-header-stack site-header-stack--desktop">
     <div class="site-topbar site-topbar--desktop">
         <div class="site-topbar-inner">
             <div class="site-topbar-links">
                 <a href="{{ route('about-us') }}">About Us</a>
                 <a href="{{ route('outlets') }}">Store Locator</a>
                 <a href="{{ route('contact-us') }}">Contact Us</a>
             </div>
             <div class="site-topbar-marquee">
                 <div class="site-topbar-marquee-track">
                     <span>{{ $topbarPromoText }}</span>
                     <span aria-hidden="true">{{ $topbarPromoText }}</span>
                 </div>
             </div>
             <div class="site-topbar-contact">
                 @if($topbarPhone)
                     <a href="tel:{{ $topbarPhoneHref($topbarPhone) }}">
                         <i class="fa fa-phone" aria-hidden="true"></i>
                         <span>{{ $topbarPhone }}</span>
                     </a>
                 @endif
             </div>
         </div>
     </div>

     <header class="desktop-header-myntra">
     <div class="desktop-header-row desktop-header-row--main">
     <div class="desktop-header-inner">
         @php
             $deliveryCountry = $febCurrency->code() === 'BDT' ? 'Bangladesh' : 'Malaysia';
         @endphp
         <div class="header-logo">
             <a href="{{ route('home') }}">
                 <img src="{{ $febLogoUrl }}" alt="Site Logo" />
             </a>
         </div>

         <div class="header-search">
             <form action="{{ route('shop-new') }}" class="header-search-form" method="GET">
                 <i class="fa fa-search header-search-icon"></i>
                 <input type="text" name="query" class="header-search-input alg-search-box"
                     placeholder="Search Here..." autocomplete="off" />
                 <button type="submit" class="header-search-btn">
                     <i class="fa fa-search"></i>
                 </button>
             </form>
         </div>

         <div class="header-actions">
             <div class="desktop-currency-picker" data-currency-picker>
                 <button type="button" class="desktop-currency-trigger" aria-haspopup="listbox" aria-expanded="false">
                     <span class="desktop-currency-trigger-copy">
                         <span class="desktop-currency-country" data-delivery-country>{{ $deliveryCountry }}</span>
                         <span class="desktop-currency-current" data-currency-current>
                             <span class="desktop-currency-current-flag" aria-hidden="true">{{ $febCurrency->code() === 'BDT' ? '🇧🇩' : '🇲🇾' }}</span>
                             {{ $febCurrency->code() }}
                         </span>
                     </span>
                     <i class="fa fa-chevron-down" aria-hidden="true"></i>
                 </button>
                 <div class="desktop-currency-menu" role="listbox">
                     <button type="button" class="desktop-currency-option" data-currency-option="BDT" role="option">
                         <span class="desktop-currency-flag">🇧🇩</span>
                         <span class="desktop-currency-option-copy"><strong>৳ BDT</strong><small>Bangladeshi Taka</small></span>
                         <i class="fa fa-check desktop-currency-check" aria-hidden="true"></i>
                     </button>
                     <button type="button" class="desktop-currency-option" data-currency-option="MYR" role="option">
                         <span class="desktop-currency-flag">🇲🇾</span>
                         <span class="desktop-currency-option-copy"><strong>RM MYR</strong><small>Malaysian Ringgit</small></span>
                         <i class="fa fa-check desktop-currency-check" aria-hidden="true"></i>
                     </button>
                 </div>
             </div>
             <a href="{{ route('outlets') }}" class="header-action-item">
                 <span class="header-action-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                         <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                         <circle cx="12" cy="10" r="3"></circle>
                     </svg>
                 </span>
                 <span class="header-action-label">Stores</span>
             </a>

             <div class="header-action-item has-dropdown">
                 <span class="header-action-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                         <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                         <circle cx="12" cy="7" r="4"></circle>
                     </svg>
                 </span>
                 <span class="header-action-label">Profile</span>

                 <div class="header-profile-dropdown">
                     <div class="profile-dropdown-header">
                         <div class="welcome-text">{{ Auth::check() ? 'Hello, ' . Auth::user()->first_name : 'Welcome' }}</div>
                         <div class="login-signup">
                             @auth
                                 <a href="{{ route('customer-dashboard') }}">My Account</a>
                                 <span>/</span>
                                 <a href="{{ route('customer-logout') }}">Logout</a>
                             @else
                                 <a href="{{ route('theme-login') }}">Sign in</a>
                                 <span>/</span>
                                 <a href="{{ route('theme-register') }}">Sign up</a>
                             @endauth
                         </div>
                     </div>
                     <div class="profile-dropdown-links">
                         <a href="{{ route('order-tracking') }}"><i class="fa fa-truck"></i>Track Order</a>
                         <a href="#"><i class="fa fa-building"></i>Corporate Sales</a>
                         <a href="#"><i class="fa fa-info-circle"></i>About Us</a>
                     </div>
                 </div>
             </div>

             <a href="{{ route('wishlist') }}" class="header-action-item">
                 <span class="header-action-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                         <path
                             d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                         </path>
                     </svg>
                 </span>
                 <span class="header-action-label">Wishlist</span>
             </a>

             <a href="{{ route('theme-carts') }}" class="header-action-item js-side-cart-open">
                 <span class="header-action-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                         <path d="M6 6h15l-1.5 9h-12z"></path>
                         <circle cx="9" cy="20" r="1"></circle>
                         <circle cx="18" cy="20" r="1"></circle>
                         <path d="M6 6L4 2H1"></path>
                     </svg>
                     <span class="header-cart-badge shopping-cart-badge"
                         style="display: {{ $febCartCount > 0 ? 'flex' : 'none' }}">{{ $febCartCount > 99 ? '99+' : $febCartCount }}</span>
                 </span>
                 <span class="header-action-label">Bag</span>
             </a>
         </div>
     </div>
     </div>

     <div class="desktop-header-row desktop-header-row--categories">
     <div class="desktop-header-inner desktop-header-inner--categories">
         <nav class="header-nav-main">
             <ul class="nav-category-list">
                 @foreach($febMenuCategories as $menuCategory)
                     @php
                         $menuCategoryValue = $menuCategory->category_slug ?: $menuCategory->id;
                     @endphp
                     <li class="nav-category-item" data-category="category-{{ $menuCategory->id }}">
                         <span class="nav-category-link"
                             data-href="{{ route('shop-new', ['category' => $menuCategoryValue]) }}">
                             {{ $menuCategory->category_name }}
                             @if($menuCategory->children->isNotEmpty())
                                 <i class="fa fa-chevron-down nav-category-caret" aria-hidden="true"></i>
                             @endif
                         </span>
                         @if($menuCategory->children->isNotEmpty())
                             <div class="mega-menu">
                                 <div class="mega-menu-inner">
                                     <div class="mega-menu-categories">
                                         <div class="mega-menu-column">
                                             <h4 class="mega-menu-heading">
                                                 <a href="{{ route('shop-new', ['category' => $menuCategoryValue]) }}">
                                                     {{ $menuCategory->category_name }}
                                                 </a>
                                             </h4>
                                             <ul class="mega-menu-links">
                                                 @foreach($menuCategory->children as $menuSubcategory)
                                                     <li>
                                                         <a href="{{ route('shop-new', ['category' => $menuSubcategory->category_slug ?: $menuSubcategory->id]) }}">
                                                             {{ $menuSubcategory->category_name }}
                                                         </a>
                                                     </li>
                                                 @endforeach
                                             </ul>
                                         </div>
                                     </div>

                                     @if($menuCategory->menuProducts->isNotEmpty())
                                         <div class="mega-menu-products">
                                             <h4 class="mega-menu-products-heading">New Arrivals</h4>
                                             <div class="mega-menu-products-grid">
                                                 @foreach($menuCategory->menuProducts as $menuProduct)
                                                     <a href="{{ route('single-product', $menuProduct->slug) }}"
                                                         class="mega-menu-product-card"
                                                         title="{{ $menuProduct->name }}">
                                                         <div class="mega-menu-product-image">
                                                             <img src="{{ \App\Support\MediaStorage::url($menuProduct->img_path, 'products') }}"
                                                                 alt="{{ $menuProduct->name }}" loading="lazy">
                                                         </div>
                                                         <div class="mega-menu-product-info">
                                                             <div class="mega-menu-product-title">{{ $menuProduct->name }}</div>
                                                         </div>
                                                     </a>
                                                 @endforeach
                                             </div>
                                         </div>
                                     @endif

                                     <div class="mega-menu-view-all">
                                         <a href="{{ route('shop-new', ['category' => $menuCategoryValue]) }}">
                                             View All {{ $menuCategory->category_name }} <span aria-hidden="true">&rarr;</span>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                         @endif
                     </li>
                 @endforeach
             </ul>
         </nav>
     </div>
     </div>
     </header>
 </div>

 <script>
     document.addEventListener("DOMContentLoaded", function() {
         var activeCurrency = window.storeCurrency && window.storeCurrency.code === 'MYR' ? 'MYR' : 'BDT';

         function saveCurrency(currency) {
             document.cookie = 'store_currency=' + currency + ';path=/;max-age=31536000;SameSite=Lax';
             window.location.reload();
         }

         document.querySelectorAll('.store-currency-select').forEach(function(select) {
             select.value = activeCurrency;
             select.addEventListener('change', function() {
                 var currency = this.value === 'MYR' ? 'MYR' : 'BDT';
                 saveCurrency(currency);
             });
         });

         var currencyPicker = document.querySelector('[data-currency-picker]');
         if (currencyPicker) {
             var currencyTrigger = currencyPicker.querySelector('.desktop-currency-trigger');
             var currencyCurrent = currencyPicker.querySelector('[data-currency-current]');
             var currencyOptions = currencyPicker.querySelectorAll('[data-currency-option]');

             currencyCurrent.innerHTML = activeCurrency === 'BDT'
                 ? '<span class="desktop-currency-current-flag" aria-hidden="true">🇧🇩</span> BDT'
                 : '<span class="desktop-currency-current-flag" aria-hidden="true">🇲🇾</span> MYR';
             currencyOptions.forEach(function(option) {
                 var selected = option.getAttribute('data-currency-option') === activeCurrency;
                 option.classList.toggle('is-selected', selected);
                 option.setAttribute('aria-selected', selected ? 'true' : 'false');
                 option.addEventListener('click', function() {
                     var currency = option.getAttribute('data-currency-option');
                     currencyPicker.classList.remove('is-open');
                     if (currency !== activeCurrency) saveCurrency(currency);
                 });
             });

             currencyTrigger.addEventListener('click', function(event) {
                 event.stopPropagation();
                 var isOpen = currencyPicker.classList.toggle('is-open');
                 currencyTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
             });
             document.addEventListener('click', function(event) {
                 if (!currencyPicker.contains(event.target)) {
                     currencyPicker.classList.remove('is-open');
                     currencyTrigger.setAttribute('aria-expanded', 'false');
                 }
             });
         }

         var deliveryCountry = document.querySelector('[data-delivery-country]');
         if (deliveryCountry && window.storeCurrency) {
             deliveryCountry.textContent = activeCurrency === 'BDT' ? 'Bangladesh' : 'Malaysia';
         }

         // Handle click on nav category links (spans with data-href)
         document
             .querySelectorAll(".nav-category-link[data-href]")
             .forEach(function(span) {
                 span.addEventListener("click", function(e) {
                     var href = this.getAttribute("data-href");
                     if (href) {
                         window.location.href = href;
                     }
                 });
             });
     });
 </script>

 <div class="mobile-toast-container" id="toastContainer"></div>
