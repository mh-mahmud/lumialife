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
                            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">User
                                <!--begin::Separator-->
                                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                <!--end::Separator-->
                                <!--begin::Description-->
                                <small class="text-muted fs-7 fw-bold my-1 ms-1">User Permission Form</small>
                                <!--end::Description--></h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center py-1">

                            <!--begin::Button-->
                            <a href="{{ URL::to('permission-list') }}" class="btn btn-sm btn-primary" id="kt_toolbar_primary_button">Permission List</a>
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
                                <div class="card-header">
                                    <!--begin::Card title-->
                                    <div class="card-title m-0">
                                        <h3 class="fw-bolder m-0">Edit Permission</h3>
                                    </div>
                                    <!--end::Card title-->
                                </div>

                                <!-- Card Body-->
                                <div class="card-body">

                                    <!-- Start Form-->

                                    <form class="g-form w-100 permission-validation-form" action="{{ route('permission.update') }}" enctype="multipart/form-data" method="POST" novalidate>
                                         @csrf
                                        <div class="row">
                                            <input type="hidden" name="id" value="{{ $data->id }}">

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Parent ID</label>
                                                    <select class=" form-control form-control-sm form-control-solid" name="parent_id"
                                                            aria-label="Default select example">
                                                            <option value="">Select option</option>
                                                            @foreach($list as $key=>$val)
                                                                <option @if($data->parent_id==$val->id) selected  @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">
                                                        Permision Name</label>
                                                    <input class="form-control form-control-sm form-control-solid" type="text" value="{{ old('name', $data->name) }}" name="name" maxlength="191" autocomplete="off" required />
                                                    <div class="invalid-feedback">Permission name is required.</div>
                                                    @if ($errors->has('name'))
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">
                                                        Permision Slug</label>
                                                    <input class="form-control form-control-sm form-control-solid" value="{{ old('slug', $data->sub_name) }}" type="text" name="slug" maxlength="191" pattern="[A-Za-z0-9._-]+" autocomplete="off" required />
                                                    <div class="invalid-feedback">Use a valid route name containing letters, numbers, dot, dash or underscore.</div>
                                                    @if ($errors->has('slug'))
                                                        <span class="text-danger">{{ $errors->first('slug') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="form-label fw-bolder text-dark">Show in Menu</label>
                                                    <select class="form-control form-control-sm form-control-solid" name="show_in_menu"
                                                            aria-label="Show in menu" required>
                                                            <option value="">Select option</option>
                                                            <option @if($data->show_in_menu=='1') selected  @endif value="1">Yes</option>
                                                            <option @if($data->show_in_menu=='0') selected  @endif value="0">No</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select whether this permission appears in the menu.</div>
                                                </div>
                                            </div>

                                            <!-- /// -->


                                        </div>
                                        <!--End Row-->

                                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                                        <a href="{{ route('create-user') }}" class="btn btn-light btn-active-light-primary me-2">Reset</a>
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


<script src="{{ asset('assets/js/permission-role-validation.js') }}"></script>
@endsection
