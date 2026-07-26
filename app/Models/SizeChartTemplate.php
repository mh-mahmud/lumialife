<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'columns', 'rows', 'is_active'];

    protected $casts = [
        'columns' => 'array',
        'rows' => 'array',
        'is_active' => 'boolean',
    ];
}
