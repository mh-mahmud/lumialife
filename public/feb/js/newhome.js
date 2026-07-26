/**
 * Newhome.js - Homepage JavaScript utilities
 * Created: 2026-01-18
 *
 * Provides carousel functionality for homepage
 */

/**
 * Initialize a slick carousel with responsive settings
 * @param {string} item_name - The class name of the carousel container
 */
function carousel_sleek(item_name) {
    if (typeof $.fn.slick === 'undefined') {
        console.warn('Slick carousel not loaded');
        return;
    }

    $('.' + item_name).slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
}

// Initialize on document ready
$(document).ready(function() {
    // Any homepage-specific initialization can go here
});
