@extends('layouts.master')

@section('content')
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">FAQ Management
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Website questions & answers</small>
            </h1>
        </div>
        <a href="{{ route('faq') }}" target="_blank" class="btn btn-sm btn-light-primary">View FAQ Page</a>
    </div>
</div>

<div class="container-fluid py-6">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-6">{{ session('success') }}</div>
    @endif

    <div class="row g-6">
        <div class="col-xl-4">
            <div class="card shadow-sm position-sticky" style="top: 90px">
                <div class="card-header"><h3 class="card-title">{{ $editingFaq ? 'Edit FAQ' : 'Add New FAQ' }}</h3></div>
                <form method="POST" action="{{ $editingFaq ? route('admin-faqs.update', $editingFaq) : route('admin-faqs.store') }}">
                    @csrf
                    @if($editingFaq) @method('PUT') @endif
                    <div class="card-body">
                        <div class="mb-5">
                            <label class="form-label required fw-bold">Question</label>
                            <textarea name="question" rows="3" class="form-control form-control-solid @error('question') is-invalid @enderror" placeholder="Enter the question" required>{{ old('question', $editingFaq?->question) }}</textarea>
                            @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label required fw-bold">Answer</label>
                            <textarea name="answer" rows="8" class="form-control form-control-solid @error('answer') is-invalid @enderror" placeholder="Enter the answer" required>{{ old('answer', $editingFaq?->answer) }}</textarea>
                            @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Basic HTML formatting is supported.</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-bold">Display Order</label>
                            <input name="sort_order" type="number" min="0" max="99999" class="form-control form-control-solid" value="{{ old('sort_order', $editingFaq?->sort_order ?? 0) }}">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingFaq?->is_active ?? true))>
                            <span class="form-check-label fw-bold">Show on website</span>
                        </label>
                    </div>
                    <div class="card-footer d-flex gap-3">
                        <button type="submit" class="btn btn-primary flex-grow-1">{{ $editingFaq ? 'Update FAQ' : 'Save FAQ' }}</button>
                        @if($editingFaq)<a href="{{ route('admin-faqs.index') }}" class="btn btn-light">Cancel</a>@endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="card-title">All FAQs</h3><div class="card-toolbar"><span class="badge badge-light-primary">{{ $faqs->total() }} total</span></div></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered mb-0">
                            <thead><tr class="text-muted fw-bold"><th class="ps-6 w-70px">Order</th><th>Question & Answer</th><th>Status</th><th class="text-end pe-6">Actions</th></tr></thead>
                            <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td class="ps-6"><span class="badge badge-light-dark">{{ $faq->sort_order }}</span></td>
                                    <td style="min-width:280px"><div class="fw-bolder text-dark mb-2">{{ $faq->question }}</div><div class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 130) }}</div></td>
                                    <td><span class="badge {{ $faq->is_active ? 'badge-light-success' : 'badge-light-secondary' }}">{{ $faq->is_active ? 'Active' : 'Hidden' }}</span></td>
                                    <td class="text-end pe-6 text-nowrap">
                                        <a href="{{ route('admin-faqs.index', ['edit' => $faq->id]) }}" class="btn btn-sm btn-light-primary me-2">Edit</a>
                                        <form action="{{ route('admin-faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ permanently?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger" type="submit">Delete</button></form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-15 text-muted">No FAQs added yet. Use the form to create the first one.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($faqs->hasPages())<div class="card-footer">{{ $faqs->links() }}</div>@endif
            </div>
        </div>
    </div>
</div>
@endsection
