@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">Home Page Setting</h1>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-light-primary">View Home Page</a>
        </div>
    </div>

    <div class="container-xxl py-4">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('home-page-setting-update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            @php
                $preview = fn ($path) => $setting->assetUrl($path);
                $switches = [
                    'banner_section_status' => 'Show banner section',
                    'about_section_status' => 'Show Fabrilife section',
                    'promo_section_status' => 'Show promo media section',
                    'bulk_section_status' => 'Show bulk order section',
                    'partners_section_status' => 'Show partner section',
                ];
            @endphp

            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">Two Home Banners</h3></div>
                <div class="card-body row g-6">
                    <div class="col-12">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="banner_section_status" value="0">
                            <input class="form-check-input" type="checkbox" name="banner_section_status" value="1" @checked(old('banner_section_status', $setting->banner_section_status))>
                            <span class="form-check-label">{{ $switches['banner_section_status'] }}</span>
                        </label>
                    </div>
                    @foreach([1 => 'one', 2 => 'two'] as $number => $key)
                        @php($imageField = 'banner_' . $key . '_image')
                        @php($urlField = 'banner_' . $key . '_url')
                        <div class="col-lg-6">
                            <label class="form-label fw-bolder">Banner {{ $number }} Image</label>
                            <input type="file" name="{{ $imageField }}" class="form-control form-control-solid" accept="image/*">
                            <input type="text" name="{{ $urlField }}" value="{{ old($urlField, $setting->{$urlField}) }}" class="form-control form-control-solid mt-3" placeholder="Banner link">
                            <img src="{{ $preview($setting->{$imageField}) }}" class="mt-3 rounded border" style="width:100%;height:150px;object-fit:cover" alt="Banner {{ $number }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">Fabrilife / About Section</h3></div>
                <div class="card-body row g-5">
                    <div class="col-12">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="about_section_status" value="0">
                            <input class="form-check-input" type="checkbox" name="about_section_status" value="1" @checked(old('about_section_status', $setting->about_section_status))>
                            <span class="form-check-label">{{ $switches['about_section_status'] }}</span>
                        </label>
                    </div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Title</label><input name="about_title" value="{{ old('about_title', $setting->about_title) }}" class="form-control form-control-solid" required></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Subtitle</label><input name="about_subtitle" value="{{ old('about_subtitle', $setting->about_subtitle) }}" class="form-control form-control-solid"></div>
                    <div class="col-12"><label class="form-label fw-bolder">Description</label><textarea name="about_description" rows="4" class="form-control form-control-solid">{{ old('about_description', $setting->about_description) }}</textarea></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Section Link</label><input name="about_url" value="{{ old('about_url', $setting->about_url) }}" class="form-control form-control-solid"></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Image</label><input type="file" name="about_image" class="form-control form-control-solid" accept="image/*"></div>
                    <div class="col-lg-4"><img src="{{ $preview($setting->about_image) }}" class="rounded border w-100" style="height:130px;object-fit:cover" alt="About image"></div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">Promo Media Section</h3></div>
                <div class="card-body row g-5">
                    <div class="col-12">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="promo_section_status" value="0">
                            <input class="form-check-input" type="checkbox" name="promo_section_status" value="1" @checked(old('promo_section_status', $setting->promo_section_status))>
                            <span class="form-check-label">{{ $switches['promo_section_status'] }}</span>
                        </label>
                        <div class="form-text">Image, MP4 or WEBM can be uploaded (maximum 50MB).</div>
                    </div>
                    @foreach(['left' => 'Left Large Media', 'right' => 'Right Media'] as $key => $label)
                        @php($mediaField = 'promo_' . $key . '_media')
                        @php($urlField = 'promo_' . $key . '_url')
                        <div class="col-lg-6">
                            <label class="form-label fw-bolder">{{ $label }}</label>
                            <input type="file" name="{{ $mediaField }}" class="form-control form-control-solid" accept="image/*,video/mp4,video/webm">
                            <input name="{{ $urlField }}" value="{{ old($urlField, $setting->{$urlField}) }}" class="form-control form-control-solid mt-3" placeholder="Click link">
                            @if($setting->promoMediaIsVideo($setting->{$mediaField}))
                                <video src="{{ $preview($setting->{$mediaField}) }}" class="mt-3 rounded border w-100" style="height:150px;object-fit:cover" muted controls></video>
                            @else
                                <img src="{{ $preview($setting->{$mediaField}) }}" class="mt-3 rounded border w-100" style="height:150px;object-fit:cover" alt="{{ $label }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">Bulk Order / Wholesale</h3></div>
                <div class="card-body row g-5">
                    <div class="col-12">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="bulk_section_status" value="0">
                            <input class="form-check-input" type="checkbox" name="bulk_section_status" value="1" @checked(old('bulk_section_status', $setting->bulk_section_status))>
                            <span class="form-check-label">{{ $switches['bulk_section_status'] }}</span>
                        </label>
                    </div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Title</label><input name="bulk_title" value="{{ old('bulk_title', $setting->bulk_title) }}" class="form-control form-control-solid"></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Link</label><input name="bulk_url" value="{{ old('bulk_url', $setting->bulk_url) }}" class="form-control form-control-solid"></div>
                    <div class="col-12"><label class="form-label fw-bolder">Description</label><textarea name="bulk_description" rows="4" class="form-control form-control-solid">{{ old('bulk_description', $setting->bulk_description) }}</textarea></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Image</label><input type="file" name="bulk_image" class="form-control form-control-solid" accept="image/*"></div>
                    <div class="col-lg-6"><img src="{{ $preview($setting->bulk_image) }}" class="rounded border w-100" style="height:130px;object-fit:cover" alt="Bulk section"></div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">Work With Us / Partners</h3></div>
                <div class="card-body row g-5">
                    <div class="col-12">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="partners_section_status" value="0">
                            <input class="form-check-input" type="checkbox" name="partners_section_status" value="1" @checked(old('partners_section_status', $setting->partners_section_status))>
                            <span class="form-check-label">{{ $switches['partners_section_status'] }}</span>
                        </label>
                    </div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Title</label><input name="partners_title" value="{{ old('partners_title', $setting->partners_title) }}" class="form-control form-control-solid"></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Subtitle</label><input name="partners_subtitle" value="{{ old('partners_subtitle', $setting->partners_subtitle) }}" class="form-control form-control-solid"></div>
                    <div class="col-12"><label class="form-label fw-bolder">Description</label><textarea name="partners_description" rows="3" class="form-control form-control-solid">{{ old('partners_description', $setting->partners_description) }}</textarea></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Featured Partner Logo</label><input type="file" name="featured_partner_logo" class="form-control form-control-solid" accept="image/*"></div>
                    <div class="col-lg-6"><label class="form-label fw-bolder">Replace All Partner Logos</label><input type="file" name="partner_logos[]" class="form-control form-control-solid" accept="image/*" multiple><div class="form-text">Select multiple logos together.</div></div>
                    <div class="col-12 d-flex flex-wrap gap-4 p-4 border rounded" id="partner-logo-list">
                        @if($setting->featured_partner_logo)
                            <div class="position-relative border rounded bg-white p-3 partner-logo-item" style="width:126px">
                                <span class="badge badge-light-primary position-absolute top-0 start-0 m-1">Featured</span>
                                <img src="{{ $preview($setting->featured_partner_logo) }}" class="w-100" style="height:62px;object-fit:contain;margin-top:12px" alt="Featured partner">
                                <button type="button" class="btn btn-sm btn-light-danger w-100 mt-2 delete-partner-logo" data-type="featured"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                        @endif
                        @foreach($setting->partner_logos ?? [] as $logo)
                            <div class="position-relative border rounded bg-white p-3 partner-logo-item" style="width:112px">
                                <img src="{{ $preview($logo) }}" class="w-100" style="height:55px;object-fit:contain" alt="Partner logo">
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger position-absolute top-0 end-0 m-1 delete-partner-logo" data-type="partner" data-logo="{{ $logo }}" title="Delete logo"><i class="fa fa-times"></i></button>
                            </div>
                        @endforeach
                        @if(!$setting->featured_partner_logo && empty($setting->partner_logos))
                            <div class="text-muted py-5 w-100 text-center">No partner logos uploaded.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-end pb-10"><button type="submit" class="btn btn-primary px-10">Save Home Page Settings</button></div>
        </form>
    </div>
@endsection

@section('endScript')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('partner-logo-list')?.addEventListener('click', async function (event) {
        const button = event.target.closest('.delete-partner-logo');
        if (!button || !confirm('Are you sure you want to delete this logo?')) return;

        button.disabled = true;
        const originalHtml = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        try {
            const response = await fetch(@json(route('home-page-setting-partner-logo-delete')), {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ type: button.dataset.type, logo: button.dataset.logo || null })
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(payload.message || `Delete failed (${response.status})`);
            button.closest('.partner-logo-item').remove();
            if (window.Swal) Swal.fire({ icon: 'success', title: 'Deleted', text: payload.message, timer: 1400, showConfirmButton: false });
        } catch (error) {
            button.disabled = false;
            button.innerHTML = originalHtml;
            const message = error.message || 'Logo could not be deleted.';
            if (window.Swal) Swal.fire({ icon: 'error', title: 'Error', text: message }); else alert(message);
        }
    });
});
</script>
@endsection
