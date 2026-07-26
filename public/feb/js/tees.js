function gallerythumbLoaded(event) {
    var target = event.target || event.srcElement;
    target.classList.add("gallerythumbLoaded");
    if (target.parentNode)
        target.parentNode.classList.add("gallerythumbWrapperLoaded");
}

function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// ============================================
// Campaign Countdown Timer
// ============================================
function initCampaignCountdown() {
    if (typeof campaignMode === 'undefined' || !campaignMode) return;
    if (typeof campaignEndTimestamp === 'undefined' || !campaignEndTimestamp) return;

    function updateCampaignCountdown() {
        var now = Math.floor(Date.now() / 1000);
        var remaining = campaignEndTimestamp - now;

        if (remaining <= 0) {
            document.querySelectorAll('.campaign-countdown-value').forEach(function(el) {
                el.textContent = '00';
            });
            return;
        }

        var days = Math.floor(remaining / 86400);
        var hours = Math.floor((remaining % 86400) / 3600);
        var minutes = Math.floor((remaining % 3600) / 60);
        var seconds = remaining % 60;

        function pad(n) { return n.toString().padStart(2, '0'); }

        var el = document.getElementById('campaign-days'); if(el) el.textContent = pad(days);
        el = document.getElementById('campaign-hours'); if(el) el.textContent = pad(hours);
        el = document.getElementById('campaign-minutes'); if(el) el.textContent = pad(minutes);
        el = document.getElementById('campaign-seconds'); if(el) el.textContent = pad(seconds);
    }

    setInterval(updateCampaignCountdown, 1000);
    updateCampaignCountdown();

    // Track campaign page view
    if (typeof fbq !== 'undefined' && typeof campaignCategory !== 'undefined') {
        fbq('track', 'ViewContent', {
            content_name: campaignCategory,
            content_category: 'Campaign Page',
            content_type: 'product_group'
        });
    }
    if (typeof gtag !== 'undefined' && typeof campaignCategory !== 'undefined') {
        gtag('event', 'view_item_list', {
            item_list_id: 'campaign',
            item_list_name: campaignCategory
        });
    }
    if (typeof ttq !== 'undefined' && typeof campaignCategory !== 'undefined') {
        ttq.track('ViewContent', {
            content_name: campaignCategory,
            content_category: 'Campaign Page'
        });
    }
}

// ============================================
// Campaign Theme Particles
// ============================================
function initCampaignParticles() {
    if (typeof campaignMode === 'undefined' || !campaignMode) return;

    var container = document.querySelector('.campaign-particles');
    if (!container) return;

    var theme = container.getAttribute('data-theme') || 'default';

    // Theme-specific particle configurations
    var particleConfigs = {
        valentine: { emoji: '❤️', count: 15, altEmoji: '💕' },
        eid: { emoji: '⭐', count: 12, altEmoji: '🌙' },
        pohela: { emoji: '🌸', count: 15, altEmoji: '🎋' },
        christmas: { emoji: '❄️', count: 18, altEmoji: '⛄' },
        winter: { emoji: '❄️', count: 18, altEmoji: '☃️' },
        summer: { emoji: '☀️', count: 10, altEmoji: '🌴' },
        default: null
    };

    var config = particleConfigs[theme];
    if (!config) return; // No particles for default theme

    for (var i = 0; i < config.count; i++) {
        var particle = document.createElement('span');
        particle.className = 'campaign-particle';
        // Alternate between main and alt emoji
        particle.textContent = (i % 3 === 0 && config.altEmoji) ? config.altEmoji : config.emoji;
        particle.style.left = (Math.random() * 100) + '%';
        particle.style.top = (Math.random() * 100) + '%';
        particle.style.animationDelay = (Math.random() * 8) + 's';
        particle.style.fontSize = (0.8 + Math.random() * 0.8) + 'rem';
        container.appendChild(particle);
    }
}

// Initialize campaign features on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initCampaignCountdown();
        initCampaignParticles();
    });
} else {
    initCampaignCountdown();
    initCampaignParticles();
}

// Global search instance for price filter access
var searchInstance = null;
var currentPriceFilter = null;

// ============================================
// TRACKING: InstantSearch Query (JS Only - No CAPI)
// ============================================
var lastTrackedQuery = '';
var searchTrackingTimeout = null;

function trackInstantSearchQuery() {
    if (!searchInstance || !searchInstance.helper) return;

    var currentQuery = searchInstance.helper.state.query || '';
    currentQuery = currentQuery.trim();

    // Skip if query is empty or same as last tracked
    if (!currentQuery || currentQuery === lastTrackedQuery) return;

    // Clear any pending tracking timeout
    if (searchTrackingTimeout) {
        clearTimeout(searchTrackingTimeout);
    }

    // Debounce: wait 1 second after user stops typing
    searchTrackingTimeout = setTimeout(function() {
        // Skip if query is too short (less than 2 characters)
        if (currentQuery.length < 2) return;

        lastTrackedQuery = currentQuery;

        // Facebook Pixel - Search (no CAPI to avoid backend load)
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Search', {
                search_string: currentQuery,
                content_category: 'products'
            });
        }

        // Google Analytics 4 - search
        if (typeof gtag !== 'undefined') {
            gtag('event', 'search', {
                search_term: currentQuery
            });
        }

        // TikTok Pixel - Search
        if (typeof ttq !== 'undefined') {
            ttq.track('Search', {
                query: currentQuery
            });
        }

        console.log('InstantSearch tracked:', currentQuery);
    }, 1000); // 1 second debounce
}

// ============================================
// TRACKING: Category ViewContent (JS Only - No CAPI)
// ============================================
function trackCategoryViewContent(category) {
    if (!category) return;

    // Facebook Pixel - ViewContent with category context
    if (typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent', {
            content_category: category,
            content_type: 'product_group'
        });
    }

    // Google Analytics 4 - view_item_list + virtual pageview
    if (typeof gtag !== 'undefined') {
        gtag('event', 'view_item_list', {
            item_list_name: category
        });

        // Virtual pageview for category
        gtag('event', 'page_view', {
            page_title: 'Shop - ' + category,
            page_location: window.location.href
        });
    }

    // TikTok Pixel - ViewContent
    if (typeof ttq !== 'undefined') {
        ttq.track('ViewContent', {
            content_category: category
        });
    }

    console.log('Category ViewContent tracked:', category);
}

/**
 * Filter products by price range - Horizontal chips style
 */
function filterByPrice(min, max, element) {
    if (!searchInstance) return;

    // Update chip states
    document.querySelectorAll('.price-chip').forEach(function(chip) {
        chip.classList.remove('active');
    });

    // Activate clicked chip
    if (element) {
        element.classList.add('active');
    }

    // Build price filter string
    var priceFilter = '';
    if (max === null) {
        priceFilter = 'price >= ' + min;
    } else {
        priceFilter = 'price >= ' + min + ' AND price <= ' + max;
    }

    // Clear previous price filter and apply new one
    currentPriceFilter = priceFilter;

    // Use helper to add numeric filter
    searchInstance.helper.setQueryParameter('filters', 'status:1 AND isSearchable:1' + (priceFilter ? ' AND ' + priceFilter : ''));
    searchInstance.helper.search();
}

/**
 * Quick Filters - Uses refinementList on 'cats' attribute
 * Clicking a quick filter selects/deselects the parent category
 */

/**
 * Exclusive category selection using Algolia helper
 * Clears all current refinements and sets only the specified category
 */
function selectCategoryExclusive(category, isCurrentlyActive) {
    if (!searchInstance || !searchInstance.helper) return;

    // Get currently selected category BEFORE clearing (for comparison)
    var previousCategory = null;
    if (searchInstance.helper.hasRefinements('cats')) {
        var refinements = searchInstance.helper.getRefinements('cats');
        if (refinements.length > 0) {
            previousCategory = refinements[0].value;
        }
    }

    // Clear ALL current refinements on 'cats' facet
    searchInstance.helper.clearRefinements('cats');

    // Determine if we should add a new refinement
    // Add if: category is provided AND (not toggling off the same category)
    var shouldAddRefinement = category && !(isCurrentlyActive && category === previousCategory);

    if (shouldAddRefinement) {
        searchInstance.helper.addDisjunctiveFacetRefinement('cats', category);

        // Track category ViewContent (JS only - no CAPI)
        // Track whenever we ADD a category refinement
        trackCategoryViewContent(category);
    }

    // Trigger search
    searchInstance.helper.search();

    // Update UI after search completes
    setTimeout(function() {
        syncQuickFilterActiveStates();
        syncTrendingActiveStates();
        updateSubcategoriesFromRefinements();
        updateSidebarVisualParents();
    }, 100);
}

/**
 * Initialize Quick Filter click handlers
 * EXCLUSIVE behavior: clicking a quick filter clears ALL other refinements
 * Uses Algolia helper with clearRefinements for atomic operations
 */
function initQuickFilters() {
    document.querySelectorAll('.quick-filter-item').forEach(function(btn) {
        if (btn.getAttribute('data-server-filter') === 'true') return;

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var category = this.getAttribute('data-filter');
            var isCurrentlyActive = this.classList.contains('active');
            selectCategoryExclusive(category, isCurrentlyActive);
        });
    });
}

/**
 * Sync quick filter button active states with current refinements
 */
function syncQuickFilterActiveStates() {
    var quickFilterCategories = ['Mens', 'Womens', 'Kids', 'Teens'];

    if (!searchInstance || !searchInstance.helper) return;

    quickFilterCategories.forEach(function(cat) {
        var btn = document.querySelector('.quick-filter-item[data-filter="' + cat + '"]');
        if (!btn) return;

        // Check if this category or any of its subcategories are refined
        var isRefined = false;
        if (searchInstance.helper.hasRefinements('cats')) {
            var refinements = searchInstance.helper.getRefinements('cats');
            refinements.forEach(function(ref) {
                if (ref.value === cat || ref.value.indexOf(cat + ' > ') === 0) {
                    isRefined = true;
                }
            });
        }

        if (isRefined) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

/**
 * Update subcategories bar based on current refinements
 * Shows level 2 categories when level 1 (parent) is selected
 * Shows level 3 categories when level 2 or level 3 is selected
 */
function updateSubcategoriesFromRefinements() {
    var quickFilterCategories = ['Mens', 'Womens', 'Kids', 'Teens'];
    var subcategoryBar = document.querySelector('.subcategory-filters-bar');
    if (!subcategoryBar) return;

    if (!searchInstance || !searchInstance.helper) return;

    // Find which parent category is active
    var activeParent = null;
    var activeParentType = null;

    quickFilterCategories.forEach(function(cat) {
        var btn = document.querySelector('.quick-filter-item[data-filter="' + cat + '"]');
        if (btn && btn.classList.contains('active')) {
            activeParent = cat;
            activeParentType = btn.getAttribute('data-category');
        }
    });

    if (!activeParent) {
        hideSubcategories();
        return;
    }

    // Check current refinement level
    var selectedLevel2 = null;
    var selectedLevel3 = null;
    var level2ParentOfLevel3 = null;

    if (searchInstance.helper.hasRefinements('cats')) {
        var refinements = searchInstance.helper.getRefinements('cats');
        refinements.forEach(function(ref) {
            var parts = ref.value.split(' > ');
            if (parts.length === 2 && parts[0] === activeParent) {
                selectedLevel2 = ref.value;
            }
            if (parts.length === 3 && parts[0] === activeParent) {
                selectedLevel3 = ref.value;
                // Extract the level 2 parent from level 3
                level2ParentOfLevel3 = parts[0] + ' > ' + parts[1];
            }
        });
    }

    // Get subcategories from the sidebar refinement list
    var subcategories = [];
    var categoryItems = document.querySelectorAll('#cats .ais-RefinementList-item');

    // Determine which level 2 to use for showing level 3 categories
    var level2ForLevel3 = selectedLevel2 || level2ParentOfLevel3;

    if (level2ForLevel3) {
        // Level 2 or Level 3 is selected - show level 3 categories (children of level2ForLevel3)
        categoryItems.forEach(function(catItem) {
            var checkbox = catItem.querySelector('input[type="checkbox"]');
            var countEl = catItem.querySelector('.facet-count');
            if (checkbox) {
                var value = checkbox.value;
                // Check if this is a direct child of the level 2
                if (value.indexOf(level2ForLevel3 + ' > ') === 0) {
                    var parts = value.split(' > ');
                    if (parts.length === 3) {
                        subcategories.push({
                            value: value,
                            label: parts[2],
                            count: countEl ? countEl.textContent : '0',
                            isRefined: checkbox.checked || value === selectedLevel3
                        });
                    }
                }
            }
        });

        if (subcategories.length > 0) {
            renderSubcategoriesFromList(subcategories, level2ForLevel3, activeParentType, true);
        } else {
            // No level 3 children, show level 2 categories instead
            subcategories = getLevel2Subcategories(categoryItems, activeParent);
            if (subcategories.length > 0) {
                renderSubcategoriesFromList(subcategories, activeParent, activeParentType, false);
            } else {
                hideSubcategories();
            }
        }
    } else {
        // No level 2 selected - show level 2 categories (children of activeParent)
        subcategories = getLevel2Subcategories(categoryItems, activeParent);
        if (subcategories.length > 0) {
            renderSubcategoriesFromList(subcategories, activeParent, activeParentType, false);
        } else {
            hideSubcategories();
        }
    }
}

/**
 * Get level 2 subcategories for a parent
 */
function getLevel2Subcategories(categoryItems, activeParent) {
    var subcategories = [];
    categoryItems.forEach(function(catItem) {
        var checkbox = catItem.querySelector('input[type="checkbox"]');
        var countEl = catItem.querySelector('.facet-count');
        if (checkbox) {
            var value = checkbox.value;
            // Check if this is a direct child of the active parent (level 2)
            if (value.indexOf(activeParent + ' > ') === 0) {
                var parts = value.split(' > ');
                if (parts.length === 2) {
                    subcategories.push({
                        value: value,
                        label: parts[1],
                        count: countEl ? countEl.textContent : '0',
                        isRefined: checkbox.checked
                    });
                }
            }
        }
    });
    return subcategories;
}

/**
 * Render subcategories from refinement list data
 * @param {Array} subcategories - Array of subcategory objects
 * @param {string} parentCategory - The parent category (e.g., "Mens" or "Mens > Half Sleeve T-Shirt")
 * @param {string} parentType - The parent type for styling
 * @param {boolean} isLevel3 - Whether we're showing level 3 categories
 */
function renderSubcategoriesFromList(subcategories, parentCategory, parentType, isLevel3) {
    var subcategoryBar = document.querySelector('.subcategory-filters-bar');
    if (!subcategoryBar) return;

    var html = '';

    // Add parent label indicator for level 3 categories
    if (isLevel3 && parentCategory) {
        // Extract the level 2 name (e.g., "Half Sleeve T-Shirt" from "Mens > Half Sleeve T-Shirt")
        var parts = parentCategory.split(' > ');
        var level2Name = parts.length > 1 ? parts[1] : parentCategory;
        html += '<span class="subcategory-parent-label"><i class="fa fa-folder-open"></i> ' + level2Name + ':</span>';
    }

    html += subcategories.map(function(sub) {
        var activeClass = sub.isRefined ? ' active' : '';
        return '<span class="subcategory-filter-item' + activeClass + '" data-filter="' + sub.value + '" data-parent="' + parentCategory + '">' +
            sub.label + ' <span class="subcat-count">' + sub.count + '</span>' +
            '</span>';
    }).join('');

    subcategoryBar.innerHTML = html;
    subcategoryBar.style.display = 'flex';
    subcategoryBar.setAttribute('data-parent-category', parentType);
    subcategoryBar.setAttribute('data-parent-filter', parentCategory);

    // Add class to indicate level 3 display
    if (isLevel3) {
        subcategoryBar.classList.add('showing-level3');
    } else {
        subcategoryBar.classList.remove('showing-level3');
    }

    // Add click handlers for subcategories
    initSubcategoryClickHandlers();
}

/**
 * Initialize subcategory click handlers
 * EXCLUSIVE behavior: clicking a subcategory clears all other refinements
 * Uses Algolia helper with clearRefinements for atomic operations
 */
function initSubcategoryClickHandlers() {
    document.querySelectorAll('.subcategory-filter-item').forEach(function(item) {
        // Remove existing listeners by cloning
        var newItem = item.cloneNode(true);
        item.parentNode.replaceChild(newItem, item);

        newItem.addEventListener('click', function(e) {
            e.preventDefault();
            var filterValue = this.getAttribute('data-filter');
            var isCurrentlyActive = this.classList.contains('active');
            selectCategoryExclusive(filterValue, isCurrentlyActive);
        });
    });
}

/**
 * Hide subcategories bar
 */
function hideSubcategories() {
    var subcategoryBar = document.querySelector('.subcategory-filters-bar');
    if (subcategoryBar) {
        subcategoryBar.style.display = 'none';
        subcategoryBar.innerHTML = '';
        subcategoryBar.removeAttribute('data-parent-category');
        subcategoryBar.removeAttribute('data-parent-filter');
    }
}

/**
 * Initialize Trending Items - Sidebar (Special Offers)
 * EXCLUSIVE behavior: clicking a special offer clears ALL other refinements
 * Uses Algolia helper with clearRefinements for atomic operations
 */
function initTrendingItems() {
    document.querySelectorAll('.trending-item').forEach(function(item) {
        if (item.getAttribute('data-server-filter') === 'true') return;

        item.addEventListener('click', function() {
            var filterValue = this.getAttribute('data-filter');
            var isCurrentlyActive = this.classList.contains('active');
            selectCategoryExclusive(filterValue, isCurrentlyActive);
        });
    });
}

/**
 * Initialize sidebar category click handlers for exclusive selection
 * Uses EVENT DELEGATION on the parent container to handle dynamically rendered items
 */
var sidebarCategoryHandlerInitialized = false;

function initSidebarCategoryClickHandlers() {
    // Only initialize the delegated event handler ONCE
    if (sidebarCategoryHandlerInitialized) return;
    sidebarCategoryHandlerInitialized = true;

    // Use event delegation on the parent containers
    ['#cats', '#cats-layout'].forEach(function(containerId) {
        var container = document.querySelector(containerId);
        if (!container) return;

        container.addEventListener('click', function(e) {
            // Find the closest facet-item.checkitem
            var item = e.target.closest('.facet-item.checkitem');
            if (!item) return;

            e.preventDefault();
            e.stopImmediatePropagation();

            var categoryValue = item.getAttribute('data-category-value');
            var isRefined = item.getAttribute('data-is-refined') === 'true';

            if (categoryValue) {
                scrollToTop();
                selectCategoryExclusive(categoryValue, isRefined);
            }
        }, true);
    });
}

/**
 * Sync Special Offers active states with current refinements
 */
function syncTrendingActiveStates() {
    if (!searchInstance || !searchInstance.helper) return;

    document.querySelectorAll('.trending-item').forEach(function(item) {
        var filterValue = item.getAttribute('data-filter');
        var isRefined = searchInstance.helper.hasRefinements('cats') &&
                        searchInstance.helper.isRefined('cats', filterValue);
        if (isRefined) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

/**
 * Update sidebar visual parent indicators
 * When a child category is selected, visually show parents as "selected" (fake)
 * This provides visual hierarchy context without actually filtering by parents
 */
function updateSidebarVisualParents() {
    if (!searchInstance || !searchInstance.helper) return;

    var categoryItems = document.querySelectorAll('#cats .ais-RefinementList-item');

    // First, remove all visual parent classes
    categoryItems.forEach(function(item) {
        item.classList.remove('visual-parent-selected');
        var facetItem = item.querySelector('.facet-item');
        if (facetItem) {
            facetItem.classList.remove('visual-parent-selected');
        }
    });

    // Get current refinements
    if (!searchInstance.helper.hasRefinements('cats')) return;

    var refinements = searchInstance.helper.getRefinements('cats');
    if (refinements.length === 0) return;

    // For each refinement, find and mark its parent categories
    refinements.forEach(function(ref) {
        var value = ref.value;
        var parts = value.split(' > ');

        // If it's a level 2 or level 3 category, mark parent(s) as visually selected
        if (parts.length >= 2) {
            // Mark level 1 parent
            var level1Parent = parts[0];
            categoryItems.forEach(function(item) {
                var checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox && checkbox.value === level1Parent) {
                    item.classList.add('visual-parent-selected');
                    var facetItem = item.querySelector('.facet-item');
                    if (facetItem) {
                        facetItem.classList.add('visual-parent-selected');
                    }
                }
            });
        }

        if (parts.length >= 3) {
            // Mark level 2 parent
            var level2Parent = parts[0] + ' > ' + parts[1];
            categoryItems.forEach(function(item) {
                var checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox && checkbox.value === level2Parent) {
                    item.classList.add('visual-parent-selected');
                    var facetItem = item.querySelector('.facet-item');
                    if (facetItem) {
                        facetItem.classList.add('visual-parent-selected');
                    }
                }
            });
        }
    });
}

/**
 * Change Clear Refinements button text
 */
function updateClearButtonText() {
    var isUpdating = false;

    function updateText() {
        if (isUpdating) return;
        isUpdating = true;

        var clearButtons = document.querySelectorAll('.ais-ClearRefinements-button');
        clearButtons.forEach(function(btn) {
            // Only update if text is NOT already 'Clear'
            if (btn.textContent !== 'Clear' && btn.textContent.trim() !== '') {
                btn.textContent = 'Clear';
            }
        });

        // Reset flag after a short delay
        setTimeout(function() { isUpdating = false; }, 50);
    }

    // Initial update
    setTimeout(updateText, 500);

    // Watch for changes and update button text (debounced)
    var debounceTimer = null;
    var observer = new MutationObserver(function(mutations) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateText, 100);
    });

    var clearContainers = document.querySelectorAll('#clear-refinements, #aside-clear-refinements');
    clearContainers.forEach(function(container) {
        if (container) {
            observer.observe(container, { childList: true, subtree: true });
        }
    });
}

/**
 * Clear price filter
 */
function clearPriceFilter() {
    if (!searchInstance) return;

    document.querySelectorAll('.price-range-item input[type="radio"]').forEach(function(radio) {
        radio.checked = false;
    });

    currentPriceFilter = null;
    searchInstance.helper.setQueryParameter('filters', 'status:1 AND isSearchable:1');
    searchInstance.helper.search();
}

window.onload = function () {
    if (window.jQuery) {
        var pricing_data = null;
        tag_data = [];

        var search = instantsearch({
            indexName: "products",
            searchClient: algoliasearch(
                "2UIXGXYA5O",
                "bfcfa7b10e2c9220df5d1d639d485218"
            ),
            routing: true,
        });

        var mode = 'view';
        if(window.location.href.includes("edit=1")) mode = 'edit';

        var customSearchbox = instantsearch.connectors.connectSearchBox(
            function (renderOptions, isFirstRender) {
                var widgetParams = renderOptions.widgetParams;
                const urlParams = new URLSearchParams(window.location.search);
                const searchString = urlParams.get("query")
                    ? urlParams.get("query")
                    : "";
                //console.log(searchString);

                if (isFirstRender) {
                    document.querySelector(widgetParams.container).innerHTML =
                        '<div class="algo-search">' +
                        '<i class="fa fa-search search-icon"></i>' +
                        '<input type="text" value="' +
                        searchString +
                        '" autocomplete="off" id="q" placeholder="' +
                        widgetParams.placeholder +
                        '" />' +
                        '<button type="button" class="search-clear" aria-label="Clear search"><i class="fa fa-times"></i></button>' +
                        "</div>";

                    var searchInput = document.querySelector("#q");
                    var clearBtn = document.querySelector(".search-clear");

                    // Toggle clear button visibility
                    function updateClearButton() {
                        if (searchInput.value.length > 0) {
                            clearBtn.classList.add('visible');
                        } else {
                            clearBtn.classList.remove('visible');
                        }
                    }

                    // Initial state
                    updateClearButton();

                    searchInput.addEventListener("input", function (event) {
                        renderOptions.refine(event.target.value);
                        updateClearButton();
                        // Sync with header search input
                        var headerSearchInput = document.querySelector('.header-search-input.alg-search-box');
                        if (headerSearchInput) {
                            headerSearchInput.value = event.target.value;
                        }
                    });

                    // Clear button click
                    clearBtn.addEventListener("click", function (event) {
                        event.preventDefault();
                        searchInput.value = '';
                        renderOptions.refine('');
                        updateClearButton();
                        searchInput.focus();
                        // Also clear header search input
                        var headerSearchInput = document.querySelector('.header-search-input.alg-search-box');
                        if (headerSearchInput) {
                            headerSearchInput.value = '';
                        }
                    });
                }
            }
        );

        // Build filter string - add campaign category filter if in campaign mode
        var baseFilters = "status:1 AND isSearchable:1";
        if (typeof window.selectedCategoryName !== 'undefined' && window.selectedCategoryName) {
            baseFilters = 'cats:"' + window.selectedCategoryName + '" AND status:1 AND isSearchable:1';
            console.log('Category filter applied from server: "' + window.selectedCategoryName + '"');
        } else if (typeof campaignMode !== 'undefined' && campaignMode === true && typeof campaignCategory !== 'undefined' && campaignCategory) {
            baseFilters = 'cats:"' + campaignCategory + '" AND status:1 AND isSearchable:1';
            console.log('Campaign mode: filtering by category "' + campaignCategory + '"');
        }

        search.addWidget(
            instantsearch.widgets.configure({
                filters: baseFilters,
                restrictSearchableAttributes: ["title"],
                typoTolerance: true,
            })
        );

        search.addWidget(
            customSearchbox({
                container: "#searchbox",
                placeholder: "Search a product",
            })
        );

        search.addWidget(
            instantsearch.widgets.stats({
                container: "#stats",
            })
        );

        search.on("render", function () {
            $(".product-picture img").addClass("transparent");
            $(".product-picture img")
                .one("load", function () {
                    $(this).removeClass("transparent");
                })
                .each(function () {
                    if (this.complete) $(this).load();
                });

            // Sync all filter active states when search results change
            // Use setTimeout to ensure DOM is fully updated by Algolia
            setTimeout(function() {
                syncQuickFilterActiveStates();
                syncTrendingActiveStates();
                updateSubcategoriesFromRefinements();
                updateSidebarVisualParents();
                initSidebarCategoryClickHandlers();
            }, 50);

            // Refresh lazy loading for newly rendered product images
            if (window.FabrilifeLazy) {
                setTimeout(function() {
                    window.FabrilifeLazy.refresh();
                }, 100);
            }

            // Track search queries for analytics (debounced, JS only)
            trackInstantSearchQuery();

            // Dispatch custom event for mobile navigation to sync filters
            document.dispatchEvent(new CustomEvent('algolia:rendered'));
        });

        //products template - Clean, minimal product card with lazy loading
        // Elements: Image + Badge | Title | Rating+Price+Cart (inline row)
        // Cart button is OUTSIDE the <a> tag to prevent navigation on click
        if(mode == 'edit') {
            var hitTemplate = "<div class=\"product-grid-single\"><div class=\"product-card\"><a class=\"product-card-link\" href=\"\/admin\/product-edit\/{{id}}\"><div class=\"product-image-wrap\"><div class=\"gallerythumbWrapper\"><img class=\"gallerythumb lazy\" src=\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E\" data-src=\"\/products\/{{image}}?v=20\" width=\"100%\" alt=\"{{title}}\" loading=\"lazy\" onload=\"gallerythumbLoaded(event)\"><\/div><span class=\"badge-discount\" style=\"display:{{special_display}}\">{{discount_percent}}<\/span><span class=\"badge-delivery\" style=\"display:{{free_delivery_display}}\"><i class=\"fa fa-bolt\"><\/i> Free Delivery<\/span><div class=\"outofstock-badge\" style=\"display:{{outofstock_display}}\">Out of Stock<\/div><\/div><div class=\"product-details\"><h3 class=\"product-name\">{{title}}<\/h3><div class=\"product-info-row\"><div class=\"product-info-left\">{{#rating_display}}<div class=\"product-rating\"><span class=\"rating-badge\">{{{rating_display}}}<\/span><\/div>{{\/rating_display}}<div class=\"product-price-row\"><span class=\"price-current\">\u09F3{{price}}<\/span><span class=\"price-original\" style=\"display:{{special_display}}\">{{regular_price}}<\/span><span class=\"price-off\" style=\"display:{{special_display}}\">{{discount_percent}}<\/span><\/div><\/div><\/div><\/div><\/a><\/div><\/div>";
        }
		else{
			var hitTemplate = "<div class=\"product-grid-single\"><div class=\"product-card\" data-has-multiple=\"{{has_multiple_images}}\"><a class=\"product-card-link\" href=\"\/product\/{{id}}-{{slug}}\"><div class=\"product-image-wrap\"><div class=\"product-carousel\" data-images=\"{{images_json}}\"><div class=\"carousel-track\"><div class=\"carousel-slide active\"><div class=\"gallerythumbWrapper gallerythumbWrapperLoaded\"><img class=\"gallerythumb gallerythumbLoaded\" src=\"\/products\/{{image}}?v=20\" width=\"100%\" alt=\"{{title}}\"><\/div><\/div><\/div><div class=\"carousel-dots\" style=\"display:{{dots_display}}\"><\/div><\/div><button class=\"btn-zoom-image\" data-images=\"{{images_json}}\"><i class=\"fa fa-search-plus\"><\/i><\/button><button class=\"btn-wishlist\" data-wishlist-btn data-product-id=\"{{id}}\"><i class=\"fa fa-heart-o\"><\/i><\/button><span class=\"badge-discount\" style=\"display:{{special_display}}\">{{discount_percent}}<\/span><span class=\"badge-delivery\" style=\"display:{{free_delivery_display}}\"><i class=\"fa fa-bolt\"><\/i> Free Delivery<\/span><span class=\"badge-campaign\" style=\"display:{{campaign_display}}\">{{campaign}}<\/span><div class=\"outofstock-badge\" style=\"display:{{outofstock_display}}\">Out of Stock<\/div><\/div><div class=\"product-details\"><h3 class=\"product-name\">{{title}}<\/h3><div class=\"product-info-row\"><div class=\"product-info-left\">{{#rating_display}}<div class=\"product-rating\"><span class=\"rating-badge\">{{{rating_display}}}<\/span><\/div>{{\/rating_display}}<div class=\"product-price-row\"><span class=\"price-current\">\u09F3{{price}}<\/span><span class=\"price-original\" style=\"display:{{special_display}}\">{{regular_price}}<\/span><span class=\"price-off\" style=\"display:{{special_display}}\">{{discount_percent}}<\/span><\/div><\/div><\/div><\/div><\/a><button class=\"btn-add-cart products-cart-button\" data-productkey=\"{{id}}\" data-url=\"product\/{{id}}-{{slug}}\" data-color=\"{{color}}\" data-title=\"{{title}}\"><i class=\"fa fa-cart-plus\"><\/i><\/button><\/div><\/div>";
		}

        var noResultsTemplate =
            '<div class="text-center">No results found matching <strong>{{query}}</strong>.</div>';

        var menuTemplate =
            '<a href="javascript:void(0);" class="facet-item {{#isRefined}}active{{/isRefined}}"><span class="facet-name"><i class="fa fa-angle-right"></i> {{label}}</span class="facet-name"></a>';

        var facetTemplateCheckbox =
            '<div class="facet-item checkitem {{classname}}" data-category-value="{{fullValue}}" data-is-refined="{{#isRefined}}true{{/isRefined}}{{^isRefined}}false{{/isRefined}}">' +
            '<input type="checkbox" class="{{cssClasses.checkbox}} usual-checkbox" value="{{fullValue}}" {{#isRefined}}checked{{/isRefined}} />{{label}}' +
            '<span class="facet-count">{{count}}</span>' +
            "</div>";

        var facetTemplateColors =
            '<div onclick="scrollToTop()" class="facet-item">' +
            '<input type="checkbox" class="{{cssClasses.checkbox}} usual-checkbox" value="{{label}}" {{#isRefined}}checked{{/isRefined}} />' +
            "<span>{{color}}</span>" +
            '<div class="colorbox" style="background-color:{{label}}" data-filterby="{{label}}"></div>' +
            "</div>";

            search.addWidget(
                instantsearch.widgets.infiniteHits({
                    container: "#hits",
                    hitsPerPage: 16,
                    filters: "status:1",
                    //searchParameters: { facetsExcludes: { "tags": ['gpmerchandise']}},
                    templates: {
                        empty: noResultsTemplate,
                        item: hitTemplate,
                    },
                    transformItems: function (items) {
                        tag_data = [];
                        return items.map(
                            function (item) {
                                item.stars = [];
    
                                /*
                                    for (var i = 1; i <= 5; ++i) {
                                        item.stars.push(i <= item.rating);
                                    }
                                    console.log(item.stars);
                                */

                                //console.log(item);
    
                                if (item.title.toLowerCase().includes(" mask")) {
                                    item.non_medical = "show";
                                    //console.log('found');
                                } else {
                                    item.non_medical = "hide";
                                    //console.log('not found');
                                }
    
                                if (item.image == "null")
                                    item.image = item.image_back;
    
                                if (item.color == "#000000") item.color = "#222222";
                                pricingArrays = [];
                                //console.log(pricing_data);
                                pricing_data.forEach(function (priceVars) {
                                    if (
                                        priceVars.type.toLowerCase() ===
                                        item.type.toLowerCase()
                                    ) {
                                        priceVars.ProductVariant.forEach(function (
                                            sizeVar
                                        ) {
                                            pricingArrays.push(item.price);
                                        });
                                    }
                                });

                                item.starts_from = "";
    
                                if (
                                    item.hasOwnProperty("regular_price") &&
                                    item.regular_price !== "" &&
                                    parseInt(item.regular_price) >
                                        parseInt(item.price)
                                ) {
                                    var regularPriceInt = parseInt(item.regular_price);
                                    var priceInt = parseInt(item.price);
                                    var discountAmount = regularPriceInt - priceInt;
                                    var discountPercent = Math.round((discountAmount / regularPriceInt) * 100);
                                    item.discount_percent = "-" + discountPercent + "%";
                                    item.discounted_amount = "Save Tk. " + discountAmount;
                                    item.regular_price = "\u09F3" + item.regular_price;
                                    item.sale = "SALE";
                                    item.special_display = "block";
                                } else {
                                    item.regular_price = "";
                                    item.discounted_amount = "";
                                    item.discount_percent = "";
                                    item.sale = "";
                                    item.special_display = "none";
                                }
    
                                if (
                                    item.hasOwnProperty("free_delivery") &&
                                    item.free_delivery == 1
                                ) {
                                    item.free_delivery = "FREE DELIVERY";
                                    item.free_delivery_display = "block";
                                } else {
                                    item.free_delivery = "";
                                    item.free_delivery_display = "none";
                                }
    
                                if (
                                    item.hasOwnProperty("campaign") &&
                                    item.campaign &&
                                    item.campaign.length > 0
                                ) {
                                    item.campaign_display = "block";
                                } else {
                                    item.campaign_display = "none";
                                }

                                // Savings display - show "Save Tk X" for products on sale
                                if (item.special_display === "block") {
                                    var regularPriceInt = parseInt(item.regular_price.replace(/[^\d]/g, ''));
                                    var priceInt = parseInt(item.price);
                                    var savingsAmount = regularPriceInt - priceInt;
                                    item.rating_display = "<i class='fa fa-tag'></i> Save \u09F3" + savingsAmount;
                                    item.rating_count = "";
                                    item.show_savings = true;
                                } else {
                                    // Non-sale products - hide the savings section
                                    item.rating_display = "";
                                    item.rating_count = "";
                                    item.show_savings = false;
                                }

                                if(backorder_active) {
                                    if (
                                        item.hasOwnProperty("outlet_instock") &&
                                        item.outlet_instock == 0
                                    ) {
                                        item.outofstock = "OUT OF STOCK";
                                        item.outofstock_display = "block";
                                        item.outofstockcartbtn = "outofstockcartbtn";
                                    } else {
                                        item.outofstock = "";
                                        item.outofstock_display = "none";
                                        item.outofstockcartbtn = "";
                                    }
                                } else {
                                    if (
                                        item.hasOwnProperty("stock_status") &&
                                        item.stock_status == 0
                                    ) {
                                        item.outofstock = "OUT OF STOCK";
                                        item.outofstock_display = "block";
                                        item.outofstockcartbtn = "outofstockcartbtn";
                                    } else {
                                        item.outofstock = "";
                                        item.outofstock_display = "none";
                                        item.outofstockcartbtn = "";
                                    }
                                }
    
                                item.price = Math.min(...pricingArrays);
                                
                                
                                // tag_data = [
                                //     ...new Set([
                                //         ...tag_data,
                                //         ...item.tags.split(","),
                                //     ]),
                                // ];
                                // document.querySelector("#tagbox").innerHTML =
                                //     "<span class='ais-tag-item header'>Tags</tags>";
                                // document.querySelector("#aside-tagbox").innerHTML =
                                //     "<span class='ais-tag-item header'>Tags</tags>";
                                // tags_added = 0;
                                // tag_data.forEach((item) => {
                                //     if (item != "") {
                                //         var tagItem =
                                //             document.createElement("span");
                                //         tagItem.className = "ais-tag-item";
                                //         tagItem.innerHTML = item;
                                //         tagItem.setAttribute(
                                //             "onclick",
                                //             "scrollToTop()"
                                //         );
                                //         tagItem.addEventListener("click", () => {
                                //             document.querySelector("#q").value =
                                //                 document.querySelector("#q").value +
                                //                 " +" +
                                //                 item;
                                //             document
                                //                 .querySelector("#q")
                                //                 .dispatchEvent(
                                //                     new Event("input", {
                                //                         bubbles: true,
                                //                     })
                                //                 );
                                //         });
    
                                //         tagItem2 = tagItem.cloneNode(true);
                                //         tagItem2.addEventListener("click", () => {
                                //             document.querySelector("#q").value =
                                //                 document.querySelector("#q").value +
                                //                 " +" +
                                //                 item;
                                //             document
                                //                 .querySelector("#q")
                                //                 .dispatchEvent(
                                //                     new Event("input", {
                                //                         bubbles: true,
                                //                     })
                                //                 );
                                //         });
    
                                //         if (tags_added++ < 20) {
                                //             document
                                //                 .querySelector("#tagbox")
                                //                 .appendChild(tagItem);
                                //             document
                                //                 .querySelector("#aside-tagbox")
                                //                 .appendChild(tagItem2);
                                //         }
                                //     }
                                // });
                                // tagItem = document.createElement("span");
                                // tagItem.className = "ais-tag-item clear";
                                // tagItem.innerHTML = "Clear Tags";
                                // tagItem.setAttribute("onclick", "scrollToTop()");
                                // tagItem.addEventListener("click", () => {
                                //     document.querySelector("#q").value =
                                //         document.querySelector("#q").value =
                                //             document
                                //                 .querySelector("#q")
                                //                 .value.replace(/\s*\+.*/gi, "");
                                //     document
                                //         .querySelector("#q")
                                //         .dispatchEvent(
                                //             new Event("input", { bubbles: true })
                                //         );
                                // });
    
                                // tagItem2 = tagItem.cloneNode(true);
                                // tagItem2.setAttribute("onclick", "scrollToTop()");
                                // tagItem2.addEventListener("click", () => {
                                //     document.querySelector("#q").value =
                                //         document.querySelector("#q").value =
                                //             document
                                //                 .querySelector("#q")
                                //                 .value.replace(/\s*\+.*/gi, "");
                                //     document
                                //         .querySelector("#q")
                                //         .dispatchEvent(
                                //             new Event("input", { bubbles: true })
                                //         );
                                // });
                                // document
                                //     .querySelector("#tagbox")
                                //     .appendChild(tagItem);
                                // document
                                //     .querySelector("#aside-tagbox")
                                //     .appendChild(tagItem2);

                                // Build all images array for carousel/lightbox
                                item.all_images = [];

                                // Primary image
                                if (item.image && item.image !== 'null') {
                                    item.all_images.push({ src: '/products/' + item.image + '?v=20', type: 'product' });
                                }

                                // Back image
                                if (item.image_back && item.image_back !== 'null' && item.image_back !== '') {
                                    item.all_images.push({ src: '/products/' + item.image_back + '?v=20', type: 'product' });
                                }

                                // Other images from Algolia
                                if (item.other_images_arr && Array.isArray(item.other_images_arr)) {
                                    item.other_images_arr.forEach(function(imgId) {
                                        if (imgId && imgId.trim() !== '') {
                                            item.all_images.push({ src: '/image-gallery/' + imgId.trim(), type: 'gallery' });
                                        }
                                    });
                                }

                                // Template variables for carousel
                                item.images_json = JSON.stringify(item.all_images).replace(/"/g, '&quot;');
                                item.has_multiple_images = item.all_images.length > 1 ? '1' : '0';
                                item.dots_display = item.all_images.length > 1 ? 'flex' : 'none';

                                return item;
                            }
                        );
                    },
                })
            );

        var catList = instantsearch.widgets.panel({
            templates: {
                //header: '<h5>Categories</h5>',
            },
        })(instantsearch.widgets.refinementList);

        search.addWidget(
            catList({
                container: "#cats",
                attribute: "cats",
                operator: "or",
                limit: 200,
                sortBy(a, b) {
                    if(a.name == 'Mega Deal') return -4;
                    if(a.name == 'Free Delivery') return -3;
                    if(a.name == 'New Arrival') return -2;
                    if(a.name == 'Top Selling') return -1;
                    if(b.name == 'Mega Deal') return 4;
                    if(b.name == 'Free Delivery') return 3;
                    if(b.name == 'New Arrival') return 2;
                    if(b.name == 'Top Selling') return 1;
                    return cat_tree[a.name] < cat_tree[b.name] ? -1 : 1;
                },
                templates: {
                    item: facetTemplateCheckbox,
                },
                transformItems: function (items) {
                    // Check helper state for refinements that might not be in items yet
                    if (searchInstance && searchInstance.helper) {
                        try {
                            var state = searchInstance.helper.state;
                            if (state && state.disjunctiveFacetsRefinements && state.disjunctiveFacetsRefinements.cats) {
                                var refinedValues = state.disjunctiveFacetsRefinements.cats;
                                refinedValues.forEach(function(refValue) {
                                    var exists = items.some(function(item) { return item.value === refValue; });
                                    if (!exists) {
                                        // Add the missing refined item with proper name for sorting
                                        // In Algolia refinementList, 'name' equals 'value' (the facet value)
                                        items.push({
                                            label: refValue,
                                            value: refValue,
                                            name: refValue,
                                            count: 0,
                                            isRefined: true,
                                            highlighted: refValue
                                        });
                                    }
                                });
                            }
                        } catch(e) {
                            console.log('transformItems error:', e);
                        }
                    }

                    // Sort items - use 'name' property (which equals facet value in refinementList)
                    items.sort(function (a, b) {
                        var aName = a.name || a.value;
                        var bName = b.name || b.value;
                        if (cat_tree[aName] !== undefined && cat_tree[bName] !== undefined)
                            return cat_tree[aName] < cat_tree[bName] ? -1 : 1;
                        else if (cat_tree[aName] !== undefined && cat_tree[bName] === undefined)
                            return -1;
                        else if (cat_tree[aName] === undefined && cat_tree[bName] !== undefined)
                            return 1;
                        else return aName < bName ? -1 : 1;
                    });

                    return items.map(function (item) {
                        let name = item.label.split(" > ");
                        // Store full value before modifying label
                        item.fullValue = item.label;
                        if (name.length == 1) {
                            item.classname = "level1";
                        }
                        if (name.length == 2) {
                            item.classname = "level2";
                        }
                        if (name.length == 3) {
                            item.classname = "level3";
                        }
                        if (name.length == 4) {
                            item.classname = "level4";
                        }
                        if(name == 'New Arrival' || name == 'Top Selling' || name == 'Free Delivery' || name == 'Mega Deal') {
                            item.classname = "newarrival " + item.classname;
                        }
                        item.label = name[name.length - 1];
                        return item;
                    });
                },
            })
        );

        //////////layout draw cat listing
        if($("#cats-layout").length > 0) {
            var catListlayout = instantsearch.widgets.panel({
                templates: {
                    //header: '<h5>Categories</h5>',
                },
            })(instantsearch.widgets.refinementList);

            console.log("Adding cats layout...")
            search.addWidget(
                catListlayout({
                    container: "#cats-layout",
                    attribute: "cats",
                    operator: "or",
                    limit: 200,
                    sortBy(a, b) {
                        if(a.name == 'Mega Deal') return -4;
                        if(a.name == 'Free Delivery') return -3;
                        if(a.name == 'New Arrival') return -2;
                        if(a.name == 'Top Selling') return -1;
                        if(b.name == 'Mega Deal') return 4;
                        if(b.name == 'Free Delivery') return 3;
                        if(b.name == 'New Arrival') return 2;
                        if(b.name == 'Top Selling') return 1;
                        return cat_tree[a.name] < cat_tree[b.name] ? -1 : 1;
                    },
                    templates: {
                        item: facetTemplateCheckbox,
                    },
                    transformItems: function (items) {
                        // Check helper state for refinements that might not be in items yet
                        if (searchInstance && searchInstance.helper) {
                            try {
                                var state = searchInstance.helper.state;
                                if (state && state.disjunctiveFacetsRefinements && state.disjunctiveFacetsRefinements.cats) {
                                    var refinedValues = state.disjunctiveFacetsRefinements.cats;
                                    refinedValues.forEach(function(refValue) {
                                        var exists = items.some(function(item) { return item.value === refValue; });
                                        if (!exists) {
                                            // Add the missing refined item with proper name for sorting
                                            // In Algolia refinementList, 'name' equals 'value' (the facet value)
                                            items.push({
                                                label: refValue,
                                                value: refValue,
                                                name: refValue,
                                                count: 0,
                                                isRefined: true,
                                                highlighted: refValue
                                            });
                                        }
                                    });
                                }
                            } catch(e) {
                                console.log('transformItems error:', e);
                            }
                        }

                        // Sort items - use 'name' property (which equals facet value in refinementList)
                        items.sort(function (a, b) {
                            var aName = a.name || a.value;
                            var bName = b.name || b.value;
                            if (cat_tree[aName] !== undefined && cat_tree[bName] !== undefined)
                                return cat_tree[aName] < cat_tree[bName] ? -1 : 1;
                            else if (cat_tree[aName] !== undefined && cat_tree[bName] === undefined)
                                return -1;
                            else if (cat_tree[aName] === undefined && cat_tree[bName] !== undefined)
                                return 1;
                            else return aName < bName ? -1 : 1;
                        });

                        return items.map(function (item) {
                            let name = item.label.split(" > ");
                            // Store full value before modifying label
                            item.fullValue = item.label;
                            if (name.length == 1) {
                                item.classname = "level1";
                            }
                            if (name.length == 2) {
                                item.classname = "level2";
                            }
                            if (name.length == 3) {
                                item.classname = "level3";
                            }
                            if (name.length == 4) {
                                item.classname = "level4";
                            }
                            if(name == 'New Arrival' || name == 'Top Selling' || name == 'Free Delivery' || name == 'Mega Deal') {
                                item.classname = "newarrival " + item.classname;
                            }
                            item.label = name[name.length - 1];
                            return item;
                        });
                    },
                })
            );
        }
        ///////////




        search.addWidget(
            instantsearch.widgets.currentRefinements({
                container: '#current-refinements'
            })
        );

        function sanitizestr(str) {
            return str.replace(/[^a-z0-9]/gi, "");
        }

        search.addWidget(
            instantsearch.widgets.clearRefinements({
                container: "#clear-refinements",
            })
        );

        search.addWidget(
            instantsearch.widgets.clearRefinements({
                container: "#aside-clear-refinements",
            })
        );

        if($("#aside-clear-refinements-layout").length > 0) {
            search.addWidget(
                instantsearch.widgets.clearRefinements({
                    container: "#aside-clear-refinements-layout",
                })
            );
        }

        $.get("/products/rocketscience").done(function (data) {
            pricing_data = JSON.parse(data);

            // Make search instance globally accessible BEFORE start
            searchInstance = search;

            search.start();

            // Initialize quick filters, sidebar and trending items after search starts
            setTimeout(function() {
                initQuickFilters();
                initMyntraSidebar();
                initTrendingItems();
                updateClearButtonText();
            }, 500);
        });
    } else {
        console.log("jquery is not loaded");
    }
};

/**
 * Myntra-Style Sidebar Functionality
 * - Dynamic category expansion
 * - Top category quick filters
 * - Header category indication
 * - Sidebar search sync
 */
function initMyntraSidebar() {
    if (window.innerWidth < 768) return; // Only on desktop (768px+)

    // Setup sidebar search box sync with main search
    setupSidebarSearch();

    // Setup top category quick filters
    setupTopCategoryFilters();

    // Setup dynamic category expansion
    setupCategoryExpansion();

    // Setup header category indication
    updateHeaderCategoryIndication();

    // Observe for changes in refinement list (when filters change)
    observeRefinementChanges();
}

/**
 * Sync sidebar search with main Algolia search
 */
function setupSidebarSearch() {
    var sidebarSearch = document.getElementById('sidebar-search');
    var mainSearch = document.getElementById('q');

    if (!sidebarSearch || !mainSearch) return;

    // Sync sidebar search with main search
    sidebarSearch.addEventListener('input', function(e) {
        mainSearch.value = e.target.value;
        mainSearch.dispatchEvent(new Event('input', { bubbles: true }));
    });

    // Sync main search with sidebar search
    mainSearch.addEventListener('input', function(e) {
        sidebarSearch.value = e.target.value;
    });

    // Initialize with current search value
    if (mainSearch.value) {
        sidebarSearch.value = mainSearch.value;
    }
}

/**
 * Setup top category quick filter buttons
 */
function setupTopCategoryFilters() {
    var topCategoryItems = document.querySelectorAll('.top-category-item');

    topCategoryItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var filterValue = this.getAttribute('data-filter');
            var isActive = this.classList.contains('active');

            // Find and click the corresponding category in the refinement list
            var categoryItems = document.querySelectorAll('#cats .ais-RefinementList-item');

            categoryItems.forEach(function(catItem) {
                var label = catItem.querySelector('.facet-item');
                if (label) {
                    var labelText = label.textContent.trim().split(/\s+/)[0]; // Get first word (category name)
                    if (labelText === filterValue || label.textContent.includes(filterValue)) {
                        // Click the checkbox or item to toggle
                        var checkbox = catItem.querySelector('input[type="checkbox"]');
                        if (checkbox) {
                            checkbox.click();
                        } else {
                            catItem.click();
                        }
                    }
                }
            });

            // Toggle active state
            if (isActive) {
                this.classList.remove('active');
            } else {
                // Remove active from others in same group (main categories)
                if (['men', 'women', 'kids', 'teens'].includes(this.getAttribute('data-category'))) {
                    document.querySelectorAll('.top-category-item[data-category="men"], .top-category-item[data-category="women"], .top-category-item[data-category="kids"], .top-category-item[data-category="teens"]').forEach(function(el) {
                        el.classList.remove('active');
                    });
                }
                this.classList.add('active');
            }

            // Update header indication
            updateHeaderCategoryIndication();
        });
    });
}

/**
 * Setup dynamic category expansion - only expand selected parent category
 */
function setupCategoryExpansion() {
    // After Algolia renders, process the category list
    setTimeout(function() {
        processCategoryExpansion();
    }, 100);
}

/**
 * Reorder level 1 categories to a specific order
 */
function reorderCategories() {
    var catContainer = document.getElementById('cats');
    if (!catContainer) return;

    var list = catContainer.querySelector('.ais-RefinementList-list');
    if (!list) return;

    // Desired order for top-level categories
    var desiredOrder = ['Mens', 'Womens', 'Teens', 'Kids', 'Sports', 'Face Mask'];

    // Get all items and group them by their level1 parent
    var items = Array.from(list.querySelectorAll('.ais-RefinementList-item'));
    var categoryGroups = {};
    var currentLevel1 = null;

    items.forEach(function(item) {
        var facetItem = item.querySelector('.facet-item');
        if (!facetItem) return;

        var isLevel1 = facetItem.classList.contains('level1');
        var categoryText = facetItem.textContent.trim().replace(/\d+$/, '').trim();

        if (isLevel1) {
            currentLevel1 = categoryText;
            categoryGroups[currentLevel1] = [item];
        } else if (currentLevel1 && categoryGroups[currentLevel1]) {
            categoryGroups[currentLevel1].push(item);
        }
    });

    // Reorder: first add items in desired order, then any remaining
    var fragment = document.createDocumentFragment();

    desiredOrder.forEach(function(catName) {
        if (categoryGroups[catName]) {
            categoryGroups[catName].forEach(function(item) {
                fragment.appendChild(item);
            });
            delete categoryGroups[catName];
        }
    });

    // Add any remaining categories not in the desired order
    Object.keys(categoryGroups).forEach(function(catName) {
        categoryGroups[catName].forEach(function(item) {
            fragment.appendChild(item);
        });
    });

    // Replace list contents
    list.innerHTML = '';
    list.appendChild(fragment);
}

/**
 * Process category items and add expansion behavior
 * Level 1 and 2 are shown by default, Level 3+ is hidden until Level 2 is expanded
 */
function processCategoryExpansion() {
    var catContainer = document.getElementById('cats');
    if (!catContainer) return;

    // Reorder categories first
    reorderCategories();

    var items = catContainer.querySelectorAll('.ais-RefinementList-item');
    var currentLevel1 = null;
    var currentLevel2 = null;
    var currentLevel1IsProductCategory = false;
    var expandedLevel2 = new Set();

    // Product-type categories to show in Categories panel
    // Any level 1 category NOT in this list is treated as Special Offers (hidden from Categories)
    var productCategories = ['Mens', 'Womens', 'Teens', 'Kids', 'Sports', 'Face Mask'];

    // Helper function to check if a category name matches a product category
    function isProductCategory(name) {
        return productCategories.some(function(pc) {
            return name.includes(pc);
        });
    }

    // First pass: identify categories and their hierarchy
    items.forEach(function(item) {
        var facetItem = item.querySelector('.facet-item');
        if (!facetItem) return;

        var isLevel1 = facetItem.classList.contains('level1');
        var isLevel2 = facetItem.classList.contains('level2');
        var isLevel3 = facetItem.classList.contains('level3');
        var isSelected = item.classList.contains('ais-RefinementList-item--selected');

        // Get the category name (first part before count)
        var categoryText = facetItem.textContent.trim();
        var categoryName = categoryText.replace(/\d+$/, '').trim();

        if (isLevel1) {
            currentLevel1 = categoryName;
            currentLevel2 = null;
            currentLevel1IsProductCategory = isProductCategory(categoryName);
            item.setAttribute('data-parent-category', currentLevel1);

            // Hide non-product categories (Special Offers) from Categories panel
            if (!currentLevel1IsProductCategory) {
                item.setAttribute('data-category-hidden', 'true');
                return;
            } else {
                item.removeAttribute('data-category-hidden');
            }
        } else if (isLevel2) {
            currentLevel2 = categoryName;
            item.setAttribute('data-belongs-to', currentLevel1);
            item.setAttribute('data-level2-category', currentLevel2);

            // Hide level2 items that belong to non-product (Special Offers) categories
            if (!currentLevel1IsProductCategory) {
                item.setAttribute('data-category-hidden', 'true');
                return;
            }

            // If level2 is selected, expand it to show level3
            if (isSelected) {
                expandedLevel2.add(currentLevel2);
                item.classList.add('is-expanded');
            }
        } else if (isLevel3 && currentLevel2) {
            item.setAttribute('data-belongs-to-level2', currentLevel2);

            // Hide level3 items that belong to non-product (Special Offers) categories
            if (!currentLevel1IsProductCategory) {
                item.setAttribute('data-category-hidden', 'true');
                return;
            }

            // If level3 is selected, its level2 parent should be expanded
            if (isSelected) {
                expandedLevel2.add(currentLevel2);
            }
        }
    });

    // Second pass: handle level3 visibility based on level2 expansion
    items.forEach(function(item) {
        var facetItem = item.querySelector('.facet-item');
        if (!facetItem) return;

        var isLevel2 = facetItem.classList.contains('level2');
        var isLevel3 = facetItem.classList.contains('level3');
        var level2Parent = item.getAttribute('data-belongs-to-level2');

        // Mark level2 as expanded if any of its level3 children are selected
        if (isLevel2) {
            var level2Cat = item.getAttribute('data-level2-category');
            if (expandedLevel2.has(level2Cat)) {
                item.classList.add('is-expanded');
            }
        }

        // Show/hide level3 based on level2 expansion state
        if (isLevel3 && level2Parent) {
            if (expandedLevel2.has(level2Parent)) {
                item.classList.add('is-visible');
                facetItem.style.display = 'flex';
            } else {
                item.classList.remove('is-visible');
                facetItem.style.display = 'none';
            }
        }

        // Add click handler to level2 items for expanding level3 (only once)
        if (isLevel2 && !facetItem.hasAttribute('data-click-bound')) {
            facetItem.setAttribute('data-click-bound', 'true');
            facetItem.addEventListener('click', function(e) {
                // Don't interfere with checkbox click
                if (e.target.type === 'checkbox') return;

                var level2Cat = item.getAttribute('data-level2-category');
                var isExpanded = item.classList.contains('is-expanded');

                // Toggle expansion
                if (isExpanded) {
                    item.classList.remove('is-expanded');
                    // Hide level3 children
                    document.querySelectorAll('[data-belongs-to-level2="' + level2Cat + '"]').forEach(function(child) {
                        child.classList.remove('is-visible');
                        var childFacet = child.querySelector('.facet-item');
                        if (childFacet) childFacet.style.display = 'none';
                    });
                } else {
                    item.classList.add('is-expanded');
                    // Show level3 children
                    document.querySelectorAll('[data-belongs-to-level2="' + level2Cat + '"]').forEach(function(child) {
                        child.classList.add('is-visible');
                        var childFacet = child.querySelector('.facet-item');
                        if (childFacet) childFacet.style.display = 'flex';
                    });
                }
            });
        }
    });
}

/**
 * Update header navigation to show active category
 */
function updateHeaderCategoryIndication() {
    var desktopHeader = document.querySelector('.desktop-header-myntra');
    if (!desktopHeader) return;

    // Remove existing active states
    desktopHeader.querySelectorAll('.nav-category-item').forEach(function(item) {
        item.classList.remove('category-active');
    });

    // Check current refinements to determine active category
    var currentRefinements = document.querySelectorAll('#cats .ais-RefinementList-item--selected .facet-item.level1');

    currentRefinements.forEach(function(refinement) {
        var categoryName = refinement.textContent.trim().split(/\s+/)[0].toLowerCase();

        // Map to header nav items
        var headerItem = desktopHeader.querySelector('.nav-category-item[data-category="' + categoryName + '"]');
        if (headerItem) {
            headerItem.classList.add('category-active');
        }
    });

    // Also check top category buttons
    var activeTopCat = document.querySelector('.top-category-item.active');
    if (activeTopCat) {
        var category = activeTopCat.getAttribute('data-category');
        var headerItem = desktopHeader.querySelector('.nav-category-item[data-category="' + category + '"]');
        if (headerItem) {
            headerItem.classList.add('category-active');
        }
    }
}

/**
 * Observe changes in the refinement list to update UI
 * Only watches for childList changes (when Algolia re-renders) to minimize jumps
 */
function observeRefinementChanges() {
    var catsContainer = document.getElementById('cats');
    if (!catsContainer) return;

    var isProcessing = false;
    var lastProcessTime = 0;

    var observer = new MutationObserver(function(mutations) {
        // Skip if we're already processing (prevents infinite loop)
        if (isProcessing) return;

        // Throttle: don't process more than once every 200ms
        var now = Date.now();
        if (now - lastProcessTime < 200) return;

        // Only react to childList changes (Algolia re-rendering the list)
        var hasChildListChange = mutations.some(function(mutation) {
            return mutation.type === 'childList' && mutation.addedNodes.length > 0;
        });

        if (!hasChildListChange) return;

        lastProcessTime = now;

        // Debounce the processing
        clearTimeout(window.categoryProcessTimeout);
        window.categoryProcessTimeout = setTimeout(function() {
            isProcessing = true;
            processCategoryExpansion();
            updateHeaderCategoryIndication();
            updateTopCategoryActiveStates();
            syncQuickFilterActiveStates();
            syncTrendingActiveStates();
            syncSubcategoryActiveStates();
            updateSidebarVisualParents();
            // Reset flag after a short delay to allow DOM to settle
            setTimeout(function() { isProcessing = false; }, 100);
        }, 50);
    });

    // Only watch for childList changes, not attributes
    observer.observe(catsContainer, {
        childList: true,
        subtree: true
    });
}

/**
 * Update top category button active states based on current selections
 */
function updateTopCategoryActiveStates() {
    var selectedCategories = document.querySelectorAll('#cats .ais-RefinementList-item--selected .facet-item');

    // Reset all active states
    document.querySelectorAll('.top-category-item').forEach(function(btn) {
        btn.classList.remove('active');
    });

    selectedCategories.forEach(function(item) {
        var text = item.textContent.trim();

        // Check each top category button
        document.querySelectorAll('.top-category-item').forEach(function(btn) {
            var filter = btn.getAttribute('data-filter');
            if (text.includes(filter) || filter === text) {
                btn.classList.add('active');
            }
        });
    });
}

/**
 * Sync subcategory badge active states with current refinements
 */
function syncSubcategoryActiveStates() {
    if (!searchInstance || !searchInstance.helper) return;

    document.querySelectorAll('.subcategory-filter-item').forEach(function(item) {
        var filterValue = item.getAttribute('data-filter');
        var isRefined = searchInstance.helper.hasRefinements('cats') &&
                        searchInstance.helper.isRefined('cats', filterValue);
        if (isRefined) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

/**
 * Toggle sidebar section collapse/expand
 */
function toggleSidebarSection(element) {
    var section = element.closest('.sidebar-section');
    if (!section) return;

    section.classList.toggle('collapsed');

    var icon = element.querySelector('i');
    if (icon) {
        if (section.classList.contains('collapsed')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        }
    }
}
