@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Newsletter Subscribers
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">{{ $subscribers->total() }} subscribers</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle gy-4 gs-7 mb-0">
                        <thead class="bg-light">
                            <tr class="fw-bolder text-muted">
                                <th class="ps-7">SL</th>
                                <th>Email Address</th>
                                <th>Subscribed At</th>
                                <th>IP Address</th>
                                <th class="text-end pe-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscribers as $subscriber)
                                <tr>
                                    <td class="ps-7">{{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}</td>
                                    <td class="fw-bold text-dark">{{ $subscriber->email }}</td>
                                    <td>{{ $subscriber->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $subscriber->ip_address ?: '—' }}</td>
                                    <td class="text-end pe-7">
                                        <form method="POST" action="{{ route('newsletter-subscribers.destroy', $subscriber) }}"
                                            onsubmit="return confirm('Remove this subscriber?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-10 text-center text-muted">No newsletter subscribers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-6">{{ $subscribers->links() }}</div>
    </div>
@endsection
