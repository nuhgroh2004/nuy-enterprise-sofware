<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mps_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_production_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('demand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->date('planned_date');
            $table->decimal('demand_quantity', 18, 4);
            $table->decimal('planned_quantity', 18, 4);
            $table->decimal('available_quantity', 18, 4)->default(0);
            $table->decimal('projected_balance', 18, 4)->default(0);
            $table->enum('status', ['planned', 'confirmed', 'produced', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['master_production_schedule_id']);
            $table->index(['product_id', 'planned_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mps_lines');
    }
};
