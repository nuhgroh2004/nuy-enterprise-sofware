<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->foreignId('reason_code_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 50);
            $table->string('movement_type', 50);
            $table->decimal('quantity', 18, 4);
            $table->decimal('unit_cost', 18, 4)->nullable();
            $table->decimal('total_cost', 18, 4)->nullable();
            $table->string('batch_number', 50)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->date('transaction_date');
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'document_number']);
            $table->index(['company_id', 'product_id', 'warehouse_id']);
            $table->index(['product_id', 'warehouse_id', 'location_id']);
            $table->index(['source_type', 'source_id']);
            $table->index(['movement_type']);
            $table->index(['transaction_date']);
            $table->index(['batch_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
