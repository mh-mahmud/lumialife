@extends('layouts.master')

@section('content')
    @php
        $isColor = $type === 'color';
        $listRoute = $isColor ? 'product-color-list' : 'product-size-list';
        $updateRoute = $isColor ? 'product-color-update' : 'product-size-update';
    @endphp

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">{{ $title }}</h1>
            <a href="{{ route($listRoute) }}" class="btn btn-sm btn-primary">Back to List</a>
        </div>
    </div>

    <div class="container-xxl py-4">
        <div class="card">
            <form action="{{ route($updateRoute, $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body row">
                    <div class="col-md-6 fv-row mb-4">
                        <label class="form-label fw-bolder">{{ $isColor ? 'Color' : 'Size' }} Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" class="form-control form-control-sm form-control-solid">
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    @if($isColor)
                        <div class="col-md-6 fv-row mb-4">
                            <label class="form-label fw-bolder">Color Code</label>
                            <div class="d-flex gap-2">
                                <input type="color" id="color_picker" value="{{ old('hex_code', $item->hex_code ?: '#000000') }}" class="form-control form-control-color">
                                <input type="text" id="hex_code" name="hex_code" value="{{ old('hex_code', $item->hex_code) }}" class="form-control form-control-sm form-control-solid">
                            </div>
                            @error('hex_code')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    @else
                        <div class="col-md-6 fv-row mb-4">
                            <label class="form-label fw-bolder">Sort Order</label>
                            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" class="form-control form-control-sm form-control-solid">
                            @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    @endif

                    <div class="col-md-6 fv-row">
                        <label class="form-label fw-bolder">Status</label>
                        <select name="status" class="form-select form-select-sm form-select-solid">
                            <option value="1" {{ old('status', (string) (int) $item->status) === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (string) (int) $item->status) === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
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
