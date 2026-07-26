<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('categories')->where('category_slug', 'gift-card')->exists()) {
            DB::table('categories')->insert([
                'parent_id' => null,
                'category_name' => 'Gift Card',
                'category_slug' => 'gift-card',
                'category_description' => 'Gift cards available for purchase.',
                'status' => 1,
                'is_display_products' => 0,
                'is_menu' => 0,
                'is_slider_bottom' => 0,
                'is_feature' => 0,
                'is_home_promo' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->where('category_slug', 'gift-card')->delete();
    }
};
