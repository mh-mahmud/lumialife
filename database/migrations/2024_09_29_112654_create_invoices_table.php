<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MySQL can leave the table behind when a later ALTER TABLE for a
        // foreign key fails, because DDL is committed independently. Treat
        // that table as the completed migration on the next run.
        if (Schema::hasTable('invoices')) {
            return;
        }

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // The legacy customers table does not guarantee an indexed `id`
            // column, so it cannot safely be the target of a MySQL foreign
            // key. Keep this indexed for joins and application-level checks.
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('currency', 3)->default('BDT');
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->enum('discount_type', ['No discount', 'Before tax', 'After tax'])->default('No discount');
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('address')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('client_note')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('item_description')->nullable();
            $table->tinyInteger('prevent_reminders')->nullable();
            $table->tinyInteger('is_recurring')->nullable();
            $table->string('payment_mode')->nullable();
            $table->unsignedBigInteger('sale_agent_id')->nullable(); // Foreign key to users or agents
            $table->timestamps(); // created_at and updated_at columns
            $table->softDeletes(); // deleted_at column for soft deletes
            
            // Foreign key constraints
            $table->foreign('sale_agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
