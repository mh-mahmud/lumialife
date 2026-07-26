/**
 * Product Card Lightbox
 * Full-screen image viewer with zoom/pan support
 * Reuses patterns from product detail page
 */
(function() {
    'use strict';

    // State
    let currentZoom = 1;
    let maxZoom = 3;
    let minZoom = 1;
    let panX = 0, panY = 0;
    let isDragging = false;
    let startX = 0, startY = 0;
    let currentImageIndex = 0;
    let allImages = [];
    let initialPinchDistance = 0;
    let initialZoom = 1;
    let zoomLevelTimeout = null;

    // DOM elements
    let lightbox, lightboxImg, zoomLevel, thumbsContainer;
    let lightboxInitialized = false;

    // Lightbox HTML
    const lightboxHTML = `
        <div class="product-lightbox" id="productLightbox">
            <button class="lightbox-close" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
            <button class="lightbox-nav lightbox-prev" aria-label="Previous">
                <i class="fa fa-chevron-left"></i>
            </button>
            <button class="lightbox-nav lightbox-next" aria-label="Next">
                <i class="fa fa-chevron-right"></i>
            </button>
            <div class="lightbox-image-wrapper">
                <img class="lightbox-image" id="lightboxImg" src="" alt="Product Image" />
            </div>
            <div class="lightbox-zoom-level" id="lightboxZoomLevel">100%</div>
            <div class="lightbox-controls">
                <button class="lightbox-btn lightbox-zoom-out" title="Zoom out">
                    <i class="fa fa-minus"></i>
                </button>
                <button class="lightbox-btn lightbox-reset" title="Reset zoom">
                    <i class="fa fa-refresh"></i>
                </button>
                <button class="lightbox-btn lightbox-zoom-in" title="Zoom in">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="lightbox-thumbnails" id="lightboxThumbs"></div>
            <div class="lightbox-instructions">
                <span class="desktop-only">Scroll to zoom &bull; Drag to pan &bull; ESC to close</span>
                <span class="mobile-only">Pinch to zoom &bull; Drag to pan &bull; Tap outside to close</span>
            </div>
        </div>
    `;

    /**
     * Initialize lightbox (create DOM once)
     */
    function initLightbox() {
        if (lightboxInitialized) return;

        document.body.insertAdjacentHTML('beforeend', lightboxHTML);

        lightbox = document.getElementById('productLightbox');
        lightboxImg = document.getElementById('lightboxImg');
        zoomLevel = document.getElementById('lightboxZoomLevel');
        thumbsContainer = document.getElementById('lightboxThumbs');

        // Event listeners
        lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox-prev').addEventListener('click', () => switchImage(currentImageIndex - 1));
        lightbox.querySelector('.lightbox-next').addEventListener('click', () => switchImage(currentImageIndex + 1));
        lightbox.querySelector('.lightbox-zoom-in').addEventListener('click', () => zoom(0.5));
        lightbox.querySelector('.lightbox-zoom-out').addEventListener('click', () => zoom(-0.5));
        lightbox.querySelector('.lightbox-reset').addEventListener('click', resetZoom);

        // Close on background click
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.classList.contains('lightbox-image-wrapper')) {
                closeLightbox();
            }
        });

        // Keyboard
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') switchImage(currentImageIndex - 1);
            if (e.key === 'ArrowRight') switchImage(currentImageIndex + 1);
        });

        // Mouse wheel zoom
        lightbox.addEventListener('wheel', (e) => {
            if (!lightbox.classList.contains('active')) return;
            e.preventDefault();
            zoom(e.deltaY > 0 ? -0.3 : 0.3);
        }, { passive: false });

        // Drag for panning
        lightboxImg.addEventListener('mousedown', startDrag);
        document.addEventListener('mousemove', doDrag);
        document.addEventListener('mouseup', endDrag);

        // Touch support
        lightbox.addEventListener('touchstart', handleTouchStart, { passive: true });
        lightbox.addEventListener('touchmove', handleTouchMove, { passive: false });
        lightbox.addEventListener('touchend', handleTouchEnd);

        // Double-tap to zoom
        let lastTap = 0;
        lightboxImg.addEventListener('touchend', (e) => {
            const now = Date.now();
            if (now - lastTap < 300) {
                e.preventDefault();
                if (currentZoom > 1) {
                    resetZoom();
                } else {
                    zoom(1);
                }
            }
            lastTap = now;
        });

        lightboxInitialized = true;
    }

    /**
     * Open lightbox with images
     */
    function openLightbox(images, startIndex = 0) {
        initLightbox();

        allImages = images;
        currentImageIndex = startIndex;

        // Reset zoom
        resetZoom();

        // Load first image
        loadImage(currentImageIndex);

        // Build thumbnails
        buildThumbnails();

        // Show/hide nav buttons
        updateNavButtons();

        // Show lightbox
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Show instructions briefly
        showInstructions();
    }

    /**
     * Close lightbox
     */
    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        resetZoom();
    }

    /**
     * Load image at index
     */
    function loadImage(index) {
        if (index < 0 || index >= allImages.length) return;

        currentImageIndex = index;
        lightboxImg.src = allImages[index].src;

        // Update thumbnails
        thumbsContainer.querySelectorAll('.lightbox-thumb').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });

        updateNavButtons();
    }

    /**
     * Switch to image (with circular wrap)
     */
    function switchImage(index) {
        if (index < 0) index = allImages.length - 1;
        if (index >= allImages.length) index = 0;
        resetZoom();
        loadImage(index);
    }

    /**
     * Build thumbnail strip
     */
    function buildThumbnails() {
        thumbsContainer.innerHTML = '';
        if (allImages.length <= 1) {
            thumbsContainer.style.display = 'none';
            return;
        }

        thumbsContainer.style.display = 'flex';
        allImages.forEach((img, i) => {
            const thumb = document.createElement('img');
            thumb.className = 'lightbox-thumb' + (i === currentImageIndex ? ' active' : '');
            thumb.src = img.src;
            thumb.alt = 'Thumbnail ' + (i + 1);
            thumb.addEventListener('click', (e) => {
                e.stopPropagation();
                switchImage(i);
            });
            thumbsContainer.appendChild(thumb);
        });
    }

    /**
     * Update nav button visibility
     */
    function updateNavButtons() {
        const hasMultiple = allImages.length > 1;
        lightbox.querySelector('.lightbox-prev').style.display = hasMultiple ? 'flex' : 'none';
        lightbox.querySelector('.lightbox-next').style.display = hasMultiple ? 'flex' : 'none';
    }

    /**
     * Zoom function
     */
    function zoom(delta) {
        const oldZoom = currentZoom;
        currentZoom = Math.max(minZoom, Math.min(maxZoom, currentZoom + delta));

        if (currentZoom !== oldZoom) {
            constrainPan();
            applyTransform();
            showZoomLevel();
            updateZoomButtons();
        }
    }

    /**
     * Reset zoom and pan
     */
    function resetZoom() {
        currentZoom = 1;
        panX = 0;
        panY = 0;
        applyTransform();
        updateZoomButtons();
    }

    /**
     * Apply transform to image
     */
    function applyTransform() {
        if (!lightboxImg) return;
        lightboxImg.style.transform = `scale(${currentZoom}) translate(${panX}px, ${panY}px)`;
    }

    /**
     * Constrain pan to keep image visible
     */
    function constrainPan() {
        if (currentZoom <= 1) {
            panX = 0;
            panY = 0;
            return;
        }

        // Get viewport and image dimensions
        const wrapper = lightbox.querySelector('.lightbox-image-wrapper');
        if (!wrapper) return;

        const wrapperRect = wrapper.getBoundingClientRect();
        const imgRect = lightboxImg.getBoundingClientRect();

        // Calculate max pan based on zoom level
        const scaledWidth = lightboxImg.naturalWidth * currentZoom;
        const scaledHeight = lightboxImg.naturalHeight * currentZoom;

        const maxPanX = Math.max(0, (scaledWidth - wrapperRect.width) / 2 / currentZoom);
        const maxPanY = Math.max(0, (scaledHeight - wrapperRect.height) / 2 / currentZoom);

        panX = Math.max(-maxPanX, Math.min(maxPanX, panX));
        panY = Math.max(-maxPanY, Math.min(maxPanY, panY));
    }

    /**
     * Show zoom level indicator
     */
    function showZoomLevel() {
        zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
        zoomLevel.classList.add('visible');
        clearTimeout(zoomLevelTimeout);
        zoomLevelTimeout = setTimeout(() => zoomLevel.classList.remove('visible'), 1500);
    }

    /**
     * Update zoom button states
     */
    function updateZoomButtons() {
        if (!lightbox) return;
        const zoomIn = lightbox.querySelector('.lightbox-zoom-in');
        const zoomOut = lightbox.querySelector('.lightbox-zoom-out');
        if (zoomIn) zoomIn.disabled = currentZoom >= maxZoom;
        if (zoomOut) zoomOut.disabled = currentZoom <= minZoom;
    }

    /**
     * Start drag (for panning)
     */
    function startDrag(e) {
        if (currentZoom <= 1) return;
        isDragging = true;
        startX = e.clientX - panX * currentZoom;
        startY = e.clientY - panY * currentZoom;
        lightboxImg.classList.add('dragging');
        e.preventDefault();
    }

    /**
     * Do drag (for panning)
     */
    function doDrag(e) {
        if (!isDragging) return;
        e.preventDefault();
        panX = (e.clientX - startX) / currentZoom;
        panY = (e.clientY - startY) / currentZoom;
        constrainPan();
        applyTransform();
    }

    /**
     * End drag
     */
    function endDrag() {
        if (!isDragging) return;
        isDragging = false;
        if (lightboxImg) lightboxImg.classList.remove('dragging');
    }

    /**
     * Handle touch start (for pinch-to-zoom)
     */
    function handleTouchStart(e) {
        if (e.touches.length === 2) {
            // Pinch start
            initialPinchDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            initialZoom = currentZoom;
        } else if (e.touches.length === 1 && currentZoom > 1) {
            // Pan start
            isDragging = true;
            startX = e.touches[0].clientX - panX * currentZoom;
            startY = e.touches[0].clientY - panY * currentZoom;
        }
    }

    /**
     * Handle touch move (for pinch-to-zoom and pan)
     */
    function handleTouchMove(e) {
        if (e.touches.length === 2) {
            // Pinch zoom
            e.preventDefault();
            const dist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            currentZoom = Math.max(minZoom, Math.min(maxZoom, initialZoom * (dist / initialPinchDistance)));
            constrainPan();
            applyTransform();
            showZoomLevel();
            updateZoomButtons();
        } else if (e.touches.length === 1 && isDragging && currentZoom > 1) {
            // Pan
            e.preventDefault();
            panX = (e.touches[0].clientX - startX) / currentZoom;
            panY = (e.touches[0].clientY - startY) / currentZoom;
            constrainPan();
            applyTransform();
        }
    }

    /**
     * Handle touch end
     */
    function handleTouchEnd() {
        isDragging = false;
        initialPinchDistance = 0;
    }

    /**
     * Show instructions briefly
     */
    function showInstructions() {
        const instr = lightbox.querySelector('.lightbox-instructions');
        if (!instr) return;

        // Only show once per session
        if (sessionStorage.getItem('lightbox-instructions-shown')) return;
        sessionStorage.setItem('lightbox-instructions-shown', 'true');

        instr.classList.add('visible');
        setTimeout(() => instr.classList.remove('visible'), 4000);
    }

    // Expose globally
    window.ProductLightbox = {
        open: openLightbox,
        close: closeLightbox
    };

    // Handle zoom button clicks on product cards
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-zoom-image');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();

            const imagesData = btn.dataset.images;
            if (imagesData) {
                try {
                    const images = JSON.parse(imagesData.replace(/&quot;/g, '"'));
                    if (images && images.length > 0) {
                        openLightbox(images, 0);
                    }
                } catch (err) {
                    console.warn('Failed to parse images data for lightbox', err);
                }
            }
        }
    });
})();
