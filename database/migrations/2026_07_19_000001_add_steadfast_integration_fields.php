<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $settingColumns = [
            'steadfast_base_url' => fn (Blueprint $table) => $table->string('steadfast_base_url')->nullable(),
            'steadfast_api_key' => fn (Blueprint $table) => $table->text('steadfast_api_key')->nullable(),
            'steadfast_secret_key' => fn (Blueprint $table) => $table->text('steadfast_secret_key')->nullable(),
            'steadfast_bearer_token' => fn (Blueprint $table) => $table->text('steadfast_bearer_token')->nullable(),
            'steadfast_active' => fn (Blueprint $table) => $table->boolean('steadfast_active')->default(false),
        ];
        foreach ($settingColumns as $column => $definition) {
            if (! Schema::hasColumn('settings', $column)) Schema::table('settings', $definition);
        }

        $orderColumns = [
            'steadfast_consignment_id' => fn (Blueprint $table) => $table->unsignedBigInteger('steadfast_consignment_id')->nullable()->index(),
            'steadfast_tracking_code' => fn (Blueprint $table) => $table->string('steadfast_tracking_code')->nullable()->index(),
            'steadfast_status' => fn (Blueprint $table) => $table->string('steadfast_status')->nullable(),
            'steadfast_response' => fn (Blueprint $table) => $table->json('steadfast_response')->nullable(),
        ];
        foreach ($orderColumns as $column => $definition) {
            if (! Schema::hasColumn('orders', $column)) Schema::table('orders', $definition);
        }
    }

    public function down(): void
    {
        $settingColumns = ['steadfast_base_url','steadfast_api_key','steadfast_secret_key','steadfast_bearer_token','steadfast_active'];
        $orderColumns = ['steadfast_consignment_id','steadfast_tracking_code','steadfast_status','steadfast_response'];
        foreach ($settingColumns as $column) if (Schema::hasColumn('settings', $column)) Schema::table('settings', fn (Blueprint $table) => $table->dropColumn($column));
        foreach ($orderColumns as $column) if (Schema::hasColumn('orders', $column)) Schema::table('orders', fn (Blueprint $table) => $table->dropColumn($column));
    }
};
