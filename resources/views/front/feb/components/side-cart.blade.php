<style>
    .feb-side-cart-overlay {
        position: fixed;
        inset: 0;
        z-index: 10040;
        background: rgba(15, 23, 42, .52);
        opacity: 0;
        visibility: hidden;
        transition: opacity .25s, visibility .25s;
    }

    .feb-side-cart {
        position: fixed;
        top: 0;
        right: 0;
        z-index: 10050;
        display: flex;
        flex-direction: column;
        width: min(400px, 92vw);
        height: 100vh;
        height: 100dvh;
        background: #f4f5f7;
        box-shadow: -12px 0 35px rgba(0, 0, 0, .18);
        transform: translateX(105%);
        transition: transform .28s ease;
    }

    body.feb-side-cart-open {
        overflow: hidden;
    }

    body.feb-side-cart-open .feb-side-cart-overlay {
        opacity: 1;
        visibility: visible;
    }

    body.feb-side-cart-open .feb-side-cart {
        transform: translateX(0);
    }

    .feb-side-cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 52px;
        padding: 0 17px;
        background: #292929;
        color: #fff;
    }

    .feb-side-cart-header strong {
        font-size: 14px;
        letter-spacing: .04em;
    }

    .feb-side-cart-close {
        border: 0;
        background: transparent;
        color: #ff5d5d;
        cursor: pointer;
        font-size: 22px;
        line-height: 1;
    }

    .feb-side-cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }

    .feb-side-cart-item {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 84px;
        gap: 12px;
        margin-bottom: 10px;
        padding: 12px;
        background: #fff;
        border: 1px solid #e3e6ea;
    }

    .feb-side-cart-item-image {
        width: 84px;
        height: 92px;
        border: 1px solid #e5e7eb;
        object-fit: cover;
    }

    .feb-side-cart-item-name {
        display: block;
        margin-bottom: 9px;
        color: #172033;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.4;
        text-decoration: none;
    }

    .feb-side-cart-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 5px 12px;
        margin-bottom: 8px;
        color: #687386;
        font-size: 11px;
    }

    .feb-side-cart-item-price {
        color: #111827;
        font-size: 13px;
        font-weight: 700;
    }

    .feb-side-cart-footer {
        border-top: 1px solid #dfe3e8;
        background: #fff;
    }

    .feb-side-cart-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 16px;
        color: #172033;
        font-size: 14px;
    }

    .feb-side-cart-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .feb-side-cart-action {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 47px;
        border: 0;
        background: #333;
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .feb-side-cart-action.is-checkout {
        background: #20b46a;
    }

    .feb-side-cart-empty,
    .feb-side-cart-loading {
        display: flex;
        min-height: 55vh;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #6b7280;
        text-align: center;
    }

    .feb-side-cart-empty i,
    .feb-side-cart-loading i {
        font-size: 38px;
        color: #a5adba;
    }

    .feb-floating-cart {
        position: fixed;
        top: 43%;
        right: 0;
        z-index: 10020;
        width: 48px;
        min-height: 50px;
        padding: 8px 5px;
        border: 0;
        border-radius: 5px 0 0 5px;
        background: #087cf0;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(8, 124, 240, .28);
        text-align: center;
    }

    .feb-floating-cart i {
        display: block;
        font-size: 18px;
    }

    .feb-floating-cart-count {
        display: {{ $febCartCount > 0 ? 'inline-flex' : 'none' }};
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        margin-top: 4px;
        padding: 0 4px;
        border-radius: 4px;
        background: #fff;
        color: #087cf0;
        font-size: 11px;
        font-weight: 700;
    }

    @media (max-width: 575px) {
        .feb-floating-cart {
            display: none;
        }

        .feb-side-cart {
            width: 92vw;
        }
    }
</style>

@unless(request()->routeIs('theme-carts', 'theme-checkout', 'thankyou-page'))
    <button type="button" class="feb-floating-cart js-side-cart-open" aria-label="Open shopping cart">
        <i class="fa fa-shopping-cart"></i>
        <span class="feb-floating-cart-count shopping-cart-badge">{{ $febCartCount > 99 ? '99+' : $febCartCount }}</span>
    </button>
@endunless

<div class="feb-side-cart-overlay" data-side-cart-close></div>
<aside class="feb-side-cart" id="febSideCart" aria-hidden="true" aria-label="Shopping cart">
    <div class="feb-side-cart-header">
        <strong>CART</strong>
        <button type="button" class="feb-side-cart-close" data-side-cart-close aria-label="Close cart">&times;</button>
    </div>
    <div class="feb-side-cart-items" id="febSideCartItems">
        <div class="feb-side-cart-loading"><i class="fa fa-spinner fa-spin"></i><span>Loading cart...</span></div>
    </div>
    <div class="feb-side-cart-footer" id="febSideCartFooter" hidden>
        <div class="feb-side-cart-total">
            <span>Cart Total</span>
            <strong id="febSideCartTotal">{{ $febCurrency->format(0) }}</strong>
        </div>
        <div class="feb-side-cart-actions">
            <a class="feb-side-cart-action" href="{{ route('theme-carts') }}">View Cart</a>
            <a class="feb-side-cart-action is-checkout" href="{{ route('theme-checkout') }}">Checkout&nbsp; <i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sideCart = document.getElementById('febSideCart');
        var itemsContainer = document.getElementById('febSideCartItems');
        var footer = document.getElementById('febSideCartFooter');
        var total = document.getElementById('febSideCartTotal');
        var dataUrl = @json(route('side-cart-data'));

        function formatMoney(value) {
            return Number(value || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function escapeHtml(value) {
            var div = document.createElement('div');
            div.textContent = value == null ? '' : String(value);
            return div.innerHTML;
        }

        function updateBadges(count) {
            document.querySelectorAll('#cartBadge, .shopping-cart-badge').forEach(function (badge) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            });
        }

        function renderCart(data) {
            var items = Array.isArray(data.items) ? data.items : [];
            updateBadges(Number(data.cart_count) || 0);

            if (!items.length) {
                itemsContainer.innerHTML = '<div class="feb-side-cart-empty"><i class="fa fa-shopping-cart"></i><strong>Your cart is empty</strong><a href="{{ route('shop-new') }}">Start shopping</a></div>';
                footer.hidden = true;
                return;
            }

            itemsContainer.innerHTML = items.map(function (item) {
                var meta = '<span>Qty: ' + Number(item.quantity || 0) + '</span>';
                if (item.color) meta += '<span>Color: ' + escapeHtml(item.color) + '</span>';
                if (item.size) meta += '<span>Size: ' + escapeHtml(item.size) + '</span>';

                return '<article class="feb-side-cart-item">' +
                    '<div><a class="feb-side-cart-item-name" href="' + escapeHtml(item.url) + '">' + escapeHtml(item.name) + '</a>' +
                    '<div class="feb-side-cart-item-meta">' + meta + '</div>' +
                    '<div class="feb-side-cart-item-price">' + window.formatStoreCurrency(item.line_total) + '</div></div>' +
                    '<img class="feb-side-cart-item-image" src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '">' +
                    '</article>';
            }).join('');

            total.textContent = window.formatStoreCurrency(data.cart_subtotal);
            footer.hidden = false;
        }

        function loadCart() {
            itemsContainer.innerHTML = '<div class="feb-side-cart-loading"><i class="fa fa-spinner fa-spin"></i><span>Loading cart...</span></div>';
            footer.hidden = true;

            window.fetch(dataUrl, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            }).then(function (response) {
                if (!response.ok) throw new Error('Cart request failed');
                return response.json();
            }).then(renderCart).catch(function () {
                itemsContainer.innerHTML = '<div class="feb-side-cart-empty"><i class="fa fa-exclamation-circle"></i><strong>Could not load cart</strong><a href="{{ route('theme-carts') }}">Open cart page</a></div>';
            });
        }

        function openCart(event) {
            if (event) event.preventDefault();
            document.body.classList.add('feb-side-cart-open');
            sideCart.setAttribute('aria-hidden', 'false');
            loadCart();
        }

        function closeCart() {
            document.body.classList.remove('feb-side-cart-open');
            sideCart.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.js-side-cart-open').forEach(function (trigger) {
            trigger.addEventListener('click', openCart);
        });
        document.querySelectorAll('[data-side-cart-close]').forEach(function (trigger) {
            trigger.addEventListener('click', closeCart);
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closeCart();
        });

        window.openFebSideCart = openCart;
        window.refreshFebSideCart = loadCart;
    });
</script>
