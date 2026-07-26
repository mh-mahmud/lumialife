<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'myr_to_bdt_rate')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->decimal('myr_to_bdt_rate', 12, 4)->default(30.2300);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'myr_to_bdt_rate')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('myr_to_bdt_rate');
            });
        }
    }
};
