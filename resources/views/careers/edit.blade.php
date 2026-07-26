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
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Career Edit Form
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Description-->
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Fill up the Career Edit Form</small>
                <!--end::Description-->
            </h1>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-1">
            <a href="{{ route('career-list') }}" class="btn btn-sm btn-primary" id="kt_toolbar_primary_button">Career List</a>
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
                        <h3 class="fw-bolder m-0">Job Post Edit</h3>
                    </div>
                    <!--end::Card title-->
                </div>

                <!-- Card Body-->
                <div class="card-body">

                    <!-- Start Form-->

                    <form class="g-form w-100" action="{{ route('career-update', $career->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $career->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <!--begin::Label-->
                                    <label class="form-label fw-bolder text-dark">Job Title</label>
                                    <input class="form-control form-control-sm form-control-solid" type="text" name="job_title" value="{{ old('job_title', $career->job_title) }}" autocomplete="off" />
                                    <!--end::Input-->
                                    @if ($errors->has('job_title'))
                                    <span class="text-danger">{{ $errors->first('job_title') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--
                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <label class="form-label fw-bolder text-dark">Category Name</label>
                                    <select class=" form-control form-control-sm form-control-solid" name="Career_category_id"
                                            aria-label="Default select example">
                                            <option value="">Select Category</option>
                                            @foreach($parents as $id => $name)
                                                <option value="{{ $id }}" {{ $career->Career_category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                    </select>
                                    @if ($errors->has('Career_category_id'))
                                        <span class="text-danger">{{ $errors->first('Career_category_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            --}}

                            <div class="col-md-6">
                                <div class="fv-row mb-3">
                                    <!--begin::Label-->
                                    <label class="form-label  fw-bolder text-dark">Job Post Image Upload</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-sm form-control-solid" type="file" name="job_image" autocomplete="off" />

                                    @if ($career->job_image)
                                    <div class="mt-3" id="profile-image-container">
                                        <img src="{{ \App\Support\MediaStorage::url($career->job_image, 'careers') }}" alt="Career Image" width="100px">
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
                                    <label class="form-label fw-bolder text-dark">Status</label>
                                    <select class="form-control form-control-sm form-control-solid" name="status" aria-label="Default select example">
                                        <option value="1" {{ $career->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $career->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label fw-bolder text-dark" for="textarea">Career Description</label>
                                    <textarea class="form-control form-control-sm editor form-control-solid" name="job_description" rows="3">{{ $career->job_description }}</textarea>
                                    @if ($errors->has('job_description'))
                                        <span class="text-danger">{{ $errors->first('job_description') }}</span>
                                    @endif
                                </div>
                            </div>


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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#delete-profile-image').click(function() {
            if (confirm('Are you sure you want to delete your Career image?')) {
                $.ajax({
                    url: '{{ route('update-career-image', $career->id) }}', // Using the correct route
                    type: 'PUT', // Correct HTTP method
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#profile-image-container').remove();
                        } else {
                            alert('Error deleting Career image');
                        }
                    },
                    error: function() {
                        alert('Error deleting Career image');
                    }
                });
            }
        });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var summernoteElement = document.querySelectorAll('.editor');         
            document.getElementById('resetButton').addEventListener('click', function() {
                $(summernoteElement).summernote('code', ''); // Clear the content of Summernote
            });
        });
    </script>

@endsection
