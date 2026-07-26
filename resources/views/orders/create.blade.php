@extends('layouts.master')

@section('content')

<!-- <div class="content d-flex flex-column flex-column-fluid" id="kt_content"> -->

<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order Forms
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Description-->
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Fill up the Order form</small>
                <!--end::Description-->
            </h1>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-1">
            <!--begin::Wrapper-->
            <div class="me-4">
                <!--begin::Menu-->

                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_61484bf44d957">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Menu separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Menu separator-->
                    <!--begin::Form-->
                    <div class="px-7 py-5">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-bold">Status:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div>
                                <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_61484bf44d957" data-allow-clear="true">
                                    <option></option>
                                    <option value="1">Approved</option>
                                    <option value="2">Pending</option>
                                    <option value="2">In Process</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-bold">Member Type:</label>
                            <!--end::Label-->
                            <!--begin::Options-->
                            <div class="d-flex">
                                <!--begin::Options-->
                                <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                    <span class="form-check-label">Author</span>
                                </label>
                                <!--end::Options-->
                                <!--begin::Options-->
                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                    <span class="form-check-label">Customer</span>
                                </label>
                                <!--end::Options-->
                            </div>
                            <!--end::Options-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-bold">Notifications:</label>
                            <!--end::Label-->
                            <!--begin::Switch-->
                            <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
                                <label class="form-check-label">Enabled</label>
                            </div>
                            <!--end::Switch-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply
                            </button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Menu 1-->
                <!--end::Menu-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Button-->
            <a href="{{ route('orders-index') }}" class="btn btn-sm btn-primary" id="kt_toolbar_primary_button">Order List</a>
            <!--end::Button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->

<!--**********************************
                                Forms
                  ***********************************-->
<div class="container-xxl">
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-xxl-stretch mt-4">
                <div class="card-header bg-light bd-cyan">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">Order Create</h3>
                    </div>
                    <!--end::Card title-->
                </div>

                <!-- Card Body-->
                <div class="card-body">

                    <!-- Start Form-->

                    <form action="{{ route('orders-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Customer</label>
                                    <select name="customer_id" class="form-control form-control-sm form-control-solid">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                           
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Mobile Number</label>
                                    <input type="text" name="mobile_number" class="form-control form-control-sm form-control-solid" value="{{ old('mobile_number') }}">
                                    @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                          
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Area</label>
                                    <select name="area" class="form-control form-control-sm form-control-solid">
                                        <option value="">Select Area</option>
                                        <option value="Inside Dhaka" {{ old('area') == 'Inside Dhaka' ? 'selected' : '' }}>Inside Dhaka</option>
                                        <option value="Outside Dhaka" {{ old('area') == 'Outside Dhaka' ? 'selected' : '' }}>Outside Dhaka</option>
                                    </select>
                                    @error('area')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        
                        <div id="products-section">
                            <div class="row mt-3 product-row">
                                
                                <div class="col-md-3">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Product</label>
                                        <select name="products[0][product_id]" class="form-control form-control-sm form-control-solid product-select">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-unit-price="{{ $product->product_value }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                
                                <div class="col-md-1">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Quantity</label>
                                        <input type="number" name="products[0][quantity]" class="form-control form-control-sm form-control-solid quantity-input" value="{{ old('quantity') }}" required>
                                        @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                               
                                <div class="col-md-2">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Unit Price</label>
                                        <input type="number" name="products[0][unit_price]" class="form-control form-control-sm form-control-solid unit-price-input" value="0" readonly>
                                        @error('unit_price')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                              
                                <div class="col-md-2">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Product Color</label>
                                        <select name="products[0][product_color]" class="form-control form-control-sm form-control-solid">
                                            <option value="">Select Color</option>
                                            <option value="Red">Black</option>
                                            <option value="Red">Red</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Green">Green</option>
                                        </select>
                                    </div>
                                </div>

                               
                                <div class="col-md-2">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Product Size</label>
                                        <select name="products[0][product_size]" class="form-control form-control-sm form-control-solid">
                                            <option value="">Select Size</option>
                                            <option value="M">M</option>
                                            <option value="XL">XL</option>
                                            <option value="2XL">2XL</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark">Amount</label>
                                        <input type="text" name="total_price" class="form-control form-control-sm form-control-solid total-amount-input" value="0" readonly>
                                    </div>
                                </div>

                             
                                <div class="col-md-1">
                                    <div class="fv-row mb-3">
                                        <label class="form-label fw-bolder text-dark invisible"> action </label>
                                        <button type="button" class="btn btn-sm btn-danger remove-product-row"><i class="bi bi-trash pe-0"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                      
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-success" id="add-product-row">Add More Product</button>
                            </div>
                        </div>

                      
                        <div class="row mt-3">

                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Discount (%)</label>
                                    <input type="text" name="discount" id="discount-percentage" class="form-control form-control-sm form-control-solid" value="0">
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Subtotal</label>
                                    <input type="text" name="sub_total" id="subtotal" class="form-control form-control-sm form-control-solid" value="0" readonly>
                                </div>
                            </div>

                         
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Shipping Charge</label>
                                    <input type="text" name="shipping_charge" id="shipping-charge" class="form-control form-control-sm form-control-solid" value="0" readonly>
                                </div>
                            </div>

                           
                            <div class="col-md-4">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Total Payable</label>
                                    <input type="text" name="payable_amount" id="total-payable" class="form-control form-control-sm form-control-solid" value="0" readonly>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                        </div>
                    </form>


                    <!-- End Form-->

                </div>
                <!--End Card body-->

                <!--begin::Actions-->

                <!--end::Actions-->
            </div>
        </div>
    </div>
</div>
<!-- End Forms-->


<!-- </div> -->
<!--end::Content-->

<script>
    let productIndex = 1;

    document.getElementById('add-product-row').addEventListener('click', function() {
        const section = document.getElementById('products-section');
        const newRow = document.querySelector('.product-row').cloneNode(true);

        newRow.querySelectorAll('input, select').forEach(function(input) {
            const name = input.name.replace(/\[\d+\]/, `[${productIndex}]`);
            input.name = name;
            input.value = '';
        });

        section.appendChild(newRow);
        productIndex++;
    });

    // document.getElementById('products-section').addEventListener('click', function(e) {
    //     if (e.target.classList.contains('remove-product-row')) {
    //         e.target.closest('.product-row').remove();
    //     }
    // });

    document.getElementById('products-section').addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-product-row');
        if (removeButton) {
            removeButton.closest('.product-row').remove();
        }
    });

    document.addEventListener('input', function(event) {
        if (event.target.matches('.quantity-input') || event.target.matches('.unit-price-input')) {
            const productRow = event.target.closest('.product-row');
            const quantity = parseFloat(productRow.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(productRow.querySelector('.unit-price-input').value) || 0;

            const totalAmount = quantity * unitPrice;
            productRow.querySelector('.total-amount-input').value = totalAmount.toFixed(2);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const productsSection = document.getElementById('products-section');
        const subtotalField = document.getElementById('subtotal');
        const shippingChargeField = document.getElementById('shipping-charge');
        const totalPayableField = document.getElementById('total-payable');
        const areaDropdown = document.querySelector('select[name="area"]');
        const discountPercentageField = document.getElementById('discount-percentage');

        function calculateTotals() {
            let subtotal = 0;

            //subtotal by summing up all product amounts
            productsSection.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
                const amount = quantity * unitPrice;

                //individual product amount field
                row.querySelector('.total-amount-input').value = amount.toFixed(2);

                subtotal += amount;
            });

            subtotalField.value = subtotal.toFixed(2);

            //discount
            const discountPercentage = parseFloat(discountPercentageField.value) || 0;
            const discountAmount = (subtotal * discountPercentage) / 100;

            //shipping charge based on area
            let shippingCharge = 0;
            if (areaDropdown.value === 'Inside Dhaka') {
                shippingCharge = 60;
            } else if (areaDropdown.value === 'Outside Dhaka') {
                shippingCharge = 120;
            }
            shippingChargeField.value = shippingCharge.toFixed(2);

            //total payable amount
            const totalPayable = subtotal - discountAmount + shippingCharge;
            totalPayableField.value = totalPayable.toFixed(2);
        }

        // calculate totals when quantity, unit price, discount, or area changes
        productsSection.addEventListener('input', calculateTotals);
        discountPercentageField.addEventListener('input', calculateTotals);
        areaDropdown.addEventListener('change', calculateTotals);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const productsSection = document.getElementById('products-section');

        //unit price based on selected product
        function updateUnitPrice(event) {
            if (event.target.classList.contains('product-select')) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const unitPrice = selectedOption.getAttribute('data-unit-price') || 0;
                const row = event.target.closest('.product-row');
                const unitPriceInput = row.querySelector('.unit-price-input');

                //unit price and recalculate totals
                unitPriceInput.value = parseFloat(unitPrice).toFixed(2);
            }
        }

        //product dropdown changes
        productsSection.addEventListener('change', updateUnitPrice);
    });
</script>

@endsection