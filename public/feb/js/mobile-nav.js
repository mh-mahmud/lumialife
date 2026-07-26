/**
 * Mobile Navigation JavaScript
 * Created: 2026-01-17
 *
 * Handles:
 * - Side menu (hamburger)
 * - Mega menu (categories)
 * - Search modal
 * - Filter panel
 * - Sort panel
 * - Cart drawer
 * - Image zoom
 * - Toast notifications
 */

(function() {
    'use strict';

    // =====================================================
    // Utility Functions
    // =====================================================

    function $(selector) {
        return document.querySelector(selector);
    }

    function $$(selector) {
        return document.querySelectorAll(selector);
    }

    function addClass(el, className) {
        if (el) el.classList.add(className);
    }

    function removeClass(el, className) {
        if (el) el.classList.remove(className);
    }

    function toggleClass(el, className) {
        if (el) el.classList.toggle(className);
    }

    function hasClass(el, className) {
        return el && el.classList.contains(className);
    }

    // Prevent body scroll when modal is open
    function preventBodyScroll(prevent) {
        document.body.style.overflow = prevent ? 'hidden' : '';
    }

    // =====================================================
    // Toast Notifications
    // =====================================================

    window.showToast = function(message, type = 'info') {
        const container = $('#toastContainer');
        if (!container) return;

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        const toast = document.createElement('div');
        toast.className = `mobile-toast ${type}`;
        toast.innerHTML = `
            <i class="fa ${icons[type] || icons.info}"></i>
            <span>${message}</span>
        `;

        container.appendChild(toast);

        // Remove after animation
        setTimeout(() => {
            toast.remove();
        }, 3000);
    };

    // =====================================================
    // Side Menu (Hamburger)
    // =====================================================

    const sideMenu = {
        menu: null,
        overlay: null,

        init: function() {
            this.menu = $('#sideMenu');
            this.overlay = $('#sideMenuOverlay');

            if (!this.menu) return;

            // Open menu
            const openBtn = $('#openMobileMenu');
            if (openBtn) {
                openBtn.addEventListener('click', () => this.open());
            }

            // Close menu
            const closeBtn = $('#closeSideMenu');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Close on overlay click
            if (this.overlay) {
                this.overlay.addEventListener('click', () => this.close());
            }

            // Handle category links that open mega menu
            $$('[data-open-mega-menu]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const category = link.dataset.openMegaMenu;
                    this.close();
                    setTimeout(() => {
                        megaMenu.open(category);
                    }, 300);
                });
            });
        },

        open: function() {
            addClass(this.menu, 'open');
            addClass(this.overlay, 'open');
            preventBodyScroll(true);
        },

        close: function() {
            removeClass(this.menu, 'open');
            removeClass(this.overlay, 'open');
            preventBodyScroll(false);
        }
    };

    // =====================================================
    // Mega Menu (Categories)
    // =====================================================

    const megaMenu = {
        menu: null,
        backdrop: null,
        currentView: 'main',

        init: function() {
            this.menu = $('#megaMenu');
            this.backdrop = $('#megaMenuBackdrop');

            if (!this.menu) return;

            // Open from bottom nav
            const openBtn = $('#openCategoryMenu');
            if (openBtn) {
                openBtn.addEventListener('click', () => this.open());
            }

            // Close button
            const closeBtn = $('#closeMegaMenu');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Close on backdrop click
            if (this.backdrop) {
                this.backdrop.addEventListener('click', () => this.close());
            }

            // Main category clicks
            $$('.main-category').forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.dataset.category;
                    this.showSubcategory(category);
                });
            });

            // Back to main
            $$('.back-to-main').forEach(btn => {
                btn.addEventListener('click', () => {
                    this.showMain();
                });
            });

            // Touch swipe to close
            this.setupSwipeClose();
        },

        open: function(category = null) {
            addClass(this.menu, 'open');
            preventBodyScroll(true);

            if (category) {
                this.showSubcategory(category);
            } else {
                this.showMain();
            }
        },

        close: function() {
            removeClass(this.menu, 'open');
            preventBodyScroll(false);

            // Reset to main view after close
            setTimeout(() => {
                this.showMain();
            }, 300);
        },

        showMain: function() {
            $('#mainCategoriesView').style.display = 'block';
            $$('.subcategory-view').forEach(el => el.style.display = 'none');
            $('#megaMenuTitle').textContent = 'Shop by Category';
            this.currentView = 'main';
        },

        showSubcategory: function(category) {
            $('#mainCategoriesView').style.display = 'none';

            const subcategoryMap = {
                'mens': 'mensSubcategories',
                'womens': 'womensSubcategories',
                'kids': 'kidsSubcategories',
                'teens': 'teensSubcategories',
                'sports': 'sportsSubcategories'
            };

            const subcategoryTitles = {
                'mens': "Men's Collection",
                'womens': "Women's Collection",
                'kids': "Kids Collection",
                'teens': "Teens Collection",
                'sports': "Sports Collection"
            };

            $$('.subcategory-view').forEach(el => el.style.display = 'none');

            const targetId = subcategoryMap[category];
            if (targetId && $('#' + targetId)) {
                $('#' + targetId).style.display = 'block';
                $('#megaMenuTitle').textContent = subcategoryTitles[category] || 'Category';
            }

            this.currentView = category;
        },

        setupSwipeClose: function() {
            const container = $('.mega-menu-container');
            if (!container) return;

            let startY = 0;
            let currentY = 0;

            container.addEventListener('touchstart', (e) => {
                startY = e.touches[0].clientY;
            }, { passive: true });

            container.addEventListener('touchmove', (e) => {
                currentY = e.touches[0].clientY;
                const diff = currentY - startY;

                if (diff > 0) {
                    container.style.transform = `translateY(${diff}px)`;
                }
            }, { passive: true });

            container.addEventListener('touchend', () => {
                const diff = currentY - startY;

                if (diff > 100) {
                    this.close();
                }

                container.style.transform = '';
                startY = 0;
                currentY = 0;
            });
        }
    };

    // =====================================================
    // Search Modal
    // =====================================================

    const searchModal = {
        modal: null,
        input: null,
        recentSearches: [],
        searchTimeout: null,

        init: function() {
            this.modal = $('#searchModal');
            this.input = $('#mobileSearchInput');

            if (!this.modal) return;

            // Load recent searches from localStorage
            this.loadRecentSearches();

            // Open search
            const openBtn = $('#openSearchModal');
            if (openBtn) {
                openBtn.addEventListener('click', () => this.open());
            }

            // Close search
            const closeBtn = $('#closeSearchModal');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Clear search input
            const clearBtn = $('#clearSearch');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    this.input.value = '';
                    clearBtn.style.display = 'none';
                    this.showSuggestions();
                    this.input.focus();
                });
            }

            // Input handler
            if (this.input) {
                this.input.addEventListener('input', () => {
                    const value = this.input.value.trim();
                    $('#clearSearch').style.display = value ? 'flex' : 'none';

                    // Debounce live search
                    clearTimeout(this.searchTimeout);
                    if (value.length >= 2) {
                        this.searchTimeout = setTimeout(() => {
                            this.performSearch(value);
                        }, 300);
                    } else {
                        this.showSuggestions();
                    }
                });

                // Search on enter
                this.input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        const value = this.input.value.trim();
                        if (value) {
                            this.addToRecent(value);
                            window.location.href = `/shop?query=${encodeURIComponent(value)}`;
                        }
                    }
                });
            }

            // Clear recent searches
            const clearRecent = $('#clearRecentSearches');
            if (clearRecent) {
                clearRecent.addEventListener('click', () => {
                    this.recentSearches = [];
                    localStorage.removeItem('fabrilife_recent_searches');
                    this.renderRecentSearches();
                });
            }
        },

        open: function() {
            addClass(this.modal, 'open');
            preventBodyScroll(true);
            this.showSuggestions();

            setTimeout(() => {
                if (this.input) this.input.focus();
            }, 300);
        },

        close: function() {
            removeClass(this.modal, 'open');
            preventBodyScroll(false);
        },

        loadRecentSearches: function() {
            try {
                const stored = localStorage.getItem('fabrilife_recent_searches');
                this.recentSearches = stored ? JSON.parse(stored) : [];
            } catch (e) {
                this.recentSearches = [];
            }
            this.renderRecentSearches();
        },

        addToRecent: function(query) {
            // Remove if exists
            this.recentSearches = this.recentSearches.filter(q => q.toLowerCase() !== query.toLowerCase());

            // Add to front
            this.recentSearches.unshift(query);

            // Keep only last 10
            this.recentSearches = this.recentSearches.slice(0, 10);

            // Save
            localStorage.setItem('fabrilife_recent_searches', JSON.stringify(this.recentSearches));
            this.renderRecentSearches();
        },

        renderRecentSearches: function() {
            const container = $('#recentSearchItems');
            const section = $('#recentSearches');

            if (!container || !section) return;

            if (this.recentSearches.length === 0) {
                section.style.display = 'none';
                return;
            }

            section.style.display = 'block';
            container.innerHTML = this.recentSearches.map(query =>
                `<a href="/shop?query=${encodeURIComponent(query)}" class="search-tag">${query}</a>`
            ).join('');
        },

        showSuggestions: function() {
            $('#liveSearchResults').style.display = 'none';
            $('.recent-searches').style.display = this.recentSearches.length ? 'block' : 'none';
            $('.popular-searches').style.display = 'block';
        },

        performSearch: function(query) {
            // Sync with Algolia desktop search if on shop page
            if (window.search && window.search.helper) {
                // If we're already on the shop page, update the search
                const algoInput = document.querySelector('#searchbox input');
                if (algoInput) {
                    algoInput.value = query;
                    algoInput.dispatchEvent(new Event('input', { bubbles: true }));

                    // Close the modal and let Algolia take over
                    setTimeout(() => {
                        this.close();
                    }, 200);
                }
            }
        },

        // If on shop page, sync mobile search with desktop Algolia search
        syncWithAlgoliaSearch: function() {
            const algoInput = document.querySelector('#searchbox input');
            if (algoInput && this.input) {
                // If Algolia has a query, show it in mobile input
                if (algoInput.value) {
                    this.input.value = algoInput.value;
                }
            }
        }
    };

    // =====================================================
    // Filter Panel
    // =====================================================

    const filterPanel = {
        panel: null,

        // Get Algolia search instance (global searchInstance from tees.js or window.search)
        getSearchInstance: function() {
            if (typeof searchInstance !== 'undefined' && searchInstance && searchInstance.helper) {
                return searchInstance;
            }
            if (window.search && window.search.helper) {
                return window.search;
            }
            return null;
        },

        init: function() {
            this.panel = $('#filtersPanel');
            if (!this.panel) return;

            // Open filters
            const openBtn = $('#openFiltersPanel');
            if (openBtn) {
                openBtn.addEventListener('click', () => this.open());
            }

            // Close filters
            const closeBtn = $('#closeFiltersPanel');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Close on backdrop
            const backdrop = $('#filtersPanelBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', () => this.close());
            }

            // Apply filters / Show Results
            const applyBtn = $('#applyFilters');
            if (applyBtn) {
                applyBtn.addEventListener('click', () => {
                    this.close();
                    // Scroll to the results/hits section
                    setTimeout(() => {
                        const hitsSection = document.querySelector('#hits');
                        if (hitsSection) {
                            const topNavHeight = 80; // sticky mobile topbar (28px) + top nav (52px)
                            const rect = hitsSection.getBoundingClientRect();
                            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                            window.scrollTo({
                                top: rect.top + scrollTop - topNavHeight - 10,
                                behavior: 'smooth'
                            });
                        }
                    }, 100);
                });
            }

            // Clear all filters - in panel header
            const clearBtn = $('#clearAllFilters');
            if (clearBtn) {
                clearBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.clearAllFilters();
                });
            }

            // Clear all - mobile refinements bar
            const mobileClearAll = $('#mobileClearAll');
            if (mobileClearAll) {
                mobileClearAll.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.clearAllFilters();
                });
            }

            // Section toggles
            $$('.filter-section-header').forEach(header => {
                header.addEventListener('click', () => {
                    const section = header.parentElement;
                    const content = section.querySelector('.filter-section-content');

                    if (content) {
                        toggleClass(section, 'expanded');
                        toggleClass(content, 'collapsed');
                    }
                });
            });

            // Quick price filters
            $$('.quick-price').forEach(btn => {
                btn.addEventListener('click', () => {
                    $$('.quick-price').forEach(b => removeClass(b, 'active'));
                    addClass(btn, 'active');

                    const min = parseInt(btn.dataset.min) || 0;
                    const max = btn.dataset.max ? parseInt(btn.dataset.max) : null;

                    if ($('#priceMin')) $('#priceMin').value = min;
                    if ($('#priceMax')) $('#priceMax').value = max || '';

                    this.applyPriceFilter(min, max);
                });
            });

            // Price apply button
            const priceApplyBtn = $('#applyPriceFilter');
            if (priceApplyBtn) {
                priceApplyBtn.addEventListener('click', () => {
                    const min = parseInt($('#priceMin')?.value) || 0;
                    const max = $('#priceMax')?.value ? parseInt($('#priceMax').value) : null;
                    this.applyPriceFilter(min, max);
                });
            }

            // Grid view toggle
            const gridView1 = $('#gridView1');
            const gridView2 = $('#gridView2');
            if (gridView1 && gridView2) {
                gridView1.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    removeClass(gridView2, 'active');
                    addClass(gridView1, 'active');
                    this.setGridColumns(1);
                });
                gridView2.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    removeClass(gridView1, 'active');
                    addClass(gridView2, 'active');
                    this.setGridColumns(2);
                });

                // Restore saved preference
                try {
                    const savedCols = localStorage.getItem('fabrilife_grid_cols');
                    if (savedCols === '1') {
                        removeClass(gridView2, 'active');
                        addClass(gridView1, 'active');
                        // Apply after Algolia renders
                        setTimeout(() => this.setGridColumns(1), 500);
                    }
                } catch(e) {}
            }

            // Listen for Algolia render events to update refinements
            document.addEventListener('algolia:rendered', () => {
                setTimeout(() => {
                    this.updateRefinementDisplay();
                    this.syncCategoryFilters();
                }, 100);
            });

            // Also listen for quick filter clicks
            document.querySelectorAll('.quick-filter-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update refinements after Algolia processes the filter
                    setTimeout(() => this.updateRefinementDisplay(), 300);
                    setTimeout(() => this.updateRefinementDisplay(), 600);
                });
            });

            // Watch for changes to the desktop current-refinements element
            const desktopRefinements = document.querySelector('#current-refinements');
            if (desktopRefinements) {
                const observer = new MutationObserver(() => {
                    setTimeout(() => this.updateRefinementDisplay(), 100);
                });
                observer.observe(desktopRefinements, { childList: true, subtree: true });
            }

            // Initial sync after a delay - multiple attempts for reliability
            setTimeout(() => {
                this.syncCategoryFilters();
                this.updateRefinementDisplay();
            }, 500);
            setTimeout(() => {
                this.syncCategoryFilters();
                this.updateRefinementDisplay();
            }, 1500);

            // Also check when searchInstance becomes available
            const checkSearchInterval = setInterval(() => {
                if (this.getSearchInstance()) {
                    this.updateRefinementDisplay();
                    this.syncCategoryFilters();
                    clearInterval(checkSearchInterval);
                }
            }, 300);
            // Clear interval after 5 seconds max
            setTimeout(() => clearInterval(checkSearchInterval), 5000);
        },

        open: function() {
            addClass(this.panel, 'open');
            preventBodyScroll(true);
            this.syncCategoryFilters();
            this.updateRefinementDisplay();
        },

        close: function() {
            removeClass(this.panel, 'open');
            preventBodyScroll(false);
        },

        clearAllFilters: function() {
            // Click the desktop clear refinements button if it exists
            const clearBtn = document.querySelector('#clear-refinements .ais-ClearRefinements-button');
            const searchObj = this.getSearchInstance();
            if (clearBtn && !clearBtn.disabled) {
                clearBtn.click();
            } else if (searchObj && searchObj.helper) {
                searchObj.helper.clearRefinements().search();
            }
            // Clear quick price selection
            $$('.quick-price').forEach(b => removeClass(b, 'active'));
            if ($('#priceMin')) $('#priceMin').value = '';
            if ($('#priceMax')) $('#priceMax').value = '';

            setTimeout(() => {
                this.updateRefinementDisplay();
                this.syncCategoryFilters();
            }, 200);
        },

        syncCategoryFilters: function() {
            const desktopCats = $('#cats');
            const mobileCatsContent = $('#categoriesFilterContent');

            if (!desktopCats || !mobileCatsContent) return;

            const catsList = desktopCats.querySelector('.ais-RefinementList-list');
            if (catsList) {
                mobileCatsContent.innerHTML = '';
                const clonedList = catsList.cloneNode(true);

                // Add click handlers to cloned items - target .facet-item divs
                clonedList.querySelectorAll('.facet-item').forEach(facetItem => {
                    facetItem.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();

                        // Get the category value from data attribute or checkbox
                        const value = facetItem.dataset.categoryValue;
                        if (value) {
                            // Find and click the original checkbox in desktop cats
                            const originalCheckbox = desktopCats.querySelector(
                                `.usual-checkbox[value="${CSS.escape(value)}"]`
                            );
                            if (originalCheckbox) {
                                originalCheckbox.click();
                                setTimeout(() => {
                                    this.updateRefinementDisplay();
                                    this.syncCategoryFilters();
                                }, 300);
                            }
                        }
                    });
                });

                // Also handle clicks on .ais-RefinementList-item if facet-item not found
                clonedList.querySelectorAll('.ais-RefinementList-item').forEach(item => {
                    const facetItem = item.querySelector('.facet-item');
                    if (!facetItem) {
                        // Fallback for standard Algolia markup
                        item.addEventListener('click', (e) => {
                            e.preventDefault();
                            const checkbox = item.querySelector('input[type="checkbox"]');
                            if (checkbox && checkbox.value) {
                                const originalCheckbox = desktopCats.querySelector(
                                    `input[type="checkbox"][value="${CSS.escape(checkbox.value)}"]`
                                );
                                if (originalCheckbox) {
                                    originalCheckbox.click();
                                    setTimeout(() => {
                                        this.updateRefinementDisplay();
                                        this.syncCategoryFilters();
                                    }, 300);
                                }
                            }
                        });
                    }
                });

                mobileCatsContent.appendChild(clonedList);
            }
        },

        applyPriceFilter: function(min, max) {
            const searchObj = this.getSearchInstance();
            if (searchObj && searchObj.helper) {
                const helper = searchObj.helper;
                helper.removeNumericRefinement('price');

                if (min > 0) {
                    helper.addNumericRefinement('price', '>=', min);
                }
                if (max) {
                    helper.addNumericRefinement('price', '<=', max);
                }

                helper.search();
                setTimeout(() => this.updateRefinementDisplay(), 200);
            }
        },

        setGridColumns: function(cols) {
            // Target the hits container - Algolia creates ais-InfiniteHits-list inside #hits
            const hitsList = document.querySelector('#hits .ais-InfiniteHits-list');
            const hitsContainer = document.querySelector('#hits');

            if (hitsList) {
                if (cols === 1) {
                    hitsList.style.gridTemplateColumns = '1fr';
                    hitsList.classList.add('single-column');
                    hitsList.classList.remove('double-column');
                } else {
                    hitsList.style.gridTemplateColumns = 'repeat(2, 1fr)';
                    hitsList.classList.add('double-column');
                    hitsList.classList.remove('single-column');
                }
            }

            // Also add class to container for CSS targeting
            if (hitsContainer) {
                if (cols === 1) {
                    hitsContainer.classList.add('single-column-view');
                    hitsContainer.classList.remove('double-column-view');
                } else {
                    hitsContainer.classList.add('double-column-view');
                    hitsContainer.classList.remove('single-column-view');
                }
            }

            // Save preference
            try {
                localStorage.setItem('fabrilife_grid_cols', cols);
            } catch(e) {}
        },

        updateRefinementDisplay: function() {
            // Uses desktop #current-refinements (styled via CSS for mobile)
            // Only need to update: badge count and filter panel's active section
            const panelActive = $('#filtersPanelActive');
            const panelTags = $('#filtersPanelActiveTags');
            const badge = $('#activeFilterCount');

            // Only run on mobile (below 768px)
            if (window.innerWidth >= 768) return;

            let count = 0;
            let tags = [];

            const searchObj = this.getSearchInstance();

            if (searchObj && searchObj.helper) {
                const helper = searchObj.helper;
                const state = helper.state;

                // Get category refinements using helper method
                if (typeof helper.hasRefinements === 'function' && helper.hasRefinements('cats')) {
                    const refinements = helper.getRefinements('cats');
                    refinements.forEach(ref => {
                        const displayName = ref.value.split(' > ').pop();
                        tags.push({ type: 'cat', value: ref.value, display: displayName });
                        count++;
                    });
                }
                // Fallback: check state directly
                else if (state && state.disjunctiveFacetsRefinements && state.disjunctiveFacetsRefinements.cats) {
                    const catRefinements = state.disjunctiveFacetsRefinements.cats;
                    if (Array.isArray(catRefinements)) {
                        catRefinements.forEach(cat => {
                            const displayName = cat.split(' > ').pop();
                            tags.push({ type: 'cat', value: cat, display: displayName });
                            count++;
                        });
                    }
                }

                // Get price refinements
                if (state && state.numericRefinements && state.numericRefinements.price) {
                    const priceRefs = state.numericRefinements.price;
                    let priceLabel = '';
                    if (priceRefs['>='] && priceRefs['<=']) {
                        priceLabel = '৳' + priceRefs['>='][0] + ' - ৳' + priceRefs['<='][0];
                    } else if (priceRefs['>=']) {
                        priceLabel = 'Above ৳' + priceRefs['>='][0];
                    } else if (priceRefs['<=']) {
                        priceLabel = 'Under ৳' + priceRefs['<='][0];
                    }
                    if (priceLabel) {
                        tags.push({ type: 'price', value: 'price', display: priceLabel });
                        count++;
                    }
                }
            }

            // Update badge on filter button
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            }

            // Update panel active section (inside filter panel)
            if (panelActive && panelTags) {
                panelTags.innerHTML = tags.map(tag => `
                    <span class="active-tag" data-type="${tag.type}" data-value="${tag.value}">
                        ${tag.display}
                        <button class="remove-tag" aria-label="Remove">&times;</button>
                    </span>
                `).join('');
                panelActive.style.display = tags.length > 0 ? 'block' : 'none';

                panelTags.querySelectorAll('.remove-tag').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const tag = e.target.closest('.active-tag');
                        if (tag) this.removeRefinement(tag.dataset.type, tag.dataset.value);
                    });
                });
            }
        },

        removeRefinement: function(type, value) {
            const searchObj = this.getSearchInstance();
            if (!searchObj || !searchObj.helper) return;

            if (type === 'cat') {
                // Click the desktop checkbox to toggle off
                const desktopCats = $('#cats');
                if (desktopCats) {
                    const checkbox = desktopCats.querySelector(
                        `.ais-RefinementList-checkbox[value="${CSS.escape(value)}"]`
                    );
                    if (checkbox) checkbox.click();
                }
            } else if (type === 'price') {
                searchObj.helper.removeNumericRefinement('price').search();
                $$('.quick-price').forEach(b => removeClass(b, 'active'));
                if ($('#priceMin')) $('#priceMin').value = '';
                if ($('#priceMax')) $('#priceMax').value = '';
            }

            setTimeout(() => {
                this.updateRefinementDisplay();
                this.syncCategoryFilters();
            }, 200);
        }
    };

    // =====================================================
    // Sort Panel
    // =====================================================

    const sortPanel = {
        panel: null,

        init: function() {
            this.panel = $('#sortPanel');
            if (!this.panel) return;

            // Open sort
            const openBtn = $('#openSortOptions');
            if (openBtn) {
                openBtn.addEventListener('click', () => this.open());
            }

            // Close sort
            const closeBtn = $('#closeSortPanel');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Close on backdrop
            const backdrop = $('#sortPanelBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', () => this.close());
            }

            // Sort option selection
            $$('.sort-option input').forEach(input => {
                input.addEventListener('change', () => {
                    const value = input.value;
                    const label = input.nextElementSibling.textContent;

                    $('#currentSortLabel').textContent = label;
                    this.close();

                    // Apply sort (would need Algolia integration)
                    this.applySort(value);
                });
            });
        },

        open: function() {
            addClass(this.panel, 'open');
            preventBodyScroll(true);
        },

        close: function() {
            removeClass(this.panel, 'open');
            preventBodyScroll(false);
        },

        applySort: function(value) {
            // Map sort option values to Algolia index names
            const sortIndexMap = {
                'relevance': 'products',
                'newest': 'products_newest',
                'price_asc': 'products_price_asc',
                'price_desc': 'products_price_desc',
                'popular': 'products_popular',
                'rating': 'products_rating'
            };

            const indexName = sortIndexMap[value] || 'products';

            // Use searchInstance (global from tees.js) or window.search as fallback
            const searchObj = (typeof searchInstance !== 'undefined' && searchInstance) ? searchInstance : window.search;
            if (searchObj && searchObj.helper) {
                // Change the Algolia index for sorting
                searchObj.helper.setIndex(indexName).search();
            }
        }
    };

    // =====================================================
    // Cart Drawer - Uses existing .view-gcart-fixed drawer
    // =====================================================

    const cartDrawer = {
        drawer: null,
        overlay: null,

        init: function() {
            // Get references (may need to refresh later if DOM updates)
            this.refreshRefs();

            // Open cart from bottom nav button
            const openBtn = $('#openCartDrawer');
            if (openBtn) {
                openBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggle();
                });
            }

            // Close cart when clicking overlay (mobile) - use delegation
            document.addEventListener('click', (e) => {
                // Close on overlay click
                if (e.target.classList.contains('cart-overlay') && e.target.classList.contains('overlay')) {
                    this.close();
                }
                // Close on X button click (fa-close inside gcart-title)
                if (e.target.classList.contains('fa-close') && e.target.closest('.gcart-title')) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.close();
                }
            });
        },

        refreshRefs: function() {
            // Re-query in case elements were added after init
            this.drawer = $('.view-gcart-fixed');
            this.overlay = $('.cart-overlay');
        },

        isOpen: function() {
            this.refreshRefs();
            return this.drawer && this.drawer.classList.contains('mobile-cart-open');
        },

        toggle: function() {
            if (this.isOpen()) {
                this.close();
            } else {
                this.open();
            }
        },

        open: function() {
            // Refresh references in case they weren't available at init
            this.refreshRefs();

            if (this.drawer) {
                // Add class for CSS targeting and set inline style
                this.drawer.classList.add('mobile-cart-open');
                this.drawer.style.transform = 'translateX(0px)';
                // Also add overlay and prevent body scroll
                if (this.overlay) {
                    this.overlay.classList.add('overlay');
                }
                document.body.classList.add('still');
                // Recalculate height for current viewport (handles dynamic browser toolbar)
                if (typeof updateDrawerHeights === 'function') {
                    updateDrawerHeights();
                }
            } else {
                // Fallback: navigate to cart page if no drawer available
                window.location.href = '/cart';
            }
        },

        close: function() {
            this.refreshRefs();

            if (this.drawer) {
                // Remove open class and set transform
                this.drawer.classList.remove('mobile-cart-open');
                // On mobile (< 768px) use 100%, on desktop/tablet use calc(100% - 40px)
                var isMobile = window.innerWidth < 768;
                this.drawer.style.transform = isMobile ? 'translateX(100%)' : 'translateX(calc(100% - 40px))';
                // Remove overlay and allow body scroll
                if (this.overlay) {
                    this.overlay.classList.remove('overlay');
                }
                document.body.classList.remove('still');
            }
        },

        updateBadge: function(count) {
            const badge = $('#cartBadge');
            if (badge) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    };

    // Make cart drawer accessible globally
    window.cartDrawer = cartDrawer;

    // =====================================================
    // Image Zoom
    // =====================================================

    const imageZoom = {
        modal: null,
        currentScale: 1,
        images: [],
        currentIndex: 0,

        init: function() {
            this.modal = $('#imageZoomModal');
            if (!this.modal) return;

            // Close button
            const closeBtn = $('#closeImageZoom');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Zoom controls (with null checks for browser compatibility)
            var zoomIn = $('#zoomIn');
            var zoomOut = $('#zoomOut');
            var zoomReset = $('#zoomReset');
            var zoomPrev = $('#zoomPrev');
            var zoomNext = $('#zoomNext');

            if (zoomIn) zoomIn.addEventListener('click', () => this.zoomIn());
            if (zoomOut) zoomOut.addEventListener('click', () => this.zoomOut());
            if (zoomReset) zoomReset.addEventListener('click', () => this.resetZoom());

            // Navigation
            if (zoomPrev) zoomPrev.addEventListener('click', () => this.prev());
            if (zoomNext) zoomNext.addEventListener('click', () => this.next());

            // Touch gestures for zoom/pan
            this.setupTouchGestures();

            // Double tap to zoom
            this.setupDoubleTap();
        },

        open: function(imageSrc, title, bgColor, allImages = null) {
            if (!this.modal) return;

            this.images = allImages || [imageSrc];
            this.currentIndex = 0;
            this.currentScale = 1;

            this.showImage(imageSrc, bgColor);
            this.updateCounter();
            this.updateThumbnails();

            addClass(this.modal, 'active');
            preventBodyScroll(true);

            // Show hint on first open
            const hint = $('#zoomHint');
            if (hint && !localStorage.getItem('zoom_hint_shown')) {
                addClass(hint, 'visible');
                localStorage.setItem('zoom_hint_shown', 'true');
            }

            // Show/hide navigation
            if (this.images.length > 1) {
                $('#zoomPrev').style.display = 'flex';
                $('#zoomNext').style.display = 'flex';
            } else {
                $('#zoomPrev').style.display = 'none';
                $('#zoomNext').style.display = 'none';
            }
        },

        close: function() {
            removeClass(this.modal, 'active');
            preventBodyScroll(false);
            this.resetZoom();
        },

        showImage: function(src, bgColor = '#fff') {
            const img = $('#zoomImage');
            if (img) {
                img.src = src;
                img.style.backgroundColor = bgColor;
            }
        },

        updateCounter: function() {
            $('.zoom-image-counter .current-image').textContent = this.currentIndex + 1;
            $('.zoom-image-counter .total-images').textContent = this.images.length;
        },

        updateThumbnails: function() {
            const container = $('#zoomThumbnails');
            if (!container) return;

            if (this.images.length <= 1) {
                addClass(container, 'single-image');
                return;
            }

            removeClass(container, 'single-image');
            container.innerHTML = this.images.map((img, i) => `
                <div class="zoom-thumbnail ${i === this.currentIndex ? 'active' : ''}" data-index="${i}">
                    <img src="${img}" alt="" />
                </div>
            `).join('');

            // Thumbnail clicks
            $$('.zoom-thumbnail').forEach(thumb => {
                thumb.addEventListener('click', () => {
                    this.currentIndex = parseInt(thumb.dataset.index);
                    this.showImage(this.images[this.currentIndex]);
                    this.updateCounter();
                    $$('.zoom-thumbnail').forEach(t => removeClass(t, 'active'));
                    addClass(thumb, 'active');
                });
            });
        },

        prev: function() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.showImage(this.images[this.currentIndex]);
                this.updateCounter();
                this.updateThumbnails();
            }
        },

        next: function() {
            if (this.currentIndex < this.images.length - 1) {
                this.currentIndex++;
                this.showImage(this.images[this.currentIndex]);
                this.updateCounter();
                this.updateThumbnails();
            }
        },

        zoomIn: function() {
            this.currentScale = Math.min(this.currentScale + 0.5, 4);
            this.applyZoom();
        },

        zoomOut: function() {
            this.currentScale = Math.max(this.currentScale - 0.5, 1);
            this.applyZoom();
        },

        resetZoom: function() {
            this.currentScale = 1;
            this.applyZoom();
            const wrapper = $('#zoomImageWrapper');
            if (wrapper) {
                wrapper.style.transform = 'scale(1) translate(0, 0)';
            }
        },

        applyZoom: function() {
            const wrapper = $('#zoomImageWrapper');
            if (wrapper) {
                wrapper.style.transform = `scale(${this.currentScale})`;
            }
        },

        setupTouchGestures: function() {
            // Simplified pinch-to-zoom would go here
            // For production, consider using a library like Hammer.js
        },

        setupDoubleTap: function() {
            const container = $('#zoomImageContainer');
            if (!container) return;

            let lastTap = 0;

            container.addEventListener('touchend', (e) => {
                const currentTime = new Date().getTime();
                const tapLength = currentTime - lastTap;

                if (tapLength < 300 && tapLength > 0) {
                    // Double tap
                    if (this.currentScale > 1) {
                        this.resetZoom();
                    } else {
                        this.currentScale = 2;
                        this.applyZoom();
                    }
                    e.preventDefault();
                }

                lastTap = currentTime;
            });
        }
    };

    // Global function to open image zoom
    window.openImageZoom = function(imageSrc, title, bgColor, allImages) {
        imageZoom.open(imageSrc, title, bgColor, allImages);
    };

    // =====================================================
    // Initialize Everything
    // =====================================================

    // Wishlist button handler - Now the button is a direct link to /wishlist page
    // No click handler needed as the <a> tag handles navigation naturally
    function initWishlistButton() {
        // Badge updates are handled by WishlistManager in wishlist.js
        // This function is kept for backwards compatibility
    }

    // Mobile Chat button handler - triggers existing chat support
    function initMobileChatButton() {
        var chatBtn = $('#openMobileChat');
        if (chatBtn) {
            chatBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Trigger the existing chat-support button's click
                var existingChatBtn = document.getElementById('chat-support');
                if (existingChatBtn) {
                    existingChatBtn.click();
                } else {
                    // Fallback: try to open chat window directly
                    var chatFrame = document.querySelector('.chat-frame');
                    var chatWindow = document.querySelector('.chat-window');

                    if (chatFrame && chatWindow) {
                        if (!chatFrame.src || chatFrame.src === '') {
                            chatFrame.src = 'https://chat.fabrilife.com/chat';
                        }
                        chatWindow.classList.remove('chat-hidden');
                    } else {
                        if (window.showToast) {
                            window.showToast('Chat is loading...', 'info');
                        }
                    }
                }
            });
        }
    }

    function init() {
        sideMenu.init();
        megaMenu.init();
        searchModal.init();
        filterPanel.init();
        sortPanel.init();
        cartDrawer.init();
        imageZoom.init();
        initWishlistButton();
        initMobileChatButton();

        console.log('Mobile navigation initialized');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
