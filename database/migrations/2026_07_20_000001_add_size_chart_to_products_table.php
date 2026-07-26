<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('size_chart_title')->nullable();
            $table->json('size_chart_columns')->nullable();
            $table->json('size_chart_rows')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size_chart_title', 'size_chart_columns', 'size_chart_rows']);
        });
    }
};
