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
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Product Edit Forms
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Description-->
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Fill up the Product Edit form</small>
                <!--end::Description-->
            </h1>
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
                <!-- <div class="card card-xxl-stretch"> -->
                <div class="card-header bg-light bd-cyan">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">Product Edit</h3>
                    </div>
                    <!--end::Card title-->
                </div>

                <!-- Card Body-->
                <div class="card-body">

                    <!-- Start Form-->

                    <form class="g-form w-100" action="{{ route('product-update-pro', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <!--begin::Label-->
                                    <label class="form-label fw-bolder text-dark">product Name</label>
                                    <input class="form-control form-control-sm form-control-solid" type="text" name="name" value="{{ old('name', $product->name) }}" autocomplete="off" />
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
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-sm form-control-solid"
                                           type="text" name="product_code" autocomplete="off" value="{{ $product->product_code }}" />
                                    <!--end::Input-->
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
                                            <option value="{{ $val->id }}" {{ $product->category_id == $val->id ? 'selected' : '' }}>{{ $val->category_name }}</option>
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
                                    <label class="form-label fw-bolder text-dark">Brand Name</label>
                                    <select class="form-control form-control-sm form-control-solid" name="brand_id" aria-label="Default select example">
                                        <option value=''>Select</option>
                                        @foreach ($brands as $key => $val)
                                            <option value="{{ $val->id }}" {{ $product->brand_id == $val->id ? 'selected' : '' }}>{{ $val->brand_name }}</option>
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
                                    @php($selectedColors = old('color_ids', $product->productColors->pluck('id')->all()))
                                    <select name="color_ids[]" multiple data-control="select2"
                                        data-placeholder="Select colors" data-close-on-select="false"
                                        class="form-select form-select-sm form-select-solid">
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ in_array($color->id, $selectedColors) ? 'selected' : '' }}>
                                                {{ $color->name }}{{ $color->hex_code ? ' (' . $color->hex_code . ')' : '' }}{{ !$color->status ? ' [Inactive]' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_ids.*')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Available Sizes</label>
                                    @php($selectedSizes = old('size_ids', $product->productSizes->pluck('id')->all()))
                                    <select name="size_ids[]" multiple data-control="select2"
                                        data-placeholder="Select sizes" data-close-on-select="false"
                                        class="form-select form-select-sm form-select-solid">
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ in_array($size->id, $selectedSizes) ? 'selected' : '' }}>{{ $size->name }}{{ !$size->status ? ' [Inactive]' : '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('size_ids.*')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <!--begin::Label-->
                                    <label class="form-label fw-bolder text-dark">Type of Product<span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm form-control-solid"
                                        id="assigned_to" name="product_type" aria-label="Default select example">
                                        <option value='' {{ $product->product_type === '' ? 'selected' : '' }}>Select</option>
                                        @foreach (config('constants.PRODUCT_TYPE') as $key => $type)
                                            <option value="{{ $type }}" {{ $product->product_type === (string)$type ? 'selected' : '' }}>{{ $type }}</option>
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
                                           type="text" name="product_cost" autocomplete="off" value="{{ $product->product_cost }}" />
                                    @if ($errors->has('product_cost'))
                                        <span class="text-danger">{{ $errors->first('product_cost') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Product Price<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-sm form-control-solid"
                                           type="text" name="product_value" autocomplete="off" value="{{ $product->product_value }}" />
                                    @if ($errors->has('product_value'))
                                        <span class="text-danger">{{ $errors->first('product_value') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Discount Price</label>
                                    <input class="form-control form-control-sm form-control-solid"
                                           type="text" name="discount_price" autocomplete="off" value="{{ $product->discount_price }}" />
                                    @if ($errors->has('discount_price'))
                                        <span class="text-danger">{{ $errors->first('discount_price') }}</span>
                                    @endif
                                </div>
                            </div>

                            @include('products.partials.size-chart-builder', ['chartProduct' => $product])

                            {{--
                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Club Points</label>
                                    <input class="form-control form-control-sm form-control-solid"
                                           type="text" name="club_point" autocomplete="off" value="{{ $product->club_point }}" />
                                    @if ($errors->has('club_point'))
                                        <span class="text-danger">{{ $errors->first('club_point') }}</span>
                                    @endif
                                </div>
                            </div>
                            --}}

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label  fw-bolder text-dark">product Image Upload</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path" autocomplete="off" />

                                    @if ($product->img_path)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path, 'products') }}" alt="Image" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif

                                    <!--end::Input-->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Image 2</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_2" autocomplete="off" />

                                    @if ($product->img_path_2)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path_2, 'products') }}" alt="Image 2" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Image 3</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_3" autocomplete="off" />

                                    @if ($product->img_path_3)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path_3, 'products') }}" alt="Image 3" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif
                                </div>
                            </div>

                            {{--
                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Image 4</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_4" autocomplete="off" />

                                    @if ($product->img_path_4)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path_4, 'products') }}" alt="Image 4" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Image 5</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_5" autocomplete="off" />

                                    @if ($product->img_path_5)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path_5, 'products') }}" alt="Image 5" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Image 6</label>
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="img_path_6" autocomplete="off" />

                                    @if ($product->img_path_6)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($product->img_path_6, 'products') }}" alt="Image 6" width="100px">
                                        <button type="button" class="btn btn-danger btn-sm p-2" id="delete-profile-image">
                                            <i class="fas fa-trash-alt pe-0"></i>
                                        </button>
                                    </div>
                                    @else
                                        <img alt="Logo" src="{{ asset('uploads/noimage.jpg') }}" width="100px"/>
                                    @endif
                                </div>
                            </div>
                            --}}

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Stock Status</label>
                                    <select class=" form-control form-control-sm form-control-solid" name="stock_status" aria-label="Default select example">

                                        <option value="">-- select one --</option>
                                        <option value="In Stock" {{ ($product->stock_status == 'In Stock') ? 'selected' : '' }}>In Stock</option>
                                        <option value="Out of Stock" {{ ($product->stock_status == 'Out of Stock') ? 'selected' : '' }}>Out of Stock</option>
                                        <option value="Limit Out" {{ ($product->stock_status == 'Limit Out') ? 'selected' : '' }}>Limit Out</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Stock Quantity<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-sm form-control-solid"
                                           type="number" name="stock_quantity" autocomplete="off" value="{{ $product->stock_quantity }}" />
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
                                           type="number" name="max_purchase_limit" autocomplete="off" value="{{ $product->max_purchase_limit }}" />
                                    @if ($errors->has('max_purchase_limit'))
                                        <span class="text-danger">{{ $errors->first('max_purchase_limit') }}</span>
                                    @endif
                                </div>
                            </div>
                            --}}


                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Status</label>
                                    <select class="form-control form-control-sm form-control-solid" name="status" aria-label="Default select example">
                                        <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Trending Now</label>
                                    <select class="form-control form-control-sm form-control-solid" name="is_trending">
                                        <option value="0" {{ old('is_trending', $product->is_trending) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_trending', $product->is_trending) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">LifeStyle Accessories</label>
                                    <select class="form-control form-control-sm form-control-solid" name="is_lifestyle">
                                        <option value="0" {{ old('is_lifestyle', $product->is_lifestyle) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_lifestyle', $product->is_lifestyle) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Best Deals</label>
                                    <select class="form-control form-control-sm form-control-solid" name="is_best_deal">
                                        <option value="0" {{ old('is_best_deal', $product->is_best_deal) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_best_deal', $product->is_best_deal) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>

                           <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bolder text-dark" for="textarea">Product Description<span class="text-danger">*</span></label>
                                    <textarea
                                        class="form-control form-control-sm  form-control-solid editor"
                                        id="description" name="description"
                                        rows="3">{{ $product->description }}</textarea>
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
                                        rows="3">{{ $product->product_specification }}</textarea>
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
                                        rows="3">{{ $product->key_features }}</textarea>
                                    @if ($errors->has('key_features'))
                                        <span
                                            class="text-danger">{{ $errors->first('key_features') }}</span>
                                    @endif
                                </div>
                            </div>
                            --}}


                        </div>
                        <!--End Row-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Update Changes</button>
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


    {{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#delete-profile-image').click(function() {
            if (confirm('Are you sure you want to delete your product image?')) {
                $.ajax({
                    url: '{{ route('update-product-image', $product->id) }}', // Using the correct route
                    type: 'PUT', // Correct HTTP method
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#profile-image-container').remove();
                           // alert('Profile image deleted successfully');
                        } else {
                            alert('Error deleting product image');
                        }
                    },
                    error: function() {
                        alert('Error deleting product image');
                    }
                });
            }
        });
    });
    </script>--}}

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
