/**
 * Product Card Carousel
 * Lightweight swipe carousel for product cards on shop page
 * Touch swipe, mouse drag, dot navigation with circular wrap
 */
(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        swipeThreshold: 50,      // Min pixels to trigger swipe
        transitionDuration: 300, // ms
        debug: false             // Set to true for console logging
    };

    function log(...args) {
        if (CONFIG.debug) console.log('[Carousel]', ...args);
    }

    /**
     * Initialize carousel on a product card
     */
    function initCarousel(card) {
        const carousel = card.querySelector('.product-carousel');
        if (!carousel || carousel.dataset.initialized === 'true') return;

        const imagesData = carousel.dataset.images;
        if (!imagesData) {
            log('No images data on carousel');
            return;
        }

        let images;
        try {
            images = JSON.parse(imagesData.replace(/&quot;/g, '"'));
        } catch (e) {
            console.warn('Failed to parse carousel images', e);
            return;
        }

        if (!images || images.length <= 1) {
            log('Only 1 or no images, skipping:', images ? images.length : 0);
            return;
        }

        log('Initializing carousel with', images.length, 'images:', images);

        // Mark as initialized
        carousel.dataset.initialized = 'true';

        // State
        let currentIndex = 0;
        let startX = 0;
        let startY = 0;
        let isDragging = false;
        let isHorizontalSwipe = null;
        let hasMoved = false;

        const track = carousel.querySelector('.carousel-track');
        const dotsContainer = carousel.querySelector('.carousel-dots');

        // Build additional slides
        buildSlides(track, images);

        // Build dots
        buildDots(dotsContainer, images.length);
        const dots = dotsContainer.querySelectorAll('.carousel-dot');

        // Touch handlers
        carousel.addEventListener('touchstart', handleTouchStart, { passive: true });
        carousel.addEventListener('touchmove', handleTouchMove, { passive: false });
        carousel.addEventListener('touchend', handleTouchEnd);

        // Mouse handlers for desktop
        carousel.addEventListener('mousedown', handleMouseDown);
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseEnd);

        // Dot click handlers
        dots.forEach((dot, index) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                goToSlide(index);
            });
        });

        // Prevent link navigation on swipe
        const link = card.querySelector('.product-card-link');
        if (link) {
            link.addEventListener('click', (e) => {
                if (hasMoved) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }

        function handleTouchStart(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isDragging = true;
            isHorizontalSwipe = null;
            hasMoved = false;
        }

        function handleTouchMove(e) {
            if (!isDragging) return;

            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = currentX - startX;
            const diffY = currentY - startY;

            // Determine swipe direction on first significant move
            if (isHorizontalSwipe === null && (Math.abs(diffX) > 10 || Math.abs(diffY) > 10)) {
                isHorizontalSwipe = Math.abs(diffX) > Math.abs(diffY);
            }

            // Only handle horizontal swipes
            if (isHorizontalSwipe) {
                e.preventDefault(); // Prevent vertical scroll
                hasMoved = true;
            }
        }

        function handleTouchEnd(e) {
            if (!isDragging) return;

            const endX = e.changedTouches[0].clientX;
            const diff = endX - startX;

            if (isHorizontalSwipe && Math.abs(diff) > CONFIG.swipeThreshold) {
                if (diff > 0) {
                    goToPrev();
                } else {
                    goToNext();
                }
            }

            isDragging = false;
            isHorizontalSwipe = null;

            // Reset hasMoved after a short delay to allow link click to be prevented
            setTimeout(() => { hasMoved = false; }, 50);
        }

        function handleMouseDown(e) {
            // Only left click
            if (e.button !== 0) return;
            startX = e.clientX;
            isDragging = true;
            hasMoved = false;
            e.preventDefault();
        }

        function handleMouseMove(e) {
            if (!isDragging) return;
            const diff = Math.abs(e.clientX - startX);
            if (diff > 5) {
                hasMoved = true;
            }
        }

        function handleMouseEnd(e) {
            if (!isDragging) return;

            const diff = e.clientX - startX;
            if (Math.abs(diff) > CONFIG.swipeThreshold) {
                if (diff > 0) {
                    goToPrev();
                } else {
                    goToNext();
                }
            }

            isDragging = false;

            // Reset hasMoved after a short delay
            setTimeout(() => { hasMoved = false; }, 50);
        }

        function goToSlide(index) {
            // Circular navigation
            if (index < 0) index = images.length - 1;
            if (index >= images.length) index = 0;

            log('Going to slide', index);
            currentIndex = index;

            // Update track position
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update dots
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
        }

        function goToNext() {
            goToSlide(currentIndex + 1);
        }

        function goToPrev() {
            goToSlide(currentIndex - 1);
        }

    }

    /**
     * Build additional carousel slides
     */
    function buildSlides(track, images) {
        // First slide already exists, build remaining
        // Load images directly (no lazy loading) for better UX
        for (let i = 1; i < images.length; i++) {
            const slide = document.createElement('div');
            slide.className = 'carousel-slide';
            slide.innerHTML = `
                <div class="gallerythumbWrapper gallerythumbWrapperLoaded">
                    <img class="gallerythumb gallerythumbLoaded"
                         src="${images[i].src}"
                         alt="Product Image">
                </div>
            `;
            track.appendChild(slide);
        }
    }

    /**
     * Build dot indicators
     */
    function buildDots(container, count) {
        container.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const dot = document.createElement('span');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.dataset.index = i;
            container.appendChild(dot);
        }
    }

    /**
     * Initialize all carousels on page
     */
    function initAllCarousels() {
        const cards = document.querySelectorAll('.product-card[data-has-multiple="1"]');
        log('initAllCarousels: Found', cards.length, 'cards with multiple images');
        cards.forEach(initCarousel);
    }

    /**
     * Observe for dynamically added cards (Algolia infinite scroll)
     */
    function setupMutationObserver() {
        const observer = new MutationObserver((mutations) => {
            let needsInit = false;
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        if (node.classList && node.classList.contains('product-card')) {
                            initCarousel(node);
                            needsInit = true;
                        }
                        if (node.querySelectorAll) {
                            const cards = node.querySelectorAll('.product-card[data-has-multiple="1"]');
                            if (cards.length > 0) {
                                cards.forEach(initCarousel);
                                needsInit = true;
                            }
                        }
                    }
                });
            });
        });

        const hitsContainer = document.getElementById('hits');
        if (hitsContainer) {
            observer.observe(hitsContainer, { childList: true, subtree: true });
        }
    }

    // Expose for manual initialization
    window.ProductCarousel = {
        init: initAllCarousels,
        initCard: initCarousel
    };

    // Initialize on DOM ready
    function onReady() {
        initAllCarousels();
        setupMutationObserver();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onReady);
    } else {
        onReady();
    }

    // Re-init after Algolia renders
    document.addEventListener('algolia:rendered', function() {
        setTimeout(initAllCarousels, 100);
    });
})();
