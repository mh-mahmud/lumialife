<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = [
        'meta_pixel_code',
        'gtm_header_code',
        'gtm_footer_code',
        'google_analytics_code',
        'custom_header_code',
        'custom_footer_code',
    ];

}
