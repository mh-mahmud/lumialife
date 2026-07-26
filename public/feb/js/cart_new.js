// Spinner for table rows (gcart) and divs (cart page)
var spinner = '<td class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></td>';
var spinnerDiv = '<div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';

function calcSum() {
    var sum = 0;
    document.querySelectorAll('.cartinput-price').forEach(function (el) {
        sum += parseFloat(el.textContent.replace(/[^\d\.]*/g,''));
    });
    if(document.querySelector('.checkout-total')){
        // Update all checkout-total elements (there may be multiple in new design)
        document.querySelectorAll('.checkout-total').forEach(function(el) {
            el.textContent = sum;
        });
    }
    if(document.querySelector('.gart-total-amount')){
        document.querySelector('.gart-total-amount').textContent = "৳ " + sum;
        if(sum === 0) {
            document.querySelector(".empty-cart").style.display = "flex";
            document.querySelector(".gcart-content").style.display = "none";
            document.querySelector(".gcart-control").style.display = "none";
        } else {
            document.querySelector(".empty-cart").style.display = "none";
            document.querySelector(".gcart-content").style.display = "block";
            document.querySelector(".gcart-control").style.display = "flex";
        }
    }
}

if(document.querySelector('.checkout-total')){
    calcSum();
}


function goToCheckout() {
    // Generate event ID for deduplication
    var checkoutEventId = 'evt_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

    // Fire browser pixel with event ID
    if (typeof fbq !== 'undefined') {
        fbq('track', 'InitiateCheckout', {}, { eventID: checkoutEventId });
    }

    // Fire server-side CAPI for InitiateCheckout (if config available from layout)
    if (typeof window.fbCapiConfig !== 'undefined' && typeof initializeFacebookData === 'function') {
        var fbData = initializeFacebookData();
        var conversionData = {
            event_name: 'InitiateCheckout',
            event_id: checkoutEventId,
            event_time: Math.floor(Date.now() / 1000),
            user_data: {
                em: window.fbCapiConfig.userEmail,
                ph: window.fbCapiConfig.userPhone,
                client_ip_address: window.fbCapiConfig.clientIp,
                client_user_agent: navigator.userAgent,
                fbc: fbData.fbc,
                fbp: fbData.fbp,
                external_id: fbData.external_id,
            },
            custom_data: {
                currency: 'BDT'
            }
        };

        $.ajax({
            url: window.fbCapiConfig.trackUrl,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(conversionData),
            complete: function() {
                window.location.href = "/checkout";
            }
        });
    } else {
        // Fallback if config not available
        window.location.href = "/checkout";
    }
}

function updateCart(event) {
    var _button = $(event.target);
    // Support both table rows (gcart) and divs (cart page)
    var _container = _button.closest(".new-designed-element");
    if(_container.length === 0) {
        _container = _button.closest("tr");
    }

    // Add appropriate spinner based on element type
    if(_container.is("tr")) {
        _container.append(spinner);
    } else {
        _container.append(spinnerDiv);
    }

    var currentItem = _container[0];
    var sizeEl = currentItem.querySelector('.cartinput-size-select');
    var quantityEl = currentItem.querySelector('.cartinput-quantity');
    var queryString = "[data-productkey='" + currentItem.dataset.productkey + "'][data-color='" + currentItem.dataset.color + "'][data-prevsize='" + sizeEl.value + "']";
    if (event.target.className.includes("cartinput-size-select") && document.querySelector(queryString)) {
        swal({
            title: "Cart Message",
            text: "Your selected size of this product already exists in cart.",
            type: "warning",
        });
        sizeEl.value = currentItem.dataset.prevsize;
        _container.find('.spinner').remove();
    } else {
        var form = new FormData();
        form.append("_token", $('meta[name=csrf-token]').attr("content"));
        form.append("product", currentItem.dataset.productkey);
        form.append("color", currentItem.dataset.color);
        form.append("prevSize", currentItem.dataset.prevsize);
        form.append("newSize", sizeEl.value);
        form.append("quantity", quantityEl.value);

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "/update-cart",
            "method": "POST",
            "headers": {
                "Cache-Control": "no-cache",
            },
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": form
        }

        $.ajax(settings).done(function (response) {
            var json = JSON.parse(response);
            if(json.hasOwnProperty("error")) {
                toastr["error"](json.error);
                currentItem.querySelector(".cartinput-quantity").value = currentItem.dataset.prevqty;
                currentItem.querySelector(".cartinput-size-select").value = currentItem.dataset.prevsize;
            } else {
                currentItem.dataset.prevqty = json.quantity;
                localStorage.setItem('currentcartsize', sizeEl.value);
                currentItem.dataset.price = json.price;
                currentItem.dataset.prevsize = json.size;
                if(json.regular_price && json.regular_price !== '' && json.regular_price > json.price) {
                    currentItem.querySelector('.Price').innerHTML = "<span class='regular-price cart_item_regular_price'>৳" + json.regular_price + "</span> <span>৳" + (json.price - Math.floor(json.price * discount/100)) + "</span>";
                    if(currentItem.querySelector('.cart_regular_price_field')) {
                        currentItem.querySelector('.cart_regular_price_field').textContent = "৳" + (json.regular_price * json.quantity);
                    }
                } else {
                    currentItem.querySelector('.Price').innerHTML = "<span>৳" + (json.price - Math.floor(json.price * discount/100)) + "</span>";
                }
                currentItem.querySelector('.cartinput-price').textContent ="৳" +  (json.price - Math.floor(json.price * discount/100)) * json.quantity;

                if(json.hasOwnProperty("stock")) {
                    item_stock = json.stock;
                    quantity_selector = currentItem.querySelector('.cartinput-quantity');
                    quantity_selector.innerHTML = '';
                    for(var i = 1; i <= item_stock; i++) {
                      var option = document.createElement('option');
                      option.value = i;
                      option.textContent = i;
                      if(i === json.quantity) {
                        option.selected = true;
                      }
                      quantity_selector.appendChild(option);
                    }
                }

                calcSum();
                // Update cart badge with new quantity
                updateCartBadge();
            }
            _container.find('.spinner').remove();
        });
    }
}

function removeFromCart(event){
    var _button = $(event.target);
    // Support both table rows (gcart) and divs (cart page)
    var _container = _button.closest(".new-designed-element");
    if(_container.length === 0) {
        _container = _button.closest("tr");
    }

    // Show confirmation dialog before removing
    swal({
        title: "Remove Item",
        text: "Are you sure you want to remove this item from your cart?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, Remove",
        cancelButtonText: "Cancel"
    }).then(function(result) {
        if (result.value) {
            // Add appropriate spinner
            if(_container.is("tr")) {
                _container.append(spinner);
            } else {
                _container.append(spinnerDiv);
            }

            var form = new FormData();
            form.append("_token", $('meta[name=csrf-token]').attr("content"));
            form.append("product", _container.data('productkey'));
            form.append("color", _container.data('color'));
            form.append("prevSize", _container.data('prevsize'));

            var settings = {
                "async": true,
                "crossDomain": true,
                "url": "/remove-from-cart",
                "method": "POST",
                "headers": {
                    "Cache-Control": "no-cache",
                },
                "processData": false,
                "contentType": false,
                "mimeType": "multipart/form-data",
                "data": form
            }

            $.ajax(settings).done(function (response) {
                if(response == "removed from cart"){
                    _container.find('.spinner').remove();
                    _container.remove();
                    updateCartBadge();
                    calcSum();
                    toastr["warning"]("Removed from Cart");
                }
            });
        }
    });
}

function updateCartBadge(){
    // Sum all quantities from cart items
    var totalQuantity = 0;
    document.querySelectorAll('.new-designed-element').forEach(function(el) {
        var qtySelect = el.querySelector('.cartinput-quantity');
        if (qtySelect) {
            totalQuantity += parseInt(qtySelect.value) || 0;
        }
    });
    document.querySelectorAll('.shopping-cart-badge').forEach(function(el) {
        el.textContent = totalQuantity;
        el.style.display = totalQuantity > 0 ? '' : 'none';
    });
    // Also update mobile bottom nav cart badge
    var mobileCartBadge = document.getElementById('cartBadge');
    if (mobileCartBadge) {
        mobileCartBadge.textContent = totalQuantity;
        mobileCartBadge.style.display = totalQuantity > 0 ? '' : 'none';
    }
    if(totalQuantity == 0){
        if(window.location.href.includes("cart"))
        window.location.href = "/shop";
    }
}


window.addEventListener('click', function(e){
    var el = e.target;
    if(el.classList.contains("addmoreBtn")){
        color = el.dataset.color;
        $.ajax({
            url: '/add-item-to-cart',
            type: 'GET',
            data : {
                id : el.dataset.productkey,
            },success: function (data) {
                console.log('AJAX success - data[3]:', data[3]);
                console.log('AJAX success - size_chart:', data[3] ? data[3].size_chart : 'no data[3]');
                localStorage.removeItem('currentcartsize');
                localStorage.setItem('currentcartinkCost', data[3]['price']);
                localStorage.setItem('cartaddedPrice', data[4]);
                pricingVariables = data[1];
                // Reset size guide state before populating
                resetSizeGuideState();
                populateSizeBoxes(color, data[9], data[3]);
                populateColorCircles(data[0], color);
                populatePriceBox();
                // Populate size chart in modal
                populateModalSizeChart(data[3]);
                // Populate product image and title
                if (document.querySelector('.pricemodal .card-photo')) {
                    document.querySelector('.pricemodal .card-photo').src = '/products/' + (data[3]['image'] || data[3]['image_back']);
                }
                if (document.querySelector('.pricemodal .card-title')) {
                    document.querySelector('.pricemodal .card-title').textContent = data[3]['title'];
                }
                localStorage.setItem('currentcartcolor' , color);
                if (document.querySelector('.size-selector-selected') && localStorage.getItem('currentcartsize') != null) {
                    document.querySelector('.priceBox').style.display = "block";
                    if(discount == 0){
                        document.querySelector('.modal-discount-text').style.display = "none";
                        document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
                    }
                    else{
                        document.querySelector('.modal-discount-text').style.display = "block";
                        document.querySelector('.modal-discount-text').innerHTML = "‎৳<del>" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize')) + "</del>",
                        document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPriceOffer(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
                    }
                }
                document.querySelector('.pricemodal-header').innerHTML = '<p style= "text-align:center">Price Details</p>';
                document.querySelector('.pricemodal-footer').innerHTML = '<button class="modal-btn btn btn-danger pull-left" data-dismiss="modal">Cancel</button></a>' +'<button class="discard-button modal-btn btn btn-success pull-right" onclick="moreProductAddToCart(\'' + data[3]['id'] + '\')">Add to Cart</button></a>';
                $('.pricemodal').modal('toggle');
            }
        });
    }
});

function populateSizeBoxes(color, inventory, product = null) {
    //console.log("populating size boxes");
    //console.log(color);
    //console.log(inventory);
    product_inventory = null;
    if(product) {
        product_inventory = product.product_inventory;
        if(product_inventory && product_inventory.length > 3) {
            product_inventory = JSON.parse(product_inventory);
        }
    }
    var variantInfo = pricingVariables.filter(each => each.variant == color)[0];

    if (!variantInfo) {
        //variantInfo = pricingVariables.filter(each => each.variant == "others")[0];
        variantInfo = pricingVariables[0];
    }
    variantInfo.ProductVariant = variantInfo.ProductVariant.sort((a, b) => a.id - b.id);

    var containerDom = document.querySelector(".pricemodal-body");
    containerDom.innerHTML = "";

    var ulElem1 = document.createElement("ul");
    ulElem1.className = "list-inline";
    ulElem1.style.textAlign = "center";
    containerDom.appendChild(ulElem1);

    var listElem = document.createElement("li");
    listElem.className = "list-inline-item";
    listElem.style.textAlign = "center";
    listElem.innerHTML = "<b>Choose Size</b>";
    ulElem1.appendChild(listElem);

    var ulElem2 = document.createElement("ul");
    ulElem2.className = "size-selectors-container list-inline";
    ulElem2.style.textAlign = "center";
    containerDom.appendChild(ulElem2);


    variantInfo.ProductVariant.forEach(function (size) {
            if (document.querySelector('.Premium_div')) {
                var containerDIV = document.querySelector(".Premium_div");
            } else {
                var containerDIV = document.createElement("div");
                containerDIV.className = "size-selectors-container list-inline Premium_div";

                var pTag = document.createElement('span');
                pTag.className = "Premium_pTag pull-left";
                pTag.style.lineHeight = "15px";
                pTag.style.width= "auto";
                pTag.style.fontSize= ".9rem";
                pTag.style.textAlign = "left";
                pTag.style.padding =  "5px 0px 5px 0px";
                pTag.innerHTML = "Premium Sizes: <br/>";

                var szcTag = document.createElement('button');
                szcTag.className = "Premium_pTag pull-left btn btn-link";
                szcTag.style.lineHeight = "0px";
                szcTag.style.fontSize= ".8rem";
                szcTag.innerText = "Size Chart";
                szcTag.addEventListener('click', function(){
                    loadSizeChart(variantInfo.type, 'Premium');
                });
                pTag.appendChild(szcTag);

                containerDIV.appendChild(pTag);

            }
            if(!inventory.includes('"'+size.size+'"')){
                var sizeDiv = document.createElement("div");
                if (localStorage.getItem('currentcartsize') == size.size) {
                    sizeDiv.className = "size-selector-selected list-inline-item";
                } else {
                    sizeDiv.className = "size-selector list-inline-item";
                }
                sizeDiv.addEventListener('click', sizeHandler, false);
                sizeDiv.textContent = size.size;
                sizeDiv.dataset.size = size.size;
                if(product_inventory) {
                    if(product_inventory.hasOwnProperty(size.size) && product_inventory[size.size] > 0) { 
                        containerDIV.appendChild(sizeDiv);
                    }
                } else {
                    containerDIV.appendChild(sizeDiv);
                }
            }
            ulElem2.appendChild(containerDIV);
    });



}

function sizeHandler() {

    if (document.querySelector('.size-selector-selected')) {
        document.querySelector('.size-selector-selected').className = "size-selector list-inline-item";
    }
    this.className = "size-selector-selected list-inline-item";
    localStorage.setItem('currentcartsize', this.dataset.size);
    if (document.querySelector('.color-selector-selected')) {
        document.querySelector('.priceBox').style.display = "block";
        //document.querySelector('.modal-price-text').innerText = "‎৳ " + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        if(discount == 0){
            document.querySelector('.modal-discount-text').style.display = "none";
            document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        }
        else{
            document.querySelector('.modal-discount-text').style.display = "block";
            document.querySelector('.modal-discount-text').innerHTML = "‎৳<del>" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize')) + "</del>",
            document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPriceOffer(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        }
    }
    showUnselectError();
}

function populateColorCircles(color, selectedColor) {
    var containerDom = document.querySelector(".pricemodal-body");

    var color_visibility = 1;
    if(color.length == 1 && color[0] == selectedColor) color_visibility = 0;

    var ulElem1 = document.createElement("ul");
    ulElem1.className = "list-inline";
    ulElem1.style.textAlign = "center";
    containerDom.appendChild(ulElem1);

    var listElem = document.createElement("li");
    if(!color_visibility) {
        listElem.style.display = "none";
    }
    listElem.className = "list-inline-item";
    listElem.style.textAlign = "center";
    listElem.innerHTML = "<b>Change Color</b>";
    ulElem1.appendChild(listElem);

    var ulElem2 = document.createElement("ul");
    if(!color_visibility) {
        ulElem2.style.display = "none";
    }
    ulElem2.className = "list-inline color-seletcor-container";
    ulElem2.style.textAlign = "center";
    containerDom.appendChild(ulElem2);

    color.forEach(function (elem) {
        var divElem = document.createElement("div");
        if (elem == selectedColor) {
            divElem.className = "color-selector color-selector-selected list-inline-item";
        }
        else {
            divElem.className = "color-selector list-inline-item";
        }
        divElem.addEventListener('click', colorHandler, false);
        divElem.style.backgroundColor = elem;
        divElem.style.cursor = "pointer";
        divElem.dataset.color = elem;
        ulElem2.appendChild(divElem);
        var facheck = document.createElement('i');
        if (elem == selectedColor) {
            facheck.className = "fa fa-check";
        } else {
            facheck.className = "fa fa-check hidden";
        }
        divElem.appendChild(facheck);
    });

}


function colorHandler() {

    if (document.querySelector('.color-selector-selected')) {
        document.querySelector('.color-selector-selected').querySelector('i').className = "fa fa-check hidden";
        document.querySelector('.color-selector-selected').className = "color-selector list-inline-item";
    }
    this.className = "color-selector color-selector-selected list-inline-item";
    this.querySelector('i').className = "fa fa-check fa-check-selected";

    localStorage.setItem('currentcartcolor', this.dataset.color);
    rePopulateSizeBoxes(this.dataset.color);
    if (document.querySelector('.size-selector-selected')) {
        document.querySelector('.priceBox').style.display = "block";
        //document.querySelector('.modal-price-text').innerText = "‎৳ " + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        if(discount == 0){
            document.querySelector('.modal-discount-text').style.display = "none";
            document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        }
        else{
            document.querySelector('.modal-discount-text').style.display = "block";
            document.querySelector('.modal-discount-text').innerHTML = "‎৳<del>" + calculateDynamicPrice(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize')) + "</del>",
            document.querySelector('.modal-price-text').innerText = "‎৳" + calculateDynamicPriceOffer(localStorage.getItem('currentcartcolor'), localStorage.getItem('currentcartsize'));
        }
    }
    else{
        document.querySelector('.priceBox').style.display = "none";
    }
    document.querySelector('.card-photo').style.background = this.dataset.color;
    showUnselectError();
}


function populatePriceBox() {
    var containerDom = document.querySelector(".pricemodal-body");

    var ulElem = document.createElement("ul");
    ulElem.className = "list-inline priceBox";
    ulElem.style.textAlign = "center";
    ulElem.style.display = 'none';
    containerDom.appendChild(ulElem);


    var listElem = document.createElement("li");
    listElem.className = "list-inline-item";
    listElem.style.textAlign = "center";
    listElem.innerHTML = "<b>Unit Price:</b>";
    ulElem.appendChild(listElem);

    var pHidElem = document.createElement("p");
    pHidElem.className = "modal-discount-text";
    ulElem.appendChild(pHidElem);

    var pElem = document.createElement("p");
    pElem.className = "modal-price-text";
    ulElem.appendChild(pElem);
}

function calculateDynamicPrice (color, size) {
    pricingVariables.forEach(function (type) {
        if (type.variant == color) {
            type.ProductVariant.forEach(function (e) {
                if (e.size == size) {
                    calculatedPrice = parseInt(((parseInt(localStorage.getItem('currentcartinkCost')) * parseFloat(type.type_multiplier)) * parseFloat(e.size_multiplier)) + (parseInt(e.price) + parseInt(type.design_multiplier)));
                }
            });
        }
    });
    return Math.floor(calculatedPrice/1)*1 + parseInt(localStorage.getItem('cartaddedPrice'));
}
function calculateDynamicPriceOffer(color, size) {
    pricingVariables.forEach(function (type) {
        if (type.variant == color) {
            type.ProductVariant.forEach(function (e) {
                if (e.size == size) {
                    calculatedPrice = parseInt(((parseInt(localStorage.getItem('currentcartinkCost')) * parseFloat(type.type_multiplier)) * parseFloat(e.size_multiplier)) + (parseInt(e.price) + parseInt(type.design_multiplier)));
                    calculatedPrice = Math.floor(calculatedPrice/1)*1 + parseInt(localStorage.getItem('cartaddedPrice'));
                    calculatedPrice = calculatedPrice - Math.floor(calculatedPrice * discount/100);
                }
            });
        }
    });
    return calculatedPrice;
}
function rePopulateSizeBoxes(color) {
    var variantInfo = pricingVariables.filter(each => each.variant == color)[0];

    if (!variantInfo) {
        variantInfo = pricingVariables[0];
    }
    variantInfo.ProductVariant = variantInfo.ProductVariant.sort((a, b) => a.id - b.id);


    sizeSelectorDOM = document.querySelector('.size-selectors-container');
    sizeSelectorDOM.innerHTML = "";

    if(variantInfo.ProductVariant.length == 0) sizeSelectorDOM.innerHTML = "Sorry ! No sizes available at the moment.<br />";
    else{
        variantInfo.ProductVariant.forEach(function (size) {
            if (!size.size.includes(' ')) {
                if (document.querySelector('.Premium_div')) {
                    var containerDIV = document.querySelector(".Premium_div");
                } else {
                    var containerDIV = document.createElement("div");
                    containerDIV.className = "size-selectors-container list-inline Premium_div";

                    var pTag = document.createElement('span');
                    pTag.className = "Premium_pTag pull-left";
                    pTag.style.lineHeight = "15px";
                    pTag.style.width= "auto";
                    pTag.style.fontSize= ".9rem";
                    pTag.style.padding =  "5px 0px 5px 0px";
                    pTag.innerHTML =  "Premium Sizes: <br/>";

                    var szcTag = document.createElement('button');
                    szcTag.className = "Premium_pTag pull-left btn btn-link";
                    szcTag.style.lineHeight = "0px";
                    szcTag.style.fontSize= ".8rem";
                    szcTag.innerText = "Size Chart";
                    szcTag.addEventListener('click', function(){
                        loadSizeChart(variantInfo.type, 'Premium');
                    });
                    pTag.appendChild(szcTag);

                    containerDIV.appendChild(pTag);

                }

                var sizeDiv = document.createElement("div");
                if (localStorage.getItem('currentcartsize') == size.size) {
                    sizeDiv.className = "size-selector-selected list-inline-item";
                } else {
                    sizeDiv.className = "size-selector list-inline-item";
                }
                sizeDiv.addEventListener('click', sizeHandler, false);
                sizeDiv.textContent = size.size;
                sizeDiv.dataset.size = size.size;
                containerDIV.appendChild(sizeDiv);


                sizeSelectorDOM.appendChild(containerDIV);
            } else {

                thisType = size.size.split(' ')[0];
                thisContainerDiv = thisType + '_div';
                thisTag = thisType + '_pTag';

                if (document.querySelector('.' + thisContainerDiv)) {
                    containerDIV = document.querySelector('.' + thisContainerDiv);
                } else {
                    var containerDIV = document.createElement("div");
                    containerDIV.className = "size-selectors-container list-inline " + thisContainerDiv;

                    var pTag = document.createElement('span');
                    pTag.className = thisTag + " pull-left";
                    pTag.style.lineHeight = "15px";
                    pTag.style.width= "auto";
                    pTag.style.fontSize= ".9rem";
                    pTag.style.padding =  "5px 0px 5px 0px";
                    pTag.innerHTML = thisType + " Sizes: <br/>";

                    var szcTag = document.createElement('button');
                    szcTag.className = "Premium_pTag pull-left btn btn-link";
                    szcTag.style.lineHeight = "0px";
                    szcTag.style.fontSize= ".8rem";
                    szcTag.innerText = "Size Chart";
                    szcTag.addEventListener('click', function(){
                        loadSizeChart(variantInfo.type, thisType);
                    });
                    pTag.appendChild(szcTag);

                    containerDIV.appendChild(pTag);

                }

                var sizeDiv = document.createElement("div");
                if (localStorage.getItem('currentcartsize') == size.size) {
                    sizeDiv.className = "size-selector-selected list-inline-item";
                } else {
                    sizeDiv.className = "size-selector list-inline-item";
                }
                sizeDiv.addEventListener('click', sizeHandler, false);
                sizeDiv.textContent = size.size.split(' ')[1];
                sizeDiv.dataset.size = size.size;
                containerDIV.appendChild(sizeDiv);


                sizeSelectorDOM.appendChild(containerDIV);
            }
        });
    }
}
function moreProductAddToCart(id){
    if (document.querySelector('.color-selector-selected') && document.querySelector('.size-selector-selected')) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/add-more-from-cart',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                color: localStorage.getItem('currentcartcolor').slice(1,7),
                size: localStorage.getItem('currentcartsize'),
                id : id
            },
            success: function (response) {
                if (response.result == "success") {
                    // Sync cart badge with server's total quantity
                    if (typeof refreshGcart === 'function') {
                        refreshGcart();
                    } else {
                        updateCartBadge();
                    }

                    // Generate event ID for deduplication
                    const addToCartEventId = generateEventId();

                    // Facebook Pixel - AddToCart Event
                    fbq('track', 'AddToCart', {
                        content_ids: [id],
                        content_type: 'product',
                        value: response.price || 0,
                        currency: 'BDT',
                    }, { eventID: addToCartEventId });

                    // GA4 and TikTok tracking
                    if(response.hasOwnProperty("g4a")) {
                        // Google Analytics 4
                        gtag("event", "add_to_cart", {
                            currency: "BDT",
                            value: Number(response.g4a.price).toFixed(2),
                            items: [response.g4a]
                        });

                        // TikTok Pixel
                        ttq.track('AddToCart', {
                            "contents": [{ "content_id": id, "content_type": "product" }],
                            "value": response.g4a.price,
                            "currency": "BDT"
                        });
                    }

                    // Facebook Conversion API - with complete user_data
                    var fbData = initializeFacebookData();
                    var capiPromise = $.ajax({
                        url: '/api/track-event',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            event_name: 'AddToCart',
                            event_id: addToCartEventId,
                            event_time: Math.floor(Date.now() / 1000),
                            user_data: {
                                em: window.fbCapiConfig ? window.fbCapiConfig.userEmail : null,
                                ph: window.fbCapiConfig ? window.fbCapiConfig.userPhone : null,
                                client_ip_address: window.fbCapiConfig ? window.fbCapiConfig.clientIp : null,
                                client_user_agent: navigator.userAgent,
                                fbc: fbData.fbc,
                                fbp: fbData.fbp,
                                external_id: fbData.external_id,
                            },
                            custom_data: {
                                content_ids: [id],
                                content_type: 'product',
                                value: response.price || 0,
                                currency: 'BDT',
                            }
                        })
                    });

                    // Wait for CAPI to complete (max 2 seconds) then redirect
                    var redirectTimeout = setTimeout(function() {
                        window.location.href = "/cart";
                    }, 2000);

                    capiPromise.always(function() {
                        clearTimeout(redirectTimeout);
                        window.location.href = "/cart";
                    });

                    return; // Exit early - redirect will happen after CAPI
                }
                // Show swal only for non-success responses
                swal({
                    title: 'Cart Message',
                    text: response.msg,
                    type: response.result,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#28a745',
                    confirmButtonText: 'View Cart',
                    cancelButtonText: 'Add One More'
                });

            }
        });
    } else {
        showUnselectError();
    }
}


function addToCartGallery(id){
    if (document.querySelector('.color-selector-selected') && document.querySelector('.size-selector-selected')) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        document.querySelector('.addToCartBtn > i').style.display = "inline-block";
        $.ajax({
            url: '/add-to-cart',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                color: localStorage.getItem('currentcartcolor').slice(1,7),
                size: localStorage.getItem('currentcartsize'),
                id : id
            },
            success: function (response) {
                if (response.result == "success") {
                    // Generate event ID for deduplication
                    const addToCartEventId = generateEventId();

                    // Facebook Pixel - AddToCart Event (with event_id)
                    fbq('track', 'AddToCart', {
                        content_ids: [id],
                        content_type: 'product',
                        value: response.price,
                        currency: 'BDT',
                    }, { eventID: addToCartEventId });

                    toastr["success"]("Added To Cart");
                    var photo = $(".card-photo");
                    // On mobile, target the bottom nav cart icon instead of hidden side drawer cart
                    var flyTarget;
                    if (window.innerWidth < 768 && $(".cart-nav-item .cart-icon-wrapper").length > 0) {
                        flyTarget = $(".cart-nav-item .cart-icon-wrapper");
                    } else {
                        flyTarget = $(".shopping-cart");
                    }
                    if(flyTarget.length > 0) flyToElement(photo, flyTarget);

                    // GA4 and TikTok tracking
                    if(response.hasOwnProperty("g4a")) {
                        response.g4a.item_list_id = 'search_page';
                        product_value = response.g4a.price * response.g4a.quantity;
                        response.g4a.price = Number(response.g4a.price).toFixed(2);

                        // Google Analytics 4
                        gtag("event", "add_to_cart", {
                            currency: "BDT",
                            value: Number(product_value).toFixed(2),
                            items: [response.g4a]
                        });

                        // TikTok Pixel
                        ttq.track('AddToCart', {
                            "contents": [
                                {
                                    "content_id": id,
                                    "content_type": "product",
                                    "content_name": response.g4a.item_name
                                }
                            ],
                            "value": response.g4a.price,
                            "currency": "BDT"
                        });
                    }

                    // Facebook Conversion API - AddToCart Event
                    var fbData = initializeFacebookData();
                    const conversionData = {
                        event_name: 'AddToCart',
                        event_id: addToCartEventId,
                        event_time: Math.floor(Date.now() / 1000),
                        user_data: {
                            client_ip_address: response.g4a ? response.g4a.ip : null,
                            client_user_agent: navigator.userAgent,
                            fbc: fbData.fbc,
                            fbp: fbData.fbp,
                            external_id: fbData.external_id,
                        },
                        custom_data: {
                            content_ids: [id],
                            content_type: 'product',
                            value: response.price,
                            currency: 'BDT',
                        }
                    };

                    $.ajax({
                        url: '/api/track-event',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(conversionData)
                    });
                }
                if (response.result == "error") {
                    toastr["error"](response.msg);
                }
                if($(".shopping-cart").length > 0) {
                    refreshGcart();
                    if(document.querySelector('.addToCartBtn > i')) {
                        document.querySelector('.addToCartBtn > i').style.display = "none";
                    }
                } else {
                    if(window.location.href.includes("/cart")) {
                        setTimeout(function () {
                            window.location.reload(false);
                            document.querySelector('.addToCartBtn > i').style.display = "none";
                        }, 1000);
                    } else {
                        window.location.reload(false);
                        document.querySelector('.addToCartBtn > i').style.display = "none";
                    }
                }
            }
        });
    } else {
        showUnselectError();
    }
}

function flyToElement(flyer, flyingTo) {
    var $func = $(this);
    var divider = 3;
    var flyerClone = $(flyer).clone();
    $(flyerClone).css({position: 'absolute', top: $(flyer).offset().top + "px", maxWidth: "150px",  width: $(flyer).width(), height: $(flyer).width(), left: $(flyer).offset().left + "px", opacity: 1, 'z-index': 10060});
    $('body').append($(flyerClone));
    var gotoX = $(flyingTo).offset().left + ($(flyingTo).width() / 2) - ($(flyer).width()/divider)/2;
    var gotoY = $(flyingTo).offset().top + ($(flyingTo).height() / 2) - ($(flyer).height()/divider)/2;

    $(flyerClone).animate({
        opacity: 0.4,
        left: gotoX,
        top: gotoY,
        width: $(flyer).width()/divider,
        height: $(flyer).height()/divider
    }, 700,
    function () {
        $(flyingTo).fadeOut('fast', function () {
            $(flyingTo).fadeIn('fast', function () {
                // Sync cart badge with server's total quantity
                if (typeof refreshGcart === 'function') {
                    refreshGcart();
                } else {
                    updateCartBadge();
                }
                $(flyerClone).fadeOut('fast', function () {
                    $(flyerClone).remove();
                });
            });
        });
    });
}

function showUnselectError(){
    showAddToCartBtn = true;
    if (document.querySelector('.color-selector-selected')) {
        document.querySelector('ul.list-inline.color-seletcor-container').classList.remove("error");
    } else {
        document.querySelector('ul.list-inline.color-seletcor-container').classList.add("error");
        showAddToCartBtn = false;
    }

    if (document.querySelector('.size-selector-selected')){
        document.querySelector('ul.size-selectors-container.list-inline').classList.remove("error");
    } else {
        document.querySelector('ul.size-selectors-container.list-inline').classList.add("error");
        showAddToCartBtn = false;
    }

    if(showAddToCartBtn == false) {
        document.querySelector('button.discard-button.modal-btn.btn.btn-success.pull-right').disabled = true;
    } else {
        document.querySelector('button.discard-button.modal-btn.btn.btn-success.pull-right').disabled = false;
    }
}

function updadeCartBadge() {
    // Use refreshGcart if available, otherwise use updateCartBadge
    if (typeof refreshGcart === 'function') {
        refreshGcart();
    } else {
        updateCartBadge();
    }
}

function loadSizeChart(type, blend) {
    var form = new FormData();

    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/request-size-chart",
        "method": "GET",
        "headers": {
            "cache-control": "no-cache",
        },
        "data": {
            type: type,
            blend: blend
        }
    }

    $.ajax(settings).done(function (response) {

        document.querySelector(".szc-tbody").innerHTML = "";

        response.forEach(function(size){
            var tr = document.createElement('tr');

            var szName = document.createElement('td');
            szName.className = "text-center";
            szName.textContent = size.size;
            tr.appendChild(szName);

            var szLength = document.createElement('td');
            szLength.className = "text-center";
            if(size.length == "NA"){
                szLength.textContent = "NA";
            }
            else{
                szLength.textContent = size.length + " cm (" + parseFloat(size.length * 0.393701).toFixed(2)+ " inch)";
            }

            tr.appendChild(szLength);
            var szWidth = document.createElement('td');
            szWidth.className = "text-center";
            if(size.width == "NA"){
                szWidth.textContent = "NA";
            }
            else{
                szWidth.textContent = size.width + " cm (" + parseFloat(size.width * 0.393701).toFixed(2)+ " inch)";
            }
            tr.appendChild(szWidth);

            var szSleeve = document.createElement('td');
            szSleeve.className = "text-center";
            if(size.sleeve == "NA"){
                szSleeve.textContent = "NA";
            }
            else{
                szSleeve.textContent = size.sleeve + " cm (" + parseFloat(size.sleeve * 0.393701).toFixed(2)+ " inch)";
            }
            tr.appendChild(szSleeve);

            document.querySelector('.szc-tbody').appendChild(tr);
        });

        $('.szc').modal('toggle');

    });

}

/**
 * Populate the modal size chart from product data
 * Supports multiple size charts with tabs and cm/inch toggle
 * @param {object} product - The product object from AJAX response (data[3])
 */
function populateModalSizeChart(product) {
    var sizeChartContainer = document.querySelector('.modal-size-chart');

    // If no size chart container exists, skip
    if (!sizeChartContainer) {
        return;
    }

    // Check if product has size_chart data
    if (!product || !product.size_chart || product.size_chart === 'null' || product.size_chart === null) {
        sizeChartContainer.classList.add('empty');
        return;
    }

    try {
        var sizeCharts = JSON.parse(product.size_chart);

        // Check if valid array with at least one chart
        if (!Array.isArray(sizeCharts) || sizeCharts.length === 0) {
            sizeChartContainer.classList.add('empty');
            return;
        }

        // Filter out charts with no data
        sizeCharts = sizeCharts.filter(function(chart) {
            return chart.chart && typeof chart.chart === 'object' && Object.keys(chart.chart).length > 0;
        });

        if (sizeCharts.length === 0) {
            sizeChartContainer.classList.add('empty');
            return;
        }

        // Show the container
        sizeChartContainer.classList.remove('empty');

        // Build the HTML content
        var html = '';

        // Header with title and unit toggle
        html += '<div class="size-chart-header">';
        html += '<div class="modal-size-chart-title">Size Chart</div>';
        html += '<div class="size-chart-unit-toggle">';
        html += '<button class="unit-btn" data-unit="cm">CM</button>';
        html += '<button class="unit-btn active" data-unit="inch">INCH</button>';
        html += '</div>';
        html += '</div>';

        // If multiple charts, show tabs
        if (sizeCharts.length > 1) {
            html += '<div class="size-chart-tabs">';
            sizeCharts.forEach(function(chartData, index) {
                var tabTitle = chartData.title || ('Chart ' + (index + 1));
                // Shorten the title for tabs
                tabTitle = tabTitle.replace(/Size chart/i, '').replace(/[()]/g, '').trim();
                if (!tabTitle) tabTitle = 'Chart ' + (index + 1);
                var activeClass = index === 0 ? ' active' : '';
                html += '<button class="size-chart-tab' + activeClass + '" data-tab="' + index + '">' + tabTitle + '</button>';
            });
            html += '</div>';
        }

        // Build tables for each chart (both cm and inch versions)
        sizeCharts.forEach(function(chartData, index) {
            var originalUnit = chartData.unit_of_measurement ? chartData.unit_of_measurement.toLowerCase() : 'inch';
            var chart = chartData.chart;

            // Extract measurement keys
            var measurementKeys = [];
            for (var size in chart) {
                if (chart.hasOwnProperty(size)) {
                    var measures = chart[size];
                    for (var key in measures) {
                        if (measures.hasOwnProperty(key) && measurementKeys.indexOf(key) === -1) {
                            measurementKeys.push(key);
                        }
                    }
                }
            }

            // Prioritize common measurements
            var priorityOrder = ['chest', 'chest_round', 'length', 'waist', 'hip', 'sleeve', 'shoulder', 'neck'];
            measurementKeys.sort(function(a, b) {
                var aLower = a.toLowerCase();
                var bLower = b.toLowerCase();
                var aIdx = 999, bIdx = 999;
                priorityOrder.forEach(function(p, i) {
                    if (aLower.includes(p) && aIdx === 999) aIdx = i;
                    if (bLower.includes(p) && bIdx === 999) bIdx = i;
                });
                return aIdx - bIdx;
            });

            // Limit to first 3 measurements for compact display
            measurementKeys = measurementKeys.slice(0, 3);

            // Sort sizes
            var sizes = Object.keys(chart);
            sizes.sort(function(a, b) {
                var sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL', '4XL', '5XL'];
                var aIdx = sizeOrder.indexOf(a.toUpperCase());
                var bIdx = sizeOrder.indexOf(b.toUpperCase());
                if (aIdx !== -1 && bIdx !== -1) return aIdx - bIdx;
                if (aIdx !== -1) return -1;
                if (bIdx !== -1) return 1;
                return parseInt(a) - parseInt(b);
            });

            var displayStyle = index === 0 ? '' : ' style="display:none;"';
            html += '<div class="size-chart-panel" data-panel="' + index + '" data-original-unit="' + originalUnit + '"' + displayStyle + '>';

            // Build header row (common for both units)
            var headerHtml = '<tr><th>Size</th>';
            measurementKeys.forEach(function(key) {
                var formattedKey = key.replace(/_/g, ' ').replace(/inches$/i, '').replace(/\b\w/g, function(l) { return l.toUpperCase(); }).trim();
                if (formattedKey.toLowerCase().includes('chest') || formattedKey.toLowerCase().includes('bust')) {
                    formattedKey = 'Chest';
                } else if (formattedKey.toLowerCase().includes('length')) {
                    formattedKey = 'Length';
                } else if (formattedKey.toLowerCase().includes('waist')) {
                    formattedKey = 'Waist';
                } else if (formattedKey.toLowerCase().includes('sleeve') && formattedKey.toLowerCase().includes('length')) {
                    formattedKey = 'Sleeve';
                } else if (formattedKey.toLowerCase().includes('sleeve') && formattedKey.toLowerCase().includes('opening')) {
                    formattedKey = 'Sleeve Op.';
                } else if (formattedKey.toLowerCase().includes('neck')) {
                    formattedKey = 'Neck';
                }
                headerHtml += '<th>' + formattedKey + '</th>';
            });
            headerHtml += '</tr>';

            // CM Table (hidden by default)
            html += '<table class="modal-size-chart-table size-chart-cm" style="display:none;"><thead>' + headerHtml + '</thead><tbody>';
            sizes.forEach(function(size) {
                html += '<tr><td><strong>' + size + '</strong></td>';
                measurementKeys.forEach(function(key) {
                    var value = chart[size][key] || '-';
                    if (value !== '-') {
                        value = convertMeasurement(value, originalUnit, 'cm');
                    }
                    html += '<td>' + value + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody></table>';

            // INCH Table (shown by default)
            html += '<table class="modal-size-chart-table size-chart-inch"><thead>' + headerHtml + '</thead><tbody>';
            sizes.forEach(function(size) {
                html += '<tr><td><strong>' + size + '</strong></td>';
                measurementKeys.forEach(function(key) {
                    var value = chart[size][key] || '-';
                    if (value !== '-') {
                        value = convertMeasurement(value, originalUnit, 'inch');
                    }
                    html += '<td>' + value + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody></table>';

            html += '</div>';
        });

        sizeChartContainer.innerHTML = html;

        // Add unit toggle click handlers
        var unitBtns = sizeChartContainer.querySelectorAll('.unit-btn');
        unitBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var selectedUnit = this.getAttribute('data-unit');
                // Update active button
                unitBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                // Toggle tables visibility
                sizeChartContainer.querySelectorAll('.size-chart-cm').forEach(function(table) {
                    table.style.display = selectedUnit === 'cm' ? '' : 'none';
                });
                sizeChartContainer.querySelectorAll('.size-chart-inch').forEach(function(table) {
                    table.style.display = selectedUnit === 'inch' ? '' : 'none';
                });
            });
        });

        // Add tab click handlers
        if (sizeCharts.length > 1) {
            var tabs = sizeChartContainer.querySelectorAll('.size-chart-tab');
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var tabIndex = this.getAttribute('data-tab');
                    // Update active tab
                    tabs.forEach(function(t) { t.classList.remove('active'); });
                    this.classList.add('active');
                    // Show corresponding panel
                    var panels = sizeChartContainer.querySelectorAll('.size-chart-panel');
                    panels.forEach(function(panel) {
                        panel.style.display = panel.getAttribute('data-panel') === tabIndex ? '' : 'none';
                    });
                });
            });
        }

        // Enable the size guide toggle button since we have valid data
        var sizeGuideBtn = document.querySelector('.pricemodal .size-guide-toggle');
        if (sizeGuideBtn) {
            sizeGuideBtn.classList.remove('no-data');
        }

    } catch (e) {
        console.log('Error parsing size chart:', e);
        sizeChartContainer.classList.add('empty');
        // Hide the size guide toggle when no data
        var sizeGuideBtn = document.querySelector('.pricemodal .size-guide-toggle');
        if (sizeGuideBtn) {
            sizeGuideBtn.classList.add('no-data');
        }
    }
}

/**
 * Toggle the size guide visibility in the price modal
 */
function toggleSizeGuide() {
    var topRow = document.querySelector('.pricemodal .pricemodal-top-row');
    var modalDialog = document.querySelector('.pricemodal .modal-dialog');
    var toggleBtn = document.querySelector('.pricemodal .size-guide-toggle');

    if (!topRow || !modalDialog) return;

    // Toggle the show-size-chart class
    topRow.classList.toggle('show-size-chart');
    modalDialog.classList.toggle('expanded');

    // Toggle active state on button
    if (toggleBtn) {
        toggleBtn.classList.toggle('active');
    }
}

/**
 * Reset size guide state when modal is opened
 */
function resetSizeGuideState() {
    var topRow = document.querySelector('.pricemodal .pricemodal-top-row');
    var modalDialog = document.querySelector('.pricemodal .modal-dialog');
    var toggleBtn = document.querySelector('.pricemodal .size-guide-toggle');
    var sizeChartContainer = document.querySelector('.pricemodal .modal-size-chart');

    // Reset to hidden state
    if (topRow) topRow.classList.remove('show-size-chart');
    if (modalDialog) modalDialog.classList.remove('expanded');
    if (toggleBtn) {
        toggleBtn.classList.remove('active');
        toggleBtn.classList.add('no-data'); // Will be removed by populateModalSizeChart if data exists
    }
    if (sizeChartContainer) {
        sizeChartContainer.classList.add('empty');
        sizeChartContainer.innerHTML = '';
    }
}

/**
 * Convert measurement value between inches and cm
 * @param {string} value - The measurement value (can be a range like "24-26")
 * @param {string} fromUnit - Source unit ('inch' or 'cm')
 * @param {string} toUnit - Target unit ('inch' or 'cm')
 * @returns {string} Converted value
 */
function convertMeasurement(value, fromUnit, toUnit) {
    if (fromUnit === toUnit) return value;

    // Handle ranges like "24-26"
    if (value.toString().indexOf('-') !== -1) {
        var parts = value.toString().split('-');
        var converted = [];
        parts.forEach(function(v) {
            var num = parseFloat(v);
            if (!isNaN(num)) {
                if (fromUnit === 'inch' && toUnit === 'cm') {
                    converted.push(Math.round(num * 2.54 * 10) / 10);
                } else if (fromUnit === 'cm' && toUnit === 'inch') {
                    converted.push(Math.round(num / 2.54 * 10) / 10);
                }
            }
        });
        return converted.join('-');
    }

    var num = parseFloat(value);
    if (isNaN(num)) return value;

    if (fromUnit === 'inch' && toUnit === 'cm') {
        return Math.round(num * 2.54 * 10) / 10;
    } else if (fromUnit === 'cm' && toUnit === 'inch') {
        return Math.round(num / 2.54 * 10) / 10;
    }
    return value;
}
