<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function edit(Request $request): View
    {
        $this->ensureAdmin($request);

        $settings = Settings::firstOrFail();

        return view('marketing.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'meta_pixel_code' => ['nullable', 'string', 'max:100000'],
            'gtm_header_code' => ['nullable', 'string', 'max:100000'],
            'gtm_footer_code' => ['nullable', 'string', 'max:100000'],
            'google_analytics_code' => ['nullable', 'string', 'max:100000'],
            'custom_header_code' => ['nullable', 'string', 'max:100000'],
            'custom_footer_code' => ['nullable', 'string', 'max:100000'],
        ]);

        Settings::firstOrFail()->update($validated);

        return back()->with('success', 'Marketing settings updated successfully.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->user_type === 'admin', 403);
    }
}
