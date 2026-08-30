<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->date('required_date');
            $table->decimal('required_quantity', 18, 4);
            $table->decimal('available_quantity', 18, 4)->default(0);
            $table->decimal('planned_receipt_quantity', 18, 4)->default(0);
            $table->decimal('planned_release_quantity', 18, 4)->default(0);
            $table->decimal('shortage_quantity', 18, 4)->default(0);
            $table->decimal('lot_size', 18, 4)->nullable();
            $table->decimal('safety_stock', 18, 4)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->enum('status', ['draft', 'planned', 'ordered', 'received', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status']);
            $table->index(['product_id', 'required_date']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requirements');
    }
};
