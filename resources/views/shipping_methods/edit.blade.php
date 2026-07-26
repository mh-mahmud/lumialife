@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">Edit Shipping Method</h1>
            <a href="{{ route('shipping-method-list') }}" class="btn btn-sm btn-primary">Back to List</a>
        </div>
    </div>

    <div class="container-xxl py-4">
        <div class="card">
            <form action="{{ route('shipping-method-update', $shippingMethod) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body row">
                    <div class="col-md-4 fv-row mb-4">
                        <label class="form-label fw-bolder">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $shippingMethod->name) }}"
                            class="form-control form-control-sm form-control-solid" required>
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-4 fv-row mb-4">
                        <label class="form-label fw-bolder">Price <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">৳</span>
                            <input type="number" name="price" value="{{ old('price', $shippingMethod->price) }}" min="0" step="0.01"
                                class="form-control form-control-solid" required>
                        </div>
                        @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-4 fv-row mb-4">
                        <label class="form-label fw-bolder">Status</label>
                        <select name="status" class="form-select form-select-sm form-select-solid" required>
                            <option value="1" {{ old('status', (string) (int) $shippingMethod->status) === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (string) (int) $shippingMethod->status) === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Shipping</button>
                </div>
            </form>
        </div>
    </div>
@endsection
