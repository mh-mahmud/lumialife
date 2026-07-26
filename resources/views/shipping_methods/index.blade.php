@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Shipping Methods</h1>
            </div>
        </div>
    </div>

    <div class="container-xxl py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Shipping Method</h3>
                    </div>
                    <form action="{{ route('shipping-method-store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="fv-row mb-4">
                                <label class="form-label fw-bolder">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm form-control-solid" placeholder="e.g. Inside Dhaka" required>
                                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fw-bolder">Price <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01"
                                        class="form-control form-control-solid" required>
                                </div>
                                @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="fv-row">
                                <label class="form-label fw-bolder">Status</label>
                                <select name="status" class="form-select form-select-sm form-select-solid" required>
                                    <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Create Shipping</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shipping Method List</h3>
                    </div>
                    <div class="card-body p-1">
                        <div class="table-responsive">
                            <table class="table table-sm table-row-bordered align-middle gy-3 mb-0">
                                <thead class="bg-light">
                                    <tr class="fw-bolder text-muted">
                                        <th class="ps-4">SL</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shippingMethods as $shippingMethod)
                                        <tr>
                                            <td class="ps-4">{{ ($shippingMethods->currentPage() - 1) * $shippingMethods->perPage() + $loop->iteration }}</td>
                                            <td class="fw-bold">{{ $shippingMethod->name }}</td>
                                            <td>৳{{ number_format($shippingMethod->price, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $shippingMethod->status ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $shippingMethod->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('shipping-method-edit', $shippingMethod) }}" class="btn btn-sm btn-light-primary">Edit</a>
                                                <form action="{{ route('shipping-method-destroy', $shippingMethod) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete this shipping method?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-6 text-muted">No shipping method found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($shippingMethods->hasPages())
                        <div class="card-footer">{{ $shippingMethods->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
