<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('bom_version_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('routing_version_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('planned_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('demand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->string('document_number', 50);
            $table->decimal('planned_quantity', 18, 4);
            $table->decimal('confirmed_quantity', 18, 4)->default(0);
            $table->decimal('produced_quantity', 18, 4)->default(0);
            $table->decimal('rejected_quantity', 18, 4)->default(0);
            $table->decimal('scrap_quantity', 18, 4)->default(0);
            $table->date('planned_start_date');
            $table->date('planned_finish_date');
            $table->timestamp('actual_start_date')->nullable();
            $table->timestamp('actual_finish_date')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['draft', 'planned', 'released', 'in_progress', 'completed', 'closed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'document_number']);
            $table->index(['company_id', 'status']);
            $table->index(['plant_id', 'status']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
