@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">Create Outlet</h1>
            <a href="{{ route('outlet-location-list') }}" class="btn btn-sm btn-light-primary">Back to List</a>
        </div>
    </div>
    <div class="container-xxl py-4">
        <div class="card">
            <form action="{{ route('outlet-location-store') }}" method="POST">
                @csrf
                <div class="card-body">@include('outlet_locations._form')</div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Outlet</button>
                </div>
            </form>
        </div>
    </div>
@endsection
