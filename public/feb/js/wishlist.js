(function () {
    'use strict';

    const manager = {
        items: [],
        busy: new Set(),

        init() {
            this.bindButtons();
            this.render();

            if (window.isLoggedIn) {
                this.fetchItems();
            }
        },

        async fetchItems() {
            try {
                const response = await fetch(window.wishlistDataUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    this.items = (data.items || []).map(Number);
                    this.render(data.count);
                }
            } catch (error) {
                console.error('Unable to load wishlist.', error);
            }
        },

        bindButtons() {
            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-wishlist-btn]');
                if (!button) return;

                event.preventDefault();
                event.stopPropagation();
                this.toggle(Number(button.dataset.productId), button);
            });
        },

        async toggle(productId, button) {
            if (!productId || this.busy.has(productId)) return;

            if (!window.isLoggedIn) {
                const returnTo = window.location.pathname + window.location.search;
                const loginUrl = new URL(window.wishlistLoginUrl, window.location.origin);
                loginUrl.searchParams.set('redirect', returnTo);
                loginUrl.searchParams.set('wishlist_product_id', productId);
                window.location.href = loginUrl.toString();
                return;
            }

            this.busy.add(productId);
            button.classList.add('is-loading');
            button.disabled = true;

            try {
                const response = await fetch(window.wishlistToggleUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                const data = await response.json();

                if (response.status === 401) {
                    window.location.href = window.wishlistLoginUrl;
                    return;
                }

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Wishlist could not be updated.');
                }

                if (data.action === 'added' && !this.items.includes(productId)) {
                    this.items.push(productId);
                } else if (data.action === 'removed') {
                    this.items = this.items.filter((id) => id !== productId);
                }

                this.render(data.count);
                this.notify(data.message, 'success');
                window.dispatchEvent(new CustomEvent('wishlistUpdated', {
                    detail: { action: data.action, productId, count: data.count }
                }));
            } catch (error) {
                this.notify(error.message || 'Wishlist could not be updated.', 'error');
            } finally {
                this.busy.delete(productId);
                button.classList.remove('is-loading');
                button.disabled = false;
            }
        },

        has(productId) {
            return this.items.includes(Number(productId));
        },

        count() {
            return this.items.length;
        },

        render(serverCount) {
            document.querySelectorAll('[data-wishlist-btn]').forEach((button) => {
                const active = this.has(button.dataset.productId);
                const removeButton = button.dataset.wishlistStyle === 'remove';
                button.classList.toggle('is-active', active);
                button.classList.toggle('active', active);
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
                button.setAttribute('title', removeButton ? 'Remove from wishlist' : (active ? 'Remove from wishlist' : 'Add to wishlist'));

                const icon = button.querySelector('i');
                if (icon && !removeButton) {
                    icon.classList.toggle('fa-heart', active);
                    icon.classList.toggle('fa-heart-o', !active);
                }
            });

            const count = Number.isFinite(Number(serverCount)) ? Number(serverCount) : this.items.length;
            document.querySelectorAll('.wishlist-badge, .wishlist-badge-mobile, .wishlist-badge-desktop').forEach((badge) => {
                badge.textContent = count;
                badge.style.display = count > 0 ? '' : 'none';
            });
        },

        notify(message, type) {
            if (window.toastr && typeof window.toastr[type] === 'function') {
                window.toastr[type](message);
                return;
            }

            const toast = document.createElement('div');
            toast.textContent = message;
            toast.style.cssText = 'position:fixed;right:20px;top:90px;z-index:100000;padding:12px 18px;border-radius:5px;color:#fff;background:' +
                (type === 'success' ? '#178447' : '#c0392b') + ';box-shadow:0 6px 20px rgba(0,0,0,.18)';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2600);
        }
    };

    window.WishlistManager = manager;
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => manager.init());
    } else {
        manager.init();
    }
})();
