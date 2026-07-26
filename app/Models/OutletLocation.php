<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'address',
        'hotline',
        'map_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];
}
