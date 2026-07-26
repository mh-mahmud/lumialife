<?php

namespace App\Support;

use App\Models\Settings;
use Illuminate\Http\Request;

class StoreCurrency
{
    public function __construct(
        private readonly string $code,
        private readonly string $symbol,
        private readonly float $myrToBdtRate,
        private readonly bool $countryDetected,
        private readonly bool $ipLookupRequired,
        private readonly ?string $detectedCountry,
    ) {
    }

    public static function forRequest(Request $request, ?Settings $settings): self
    {
        $selectedCurrency = strtoupper((string) $request->cookie('store_currency'));
        if (in_array($selectedCurrency, ['BDT', 'MYR'], true)) {
            $rate = max((float) ($settings?->myr_to_bdt_rate ?: 30.23), 0.000001);

            return new self(
                $selectedCurrency,
                $selectedCurrency === 'BDT' ? '৳' : 'RM',
                $rate,
                true,
                false,
                null,
            );
        }

        $headerCountry = collect([
            $request->header('CF-IPCountry'),
            $request->header('CloudFront-Viewer-Country'),
            $request->header('X-Vercel-IP-Country'),
            $request->header('X-Country-Code'),
        ])->filter()->first();
        $cachedCountry = strtoupper((string) $request->cookie('store_country'));
        $country = $headerCountry ?: (preg_match('/^[A-Z]{2}$/', $cachedCountry) ? $cachedCountry : null);

        // Bangladesh is the base market. A configured CDN/proxy country
        // header switches every other country to Malaysian Ringgit.
        $code = $country && strtoupper($country) !== 'BD' ? 'MYR' : 'BDT';
        $rate = max((float) ($settings?->myr_to_bdt_rate ?: 30.23), 0.000001);

        return new self(
            $code,
            $code === 'BDT' ? '৳' : 'RM',
            $rate,
            (bool) $country,
            ! $headerCountry,
            $country ? strtoupper($country) : null,
        );
    }

    public function convert(float|int|string|null $bdtAmount): float
    {
        $amount = (float) $bdtAmount;

        return $this->code === 'MYR' ? $amount / $this->myrToBdtRate : $amount;
    }

    public function format(float|int|string|null $bdtAmount, int $decimals = 2): string
    {
        return $this->symbol . ' ' . number_format($this->convert($bdtAmount), $decimals);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function rate(): float
    {
        return $this->myrToBdtRate;
    }

    public function countryDetected(): bool
    {
        return $this->countryDetected;
    }

    public function ipLookupRequired(): bool
    {
        return $this->ipLookupRequired;
    }

    public function detectedCountry(): ?string
    {
        return $this->detectedCountry;
    }
}
