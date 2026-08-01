<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->longText('meta_pixel_code')->nullable();
            $table->longText('gtm_header_code')->nullable();
            $table->longText('gtm_footer_code')->nullable();
            $table->longText('google_analytics_code')->nullable();
            $table->longText('custom_header_code')->nullable();
            $table->longText('custom_footer_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'meta_pixel_code',
                'gtm_header_code',
                'gtm_footer_code',
                'google_analytics_code',
                'custom_header_code',
                'custom_footer_code',
            ]);
        });
    }
};
