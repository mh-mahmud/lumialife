<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'app_promo_enabled')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->boolean('app_promo_enabled')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'app_promo_enabled')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('app_promo_enabled');
            });
        }
    }
};
