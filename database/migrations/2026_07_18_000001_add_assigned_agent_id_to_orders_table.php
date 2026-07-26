<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'assigned_agent_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->char('assigned_agent_id', 4)->nullable()->after('billing_address_id')->index();
            $table->foreign('assigned_agent_id')->references('agent_id')->on('agents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('orders', 'assigned_agent_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_agent_id']);
            $table->dropColumn('assigned_agent_id');
        });
    }
};
