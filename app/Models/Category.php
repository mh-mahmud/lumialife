<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'category_name',
        'category_slug',
        'category_description',
        'category_image',
        'is_display_products',
        'is_menu',
        'is_slider_bottom',
        'is_feature',
        'is_home_promo',
        'status'
    ];

    protected $casts = [
        'is_display_products' => 'boolean',
        'is_menu' => 'boolean',
        'is_slider_bottom' => 'boolean',
        'is_feature' => 'boolean',
        'is_home_promo' => 'boolean',
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
