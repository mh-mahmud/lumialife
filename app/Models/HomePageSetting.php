<?php

namespace App\Models;

use App\Support\MediaStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'banner_section_status' => 'boolean',
        'about_section_status' => 'boolean',
        'promo_section_status' => 'boolean',
        'bulk_section_status' => 'boolean',
        'partners_section_status' => 'boolean',
        'partner_logos' => 'array',
    ];

    public function assetUrl(?string $path): string
    {
        if (!$path) {
            return asset('uploads/blank.png');
        }

        return MediaStorage::url($path, 'home-page');
    }

    public function promoMediaIsVideo(?string $path): bool
    {
        return in_array(strtolower(pathinfo((string) $path, PATHINFO_EXTENSION)), ['mp4', 'webm'], true);
    }
}
