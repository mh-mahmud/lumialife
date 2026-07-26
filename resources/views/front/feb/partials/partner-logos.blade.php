@php
    $partnerLogos = collect($setting->partner_logos ?? []);

    if ($setting->featured_partner_logo) {
        $partnerLogos->prepend($setting->featured_partner_logo);
    }

    $logoBlocks = $partnerLogos
        ->filter(fn ($logo) => filled($logo))
        ->unique()
        ->values()
        ->chunk(6);
@endphp

@if($logoBlocks->isNotEmpty())
    <div class="container-fluid text-center" style="margin-top: 20px">
        <div id="print_type_carousel" class="carousel-items">
            @foreach($logoBlocks as $logoBlock)
                <div class="carousel-block">
                    @foreach($logoBlock->chunk(2) as $logoRow)
                        <div class="carousel-row">
                            @foreach($logoRow as $logo)
                                <div class="carousel-col slick-slide-client">
                                    <img src="{{ $setting->assetUrl($logo) }}" alt="Partner logo" loading="lazy" />
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
