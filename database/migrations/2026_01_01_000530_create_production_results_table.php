<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('production_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->string('document_number', 50);
            $table->decimal('good_quantity', 18, 4);
            $table->decimal('rejected_quantity', 18, 4)->default(0);
            $table->decimal('scrap_quantity', 18, 4)->default(0);
            $table->string('batch_number', 50)->nullable();
            $table->date('result_date');
            $table->enum('status', ['draft', 'posted', 'reversed'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'document_number']);
            $table->index(['production_order_id']);
            $table->index(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_results');
    }
};
