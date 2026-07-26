<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_menu')->default(false)->after('is_display_products');
            $table->boolean('is_slider_bottom')->default(false)->after('is_menu');
            $table->boolean('is_feature')->default(false)->after('is_slider_bottom');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['is_menu', 'is_slider_bottom', 'is_feature']);
        });
    }
};
