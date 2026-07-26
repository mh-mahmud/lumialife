@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">Outlet Locations</h1>
            <a href="{{ route('outlet-location-create') }}" class="btn btn-sm btn-primary">Create Outlet</a>
        </div>
    </div>

    <div class="container-xxl py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-6">
            <div class="card-header"><h3 class="card-title">Outlet Page Banner</h3></div>
            <form action="{{ route('outlet-location-banner') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row align-items-end g-5">
                    <div class="col-lg-5">
                        <label class="form-label fw-bolder">Banner Image</label>
                        <input type="file" name="banner_image" accept="image/jpeg,image/png,image/webp"
                            class="form-control form-control-solid" required>
                        <div class="form-text">JPG, PNG or WEBP; maximum 5MB.</div>
                        @error('banner_image')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-lg-5">
                        <div class="border rounded overflow-hidden" style="height:110px;background:#f5f5f5">
                            <img src="{{ $pageSetting?->bannerUrl() ?? asset('feb/image-gallery/outletbanner.jpg') }}"
                                alt="Current outlet banner" style="width:100%;height:100%;object-fit:cover">
                        </div>
                    </div>
                    <div class="col-lg-2 text-lg-end">
                        <button type="submit" class="btn btn-primary">Update Banner</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Outlet List</h3></div>
            <div class="card-body p-1">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle gy-4 mb-0">
                        <thead class="bg-light">
                            <tr class="fw-bolder text-muted">
                                <th class="ps-4">SL</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Hotline</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outlets as $outlet)
                                <tr>
                                    <td class="ps-4">{{ ($outlets->currentPage() - 1) * $outlets->perPage() + $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $outlet->location_name }}</td>
                                    <td style="min-width:260px">{{ \Illuminate\Support\Str::limit($outlet->address, 85) }}</td>
                                    <td>{{ $outlet->hotline }}</td>
                                    <td>{{ $outlet->sort_order }}</td>
                                    <td><span class="badge {{ $outlet->status ? 'badge-light-success' : 'badge-light-danger' }}">{{ $outlet->status ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-end pe-4 text-nowrap">
                                        <a href="{{ $outlet->map_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-light">Map</a>
                                        <a href="{{ route('outlet-location-edit', $outlet) }}" class="btn btn-sm btn-light-primary">Edit</a>
                                        <form action="{{ route('outlet-location-destroy', $outlet) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this outlet?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-7 text-muted">No outlet found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($outlets->hasPages())<div class="card-footer">{{ $outlets->links() }}</div>@endif
        </div>
    </div>
@endsection
