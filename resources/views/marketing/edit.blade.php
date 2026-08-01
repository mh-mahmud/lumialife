@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div>
                <h1 class="text-dark fw-bolder fs-3 my-1">Marketing</h1>
                <span class="text-muted fs-7">Tracking and custom storefront code</span>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-light-primary">View Storefront</a>
        </div>
    </div>

    <div class="container-xxl py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-warning">
            These snippets are rendered as live code on every storefront page. Only paste code from trusted providers.
        </div>

        <form action="{{ route('marketing.update') }}" method="POST">
            @csrf
            @method('PUT')

            @php
                $fields = [
                    'meta_pixel_code' => ['Meta Pixel Code', 'Paste the complete Meta Pixel script. It will be placed before </head>.'],
                    'gtm_header_code' => ['GTM Header Code', 'Paste the Google Tag Manager head snippet. It will be placed before </head>.'],
                    'gtm_footer_code' => ['GTM Footer Code', 'Paste the GTM body/noscript snippet. It will be placed before </body>.'],
                    'google_analytics_code' => ['Google Analytics Code', 'Paste the complete Google Analytics tag. It will be placed before </head>.'],
                    'custom_header_code' => ['Custom Header Code', 'Custom HTML, CSS, or JavaScript rendered before </head>.'],
                    'custom_footer_code' => ['Custom Footer Code', 'Custom HTML or JavaScript rendered before </body>.'],
                ];
            @endphp

            <div class="row g-6">
                @foreach ($fields as $name => [$label, $help])
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">{{ $label }}</h3></div>
                            <div class="card-body">
                                <textarea name="{{ $name }}" rows="8" class="form-control form-control-solid font-monospace" spellcheck="false" placeholder="Paste code here...">{{ old($name, $settings->{$name}) }}</textarea>
                                <div class="form-text mt-2">{{ $help }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-6">
                <button type="submit" class="btn btn-primary">Save Marketing Settings</button>
            </div>
        </form>
    </div>
@endsection
