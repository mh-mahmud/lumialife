<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'fraud_checker_base_url')) {
                $table->string('fraud_checker_base_url')->nullable();
            }
            if (! Schema::hasColumn('settings', 'fraud_checker_api_key')) {
                $table->text('fraud_checker_api_key')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'fraud_checker_base_url')) $table->dropColumn('fraud_checker_base_url');
            if (Schema::hasColumn('settings', 'fraud_checker_api_key')) $table->dropColumn('fraud_checker_api_key');
        });
    }
};
