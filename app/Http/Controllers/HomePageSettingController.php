<?php

namespace App\Http\Controllers;

use App\Models\HomePageSetting;
use App\Support\MediaStorage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomePageSettingController extends Controller
{
    private const IMAGE_FIELDS = [
        'banner_one_image',
        'banner_two_image',
        'about_image',
        'bulk_image',
        'featured_partner_logo',
    ];

    public function edit()
    {
        $setting = HomePageSetting::firstOrCreate([]);

        return view('home_page_settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = HomePageSetting::firstOrCreate([]);
        $data = $request->validate([
            'banner_section_status' => ['nullable', 'boolean'],
            'banner_one_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'banner_one_url' => ['nullable', 'string', 'max:1000'],
            'banner_two_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'banner_two_url' => ['nullable', 'string', 'max:1000'],
            'about_section_status' => ['nullable', 'boolean'],
            'about_title' => ['required', 'string', 'max:200'],
            'about_subtitle' => ['nullable', 'string', 'max:500'],
            'about_description' => ['nullable', 'string', 'max:5000'],
            'about_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'about_url' => ['nullable', 'string', 'max:1000'],
            'promo_section_status' => ['nullable', 'boolean'],
            'promo_left_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif,mp4,webm', 'max:51200'],
            'promo_left_url' => ['nullable', 'string', 'max:1000'],
            'promo_right_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif,mp4,webm', 'max:51200'],
            'promo_right_url' => ['nullable', 'string', 'max:1000'],
            'bulk_section_status' => ['nullable', 'boolean'],
            'bulk_title' => ['nullable', 'string', 'max:300'],
            'bulk_description' => ['nullable', 'string', 'max:5000'],
            'bulk_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'bulk_url' => ['nullable', 'string', 'max:1000'],
            'partners_section_status' => ['nullable', 'boolean'],
            'partners_title' => ['nullable', 'string', 'max:300'],
            'partners_subtitle' => ['nullable', 'string', 'max:500'],
            'partners_description' => ['nullable', 'string', 'max:5000'],
            'featured_partner_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'partner_logos' => ['nullable', 'array', 'max:60'],
            'partner_logos.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:3072'],
        ]);

        foreach (['banner_section_status', 'about_section_status', 'promo_section_status', 'bulk_section_status', 'partners_section_status'] as $statusField) {
            $data[$statusField] = $request->boolean($statusField);
        }

        foreach (self::IMAGE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeFile($request->file($field), $setting->{$field});
            } else {
                unset($data[$field]);
            }
        }

        foreach (['promo_left_media', 'promo_right_media'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeFile($request->file($field), $setting->{$field});
            } else {
                unset($data[$field]);
            }
        }

        if ($request->hasFile('partner_logos')) {
            foreach ($setting->partner_logos ?? [] as $oldLogo) {
                $this->deleteUploadedFile($oldLogo);
            }
            $data['partner_logos'] = collect($request->file('partner_logos'))
                ->map(fn ($file) => $this->storeFile($file))
                ->values()
                ->all();
        } else {
            unset($data['partner_logos']);
        }

        $setting->update($data);

        return redirect()->route('home-page-setting-edit')->with('success', 'Home page settings updated successfully.');
    }

    public function deletePartnerLogo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:featured,partner'],
            'logo' => ['nullable', 'string', 'max:1000'],
        ]);

        $setting = HomePageSetting::firstOrCreate([]);

        if ($data['type'] === 'featured') {
            if (!$setting->featured_partner_logo) {
                return response()->json(['message' => 'Featured partner logo was already removed.'], 404);
            }

            $logo = $setting->featured_partner_logo;
            $setting->update(['featured_partner_logo' => null]);
            $this->deleteUploadedFile($logo);

            return response()->json(['message' => 'Featured partner logo deleted successfully.']);
        }

        $logos = $setting->partner_logos ?? [];
        $index = array_search($data['logo'] ?? '', $logos, true);

        if ($index === false) {
            return response()->json(['message' => 'Partner logo was not found.'], 404);
        }

        $logo = $logos[$index];
        unset($logos[$index]);
        $setting->update(['partner_logos' => array_values($logos)]);
        $this->deleteUploadedFile($logo);

        return response()->json(['message' => 'Partner logo deleted successfully.']);
    }

    private function storeFile($file, ?string $oldPath = null): string
    {
        return MediaStorage::replace($file, 'home-page', $oldPath);
    }

    private function deleteUploadedFile(?string $path): void
    {
        MediaStorage::delete($path, 'home-page');
    }
}
