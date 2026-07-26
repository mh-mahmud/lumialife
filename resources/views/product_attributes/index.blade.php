@extends('layouts.master')

@section('content')
    @php
        $isColor = $type === 'color';
        $singular = $isColor ? 'Color' : 'Size';
        $storeRoute = $isColor ? 'product-color-store' : 'product-size-store';
        $editRoute = $isColor ? 'product-color-edit' : 'product-size-edit';
        $destroyRoute = $isColor ? 'product-color-destroy' : 'product-size-destroy';
    @endphp

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $title }}</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('product-color-list') }}" class="btn btn-sm {{ $isColor ? 'btn-primary' : 'btn-light-primary' }}">Colors</a>
                <a href="{{ route('product-size-list') }}" class="btn btn-sm {{ !$isColor ? 'btn-primary' : 'btn-light-primary' }}">Sizes</a>
                <a href="{{ route('product-list') }}" class="btn btn-sm btn-light">Products</a>
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
                        <h3 class="card-title">Create {{ $singular }}</h3>
                    </div>
                    <form action="{{ route($storeRoute) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="fv-row mb-4">
                                <label class="form-label fw-bolder">{{ $singular }} Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm form-control-solid" placeholder="{{ $isColor ? 'e.g. Navy Blue' : 'e.g. XL' }}">
                                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            @if($isColor)
                                <div class="fv-row mb-4">
                                    <label class="form-label fw-bolder">Color Code</label>
                                    <div class="d-flex gap-2">
                                        <input type="color" id="color_picker" value="{{ old('hex_code', '#000000') }}"
                                            class="form-control form-control-color">
                                        <input type="text" id="hex_code" name="hex_code" value="{{ old('hex_code') }}"
                                            class="form-control form-control-sm form-control-solid" placeholder="#000000">
                                    </div>
                                    @error('hex_code')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            @else
                                <div class="fv-row mb-4">
                                    <label class="form-label fw-bolder">Sort Order</label>
                                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}"
                                        class="form-control form-control-sm form-control-solid">
                                    @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            @endif

                            <div class="fv-row">
                                <label class="form-label fw-bolder">Status</label>
                                <select name="status" class="form-select form-select-sm form-select-solid">
                                    <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Create {{ $singular }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }} List</h3>
                    </div>
                    <div class="card-body p-1">
                        <div class="table-responsive">
                            <table class="table table-sm table-row-bordered align-middle gy-3 mb-0">
                                <thead class="bg-light">
                                    <tr class="fw-bolder text-muted">
                                        <th class="ps-4">SL</th>
                                        <th>Name</th>
                                        <th>{{ $isColor ? 'Preview / Code' : 'Sort Order' }}</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td class="ps-4">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                            <td class="fw-bold">{{ $item->name }}</td>
                                            <td>
                                                @if($isColor)
                                                    @if($item->hex_code)
                                                        <span class="d-inline-block rounded border me-2 align-middle" style="width:24px;height:24px;background:{{ $item->hex_code }}"></span>
                                                        {{ $item->hex_code }}
                                                    @else
                                                        —
                                                    @endif
                                                @else
                                                    {{ $item->sort_order }}
                                                @endif
                                            </td>
                                            <td>{{ $item->products_count }}</td>
                                            <td><span class="badge {{ $item->status ? 'badge-light-success' : 'badge-light-danger' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span></td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route($editRoute, $item->id) }}" class="btn btn-sm btn-light-primary">Edit</a>
                                                <form action="{{ route($destroyRoute, $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this {{ strtolower($singular) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-6 text-muted">No {{ strtolower($title) }} found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($items->hasPages())
                        <div class="card-footer">{{ $items->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@if($type === 'color')
    @section('endScript')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const picker = document.getElementById('color_picker');
                const input = document.getElementById('hex_code');
                picker.addEventListener('input', function () { input.value = picker.value.toUpperCase(); });
                input.addEventListener('input', function () {
                    if (/^#[0-9A-Fa-f]{6}$/.test(input.value)) picker.value = input.value;
                });
            });
        </script>
    @endsection
@endif
