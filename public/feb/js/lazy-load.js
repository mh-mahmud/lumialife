/**
 * FABRILIFE LAZY LOADING
 * Modern, performant image lazy loading with shimmer placeholders
 *
 * Features:
 * - IntersectionObserver for efficient viewport detection
 * - Graceful fallback for older browsers
 * - Automatic shimmer removal on load
 * - Support for responsive images (srcset)
 * - Works with dynamically added content
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        rootMargin: '200px 0px', // Start loading 200px before entering viewport
        threshold: 0.01,
        fadeInClass: 'loaded',
        lazyClass: 'lazy',
        loadedCallback: null
    };

    // Check for IntersectionObserver support
    const supportsIntersectionObserver = 'IntersectionObserver' in window;

    /**
     * Find ancestor with specific class
     */
    function findAncestorWithClass(element, className) {
        let current = element.parentElement;
        while (current) {
            if (current.classList && current.classList.contains(className)) {
                return current;
            }
            current = current.parentElement;
        }
        return null;
    }

    /**
     * Initialize lazy loading for an image
     */
    function loadImage(img) {
        const src = img.dataset.src;
        const srcset = img.dataset.srcset;
        const sizes = img.dataset.sizes;

        if (!src && !srcset) return;

        // Create a new image to preload
        const tempImg = new Image();

        tempImg.onload = function() {
            // Apply the actual source
            if (src) img.src = src;
            if (srcset) img.srcset = srcset;
            if (sizes) img.sizes = sizes;

            // Add loaded class for CSS transitions
            img.classList.add(config.fadeInClass);
            img.classList.remove(config.lazyClass);

            // Handle parent container classes - search up the DOM tree
            const flexCatImg = findAncestorWithClass(img, 'flex-cat-img');
            if (flexCatImg) {
                flexCatImg.classList.add('loaded');
            }

            const gallerySkeleton = findAncestorWithClass(img, 'gallery-skeleton');
            if (gallerySkeleton) {
                gallerySkeleton.classList.add('loaded');
            }

            const gallerythumbWrapper = findAncestorWithClass(img, 'gallerythumbWrapper');
            if (gallerythumbWrapper) {
                gallerythumbWrapper.classList.add('gallerythumbWrapperLoaded');
            }

            const homeProduct = findAncestorWithClass(img, 'home-product');
            if (homeProduct) {
                homeProduct.classList.add('image-loaded');
            }

            // Also check immediate parent
            const parent = img.parentElement;
            if (parent) {
                parent.classList.add('loaded');
                parent.classList.add('image-loaded');
            }

            // Remove data attributes
            delete img.dataset.src;
            delete img.dataset.srcset;
            delete img.dataset.sizes;

            // Callback if provided
            if (typeof config.loadedCallback === 'function') {
                config.loadedCallback(img);
            }
        };

        tempImg.onerror = function() {
            // Still mark as loaded to remove shimmer
            img.classList.add(config.fadeInClass);
            img.classList.remove(config.lazyClass);

            // Mark ancestors as loaded too
            const flexCatImg = findAncestorWithClass(img, 'flex-cat-img');
            if (flexCatImg) flexCatImg.classList.add('loaded');

            console.warn('Failed to load image:', src || srcset);
        };

        // Start loading
        if (srcset) {
            tempImg.srcset = srcset;
            if (sizes) tempImg.sizes = sizes;
        }
        if (src) {
            tempImg.src = src;
        }
    }

    /**
     * IntersectionObserver callback
     */
    function onIntersection(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                loadImage(img);
                observer.unobserve(img);
            }
        });
    }

    /**
     * Create and return the IntersectionObserver
     */
    function createObserver() {
        if (!supportsIntersectionObserver) return null;

        return new IntersectionObserver(onIntersection, {
            rootMargin: config.rootMargin,
            threshold: config.threshold
        });
    }

    /**
     * Check if element is in viewport
     */
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top < (window.innerHeight || document.documentElement.clientHeight) + 200 &&
            rect.bottom > -200 &&
            rect.left < (window.innerWidth || document.documentElement.clientWidth) + 200 &&
            rect.right > -200
        );
    }

    /**
     * Observe all lazy images in a container
     */
    function observeImages(container, observer) {
        container = container || document;
        const images = container.querySelectorAll('img.' + config.lazyClass);

        if (!observer) {
            // Fallback: load all images immediately
            images.forEach(loadImage);
            return;
        }

        images.forEach(function(img) {
            // If image is already in viewport, load immediately
            if (isInViewport(img)) {
                loadImage(img);
            } else {
                observer.observe(img);
            }
        });
    }

    /**
     * Initialize lazy loading
     */
    function init() {
        const observer = createObserver();

        // Observe existing images
        observeImages(document, observer);

        // Expose for dynamic content
        window.FabrilifeLazy = {
            observe: function(container) {
                observeImages(container, observer);
            },
            loadImage: loadImage,
            refresh: function() {
                observeImages(document, observer);
            },
            config: function(options) {
                Object.assign(config, options);
            }
        };
    }

    /**
     * Convert existing images to lazy load format
     * Call this on dynamic content or run once on page load
     */
    window.convertToLazy = function(container) {
        container = container || document;
        const images = container.querySelectorAll('img:not(.lazy):not(.loaded):not([data-no-lazy])');

        images.forEach(function(img) {
            // Skip if already lazy or has no-lazy attribute
            if (img.classList.contains('lazy') || img.classList.contains('loaded')) return;
            if (img.hasAttribute('data-no-lazy')) return;

            // Skip small images (icons, etc.)
            if (img.width && img.width < 50) return;

            const src = img.src;
            const srcset = img.srcset;

            // Only convert if image has a source
            if (!src && !srcset) return;

            // Store original sources
            if (src) {
                img.dataset.src = src;
                // Use a tiny transparent placeholder
                img.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E';
            }
            if (srcset) {
                img.dataset.srcset = srcset;
                img.removeAttribute('srcset');
            }

            // Add lazy class
            img.classList.add('lazy');

            // Add native lazy loading as fallback
            img.loading = 'lazy';
        });

        // Observe the new lazy images
        if (window.FabrilifeLazy) {
            window.FabrilifeLazy.observe(container);
        }
    };

    /**
     * Helper to create a product skeleton placeholder
     */
    window.createProductSkeleton = function() {
        const skeleton = document.createElement('div');
        skeleton.className = 'product-skeleton';
        skeleton.innerHTML =
            '<div class="skeleton-image"></div>' +
            '<div class="skeleton-text">' +
            '<div class="skeleton-title"></div>' +
            '<div class="skeleton-price"></div>' +
            '</div>';
        return skeleton;
    };

    /**
     * MutationObserver for dynamically added images
     * Automatically converts and observes new images
     */
    function setupMutationObserver() {
        if (!('MutationObserver' in window)) return;

        const mutationObserver = new MutationObserver(function(mutations) {
            let hasNewImages = false;

            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            // Check if the node itself is an image
                            if (node.tagName === 'IMG' && !node.classList.contains('loaded')) {
                                hasNewImages = true;
                            }
                            // Check for images within the node
                            if (node.querySelectorAll) {
                                const imgs = node.querySelectorAll('img:not(.loaded)');
                                if (imgs.length) hasNewImages = true;
                            }
                        }
                    });
                }
            });

            // Debounce: only refresh if new images found
            if (hasNewImages && window.FabrilifeLazy) {
                // Small delay to ensure DOM is ready
                setTimeout(function() {
                    window.FabrilifeLazy.refresh();
                }, 100);
            }
        });

        // Start observing
        mutationObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            init();
            setupMutationObserver();
        });
    } else {
        init();
        setupMutationObserver();
    }

    // Also handle images loaded after window.load (for dynamically loaded content)
    window.addEventListener('load', function() {
        // Give a small delay for any post-load scripts
        setTimeout(function() {
            if (window.FabrilifeLazy) {
                window.FabrilifeLazy.refresh();
            }
        }, 300);
    });

})();

/**
 * jQuery plugin for lazy loading (if jQuery is available)
 */
if (typeof jQuery !== 'undefined') {
    (function($) {
        $.fn.lazyLoad = function() {
            return this.each(function() {
                if (window.FabrilifeLazy) {
                    window.FabrilifeLazy.observe(this);
                }
            });
        };

        // Auto-convert images in AJAX loaded content
        $(document).ajaxComplete(function(event, xhr, settings) {
            setTimeout(function() {
                if (window.FabrilifeLazy) {
                    window.FabrilifeLazy.refresh();
                }
            }, 100);
        });
    })(jQuery);
}
