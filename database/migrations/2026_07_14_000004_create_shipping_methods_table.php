<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_method')->nullable()->after('delivery_charge');
        });

        if (Schema::hasTable('settings')) {
            $settings = DB::table('settings')->first();
            $now = now();

            DB::table('shipping_methods')->insert([
                [
                    'name' => 'Inside Dhaka',
                    'price' => (float) ($settings->charge_inside_dhaka ?? 0),
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Outside Dhaka',
                    'price' => (float) ($settings->charge_outside_dhaka ?? 0),
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_method');
        });

        Schema::dropIfExists('shipping_methods');
    }
};
