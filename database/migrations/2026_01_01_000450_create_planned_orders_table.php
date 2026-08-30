<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planned_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('material_requirement_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->string('order_type', 50)->default('production');
            $table->decimal('planned_quantity', 18, 4);
            $table->date('planned_release_date');
            $table->date('planned_receipt_date');
            $table->integer('lead_time_days')->nullable();
            $table->enum('status', ['draft', 'firm', 'released', 'converted', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('converted_order_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planned_orders');
    }
};
