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
                            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Product
                                <!--begin::Separator-->
                                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                <!--end::Separator-->
                                <!--begin::Description-->
                                <small class="text-muted fs-7 fw-bold my-1 ms-1">Fill up the Product</small>
                                <!--end::Description--></h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 py-1">
                            <a href="{{ route('product-color-list') }}" class="btn btn-sm btn-light-primary">Manage Colors</a>
                            <a href="{{ route('product-size-list') }}" class="btn btn-sm btn-light-primary">Manage Sizes</a>
                            <a href="{{ route('product-list') }}" class="btn btn-sm btn-primary" id="kt_toolbar_primary_button">Product List</a>
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
                                        <h3 class="fw-bolder m-0">Product Create</h3>
                                    </div>
                                    <!--end::Card title-->
                                </div>

                                <!-- Card Body-->
                                <div class="card-body">

                                    <!-- Start Form-->

                                    <form class="g-form w-100" action="{{ route('add-product-pro') }}"  method="POST" enctype="multipart/form-data">
                                        @csrf

                                        @if ($errors->any())
                                            <div class="alert alert-danger d-flex align-items-start mb-5" role="alert">
                                                <span class="svg-icon svg-icon-2hx svg-icon-danger me-3 mt-1">
                                                    <i class="fa fa-exclamation-circle fs-2 text-danger"></i>
                                                </span>
                                                <div>
                                                    <h5 class="mb-2 text-danger">Please correct the following errors:</h5>
                                                    <ul class="mb-0 ps-5">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger mb-5" role="alert">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <!--begin::Label-->
                                                    <label class="form-label fw-bolder text-dark">Product Name<span class="text-danger">*</span></label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="text" name="name" autocomplete="off" value="{{ old('name') }}" />
                                                    <!--end::Input-->
                                                    @if ($errors->has('name'))
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <!--begin::Label-->
                                                    <label class="form-label fw-bolder text-dark">Product Code/SKU<span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-sm form-control-solid" type="text" name="product_code" autocomplete="off" value="{{ old('product_code') }}" />
                                                    @if ($errors->has('product_code'))
                                                        <span class="text-danger">{{ $errors->first('product_code') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <!--begin::Label-->
                                                    <label class="form-label fw-bolder text-dark">Category<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm form-control-solid" name="category_id" aria-label="Default select example">
                                                        <option value=''>Select</option>
                                                        @foreach ($categories as $key => $val)
                                                            <option value="{{ $val->id }}" {{ old('category_id') === (string)$val->id ? 'selected' : '' }}>{{ $val->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('category_id'))
                                                        <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <!--begin::Label-->
                                                    <label class="form-label fw-bolder text-dark"><span class="text-danger">*</span>Brand Name</label>
                                                    <select class="form-control form-control-sm form-control-solid" name="brand_id" aria-label="Default select example">
                                                        <option value=''>Select</option>
                                                        @foreach ($brands as $key => $val)
                                                            <option value="{{ $val->id }}" {{ old('brand_id') === (string)$val->id ? 'selected' : '' }}>{{ $val->brand_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('brand_id'))
                                                        <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Available Colors</label>
                                                    <select name="color_ids[]" multiple data-control="select2"
                                                        data-placeholder="Select colors" data-close-on-select="false"
                                                        class="form-select form-select-sm form-select-solid">
                                                        @foreach($colors as $color)
                                                            <option value="{{ $color->id }}" {{ in_array($color->id, old('color_ids', [])) ? 'selected' : '' }}>
                                                                {{ $color->name }}{{ $color->hex_code ? ' (' . $color->hex_code . ')' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('color_ids.*')<span class="text-danger">{{ $message }}</span>@enderror
                                                    @if($colors->isEmpty())
                                                        <small class="text-muted">No active colors. <a href="{{ route('product-color-list') }}">Create color</a></small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Available Sizes</label>
                                                    <select name="size_ids[]" multiple data-control="select2"
                                                        data-placeholder="Select sizes" data-close-on-select="false"
                                                        class="form-select form-select-sm form-select-solid">
                                                        @foreach($sizes as $size)
                                                            <option value="{{ $size->id }}" {{ in_array($size->id, old('size_ids', [])) ? 'selected' : '' }}>{{ $size->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size_ids.*')<span class="text-danger">{{ $message }}</span>@enderror
                                                    @if($sizes->isEmpty())
                                                        <small class="text-muted">No active sizes. <a href="{{ route('product-size-list') }}">Create size</a></small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <!--begin::Label-->
                                                    <label class="form-label fw-bolder text-dark">Type of Product<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm form-control-solid"
                                                        id="assigned_to" name="product_type" aria-label="Default select example">
                                                        <option value='' {{ old('product_type', '') === '' ? 'selected' : '' }}>Select</option>
                                                        @foreach (config('constants.PRODUCT_TYPE') as $key => $type)
                                                            <option value="{{$type}}" {{ $type == 'Physical' ? 'selected' : '' }}>{{$type}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('product_type'))
                                                        <span class="text-danger">{{ $errors->first('product_type') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Product Cost</label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="text" name="product_cost" autocomplete="off" value="{{ old('product_cost') }}" />
                                                    @if ($errors->has('product_cost'))
                                                        <span class="text-danger">{{ $errors->first('product_cost') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Product Price<span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="text" name="product_value" autocomplete="off" value="{{ old('product_value') }}" />
                                                    @if ($errors->has('product_value'))
                                                        <span class="text-danger">{{ $errors->first('product_value') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Discount Price</label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="text" name="discount_price" autocomplete="off" value="{{ old('discount_price') }}" />
                                                    @if ($errors->has('discount_price'))
                                                        <span class="text-danger">{{ $errors->first('discount_price') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @include('products.partials.size-chart-builder', ['chartProduct' => null])

                                            {{--
                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Club Points</label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="text" name="club_point" autocomplete="off" value="{{ old('club_point') }}" />
                                                    @if ($errors->has('club_point'))
                                                        <span class="text-danger">{{ $errors->first('club_point') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            --}}

                                            {{--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bolder text-dark" for="textarea">How to Order</label>
                                                    <textarea class="form-control form-control-sm  form-control-solid" name="how_to_order" rows="3">{{ old('how_to_order') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bolder text-dark" for="textarea">Return Policy</label>
                                                    <textarea class="form-control form-control-sm  form-control-solid" name="return_policy" rows="3">{{ old('return_policy') }}</textarea>
                                                </div>
                                            </div>--}}

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Main Image</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 2</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_2" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 3</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_3" autocomplete="off" />
                                                </div>
                                            </div>
                                            {{--
                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 4</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_4" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 5</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_5" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 6</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_6" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 7</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_7" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Image 8</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_8" autocomplete="off" />
                                                </div>
                                            </div>--}}

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Stock Status</label>
                                                    <select class=" form-control form-control-sm form-control-solid" name="stock_status" aria-label="Default select example">

                                                        <option value="In Stock" selected>In Stock</option>
                                                        <option value="Out of Stock">Out of Stock</option>
                                                        <option value="Limit Out">Limit Out</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Stock Quantity<span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="number" name="stock_quantity" autocomplete="off" value="{{ old('stock_quantity') }}" />
                                                    @if ($errors->has('stock_quantity'))
                                                        <span class="text-danger">{{ $errors->first('stock_quantity') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--
                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Max Purchase Limit</label>
                                                    <input class="form-control form-control-sm form-control-solid"
                                                           type="number" name="max_purchase_limit" autocomplete="off" value="{{ old('max_purchase_limit') }}" />
                                                    @if ($errors->has('max_purchase_limit'))
                                                        <span class="text-danger">{{ $errors->first('max_purchase_limit') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            --}}

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Status</label>
                                                    <select class=" form-control form-control-sm form-control-solid" name="status"
                                                            aria-label="Default select example">

                                                        <option value="1" selected>Active</option>
                                                        <option value="0">Inactive</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Trending Now</label>
                                                    <select class="form-control form-control-sm form-control-solid" name="is_trending">
                                                        <option value="0" {{ old('is_trending', '0') === '0' ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ old('is_trending') === '1' ? 'selected' : '' }}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">LifeStyle Accessories</label>
                                                    <select class="form-control form-control-sm form-control-solid" name="is_lifestyle">
                                                        <option value="0" {{ old('is_lifestyle', '0') === '0' ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ old('is_lifestyle') === '1' ? 'selected' : '' }}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Best Deals</label>
                                                    <select class="form-control form-control-sm form-control-solid" name="is_best_deal">
                                                        <option value="0" {{ old('is_best_deal', '0') === '0' ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ old('is_best_deal') === '1' ? 'selected' : '' }}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>

                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bolder text-dark" for="textarea">Product Description<span class="text-danger">*</span></label>
                                                    <textarea
                                                        class="form-control form-control-sm  form-control-solid editor"
                                                        id="description" name="description"
                                                        rows="3">{{ old('description') }}</textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bolder text-dark" for="textarea">Product Specification</label>
                                                    <textarea
                                                        class="form-control form-control-sm  form-control-solid editor"
                                                        id="product_specification" name="product_specification"
                                                        rows="3">{{ old('product_specification') }}</textarea>
                                                    @if ($errors->has('product_specification'))
                                                        <span
                                                            class="text-danger">{{ $errors->first('product_specification') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bolder text-dark" for="textarea">Key Features</label>
                                                    <textarea
                                                        class="form-control form-control-sm  form-control-solid editor"
                                                        id="key_features" name="key_features"
                                                        rows="3">{{ old('key_features') }}</textarea>
                                                    @if ($errors->has('key_features'))
                                                        <span
                                                            class="text-danger">{{ $errors->first('key_features') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            --}}
                                        </div>

                                      <div class="card-footer d-flex justify-content-end py-6 px-9">
                                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Submit</button>
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


@endsection
@section('endScript')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var summernoteElement = document.querySelectorAll('.editor');         
            document.getElementById('resetButton').addEventListener('click', function() {
                $(summernoteElement).summernote('code', ''); // Clear the content of Summernote
            });
        });
    </script>
@endsection
