<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];
}
