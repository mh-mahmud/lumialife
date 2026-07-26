<?php

namespace App\Http\Controllers;

use App\Models\OutletLocation;
use App\Models\OutletPageSetting;
use App\Support\MediaStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OutletLocationController extends Controller
{
    public function index()
    {
        $outlets = OutletLocation::orderBy('sort_order')->orderBy('location_name')->paginate(20);
        $pageSetting = OutletPageSetting::first();

        return view('outlet_locations.index', compact('outlets', 'pageSetting'));
    }

    public function create()
    {
        return view('outlet_locations.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedOutlet($request);
        OutletLocation::create($data);

        return redirect()->route('outlet-location-list')->with('success', 'Outlet created successfully.');
    }

    public function edit(OutletLocation $outletLocation)
    {
        return view('outlet_locations.edit', compact('outletLocation'));
    }

    public function update(Request $request, OutletLocation $outletLocation)
    {
        $outletLocation->update($this->validatedOutlet($request));

        return redirect()->route('outlet-location-list')->with('success', 'Outlet updated successfully.');
    }

    public function destroy(OutletLocation $outletLocation)
    {
        $outletLocation->delete();

        return redirect()->route('outlet-location-list')->with('success', 'Outlet deleted successfully.');
    }

    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $setting = OutletPageSetting::firstOrCreate([]);
        $fileName = MediaStorage::replace($request->file('banner_image'), 'outlets', $setting->banner_image);
        $setting->update(['banner_image' => $fileName]);

        return redirect()->route('outlet-location-list')->with('success', 'Outlet banner updated successfully.');
    }

    private function validatedOutlet(Request $request): array
    {
        $data = $request->validate([
            'location_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:1000'],
            'hotline' => ['required', 'string', 'max:50'],
            'map_url' => ['required', 'string', 'max:6000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'status' => ['required', 'boolean'],
        ]);

        $data['map_url'] = $this->normalizeMapUrl($data['map_url']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function normalizeMapUrl(string $input): string
    {
        $input = html_entity_decode(trim($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/src=["\']([^"\']+)["\']/i', $input, $matches)) {
            $input = trim($matches[1]);
        }

        $host = strtolower((string) parse_url($input, PHP_URL_HOST));
        $scheme = strtolower((string) parse_url($input, PHP_URL_SCHEME));
        $googleHost = $host === 'google.com' || str_ends_with($host, '.google.com')
            || $host === 'google.com.bd' || str_ends_with($host, '.google.com.bd');

        if (!filter_var($input, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true) || !$googleHost) {
            throw ValidationException::withMessages([
                'map_url' => 'Please provide a valid Google Maps embed URL or iframe code.',
            ]);
        }

        return $input;
    }
}
