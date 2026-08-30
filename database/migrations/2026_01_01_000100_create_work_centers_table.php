<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->constrained()->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name', 255);
            $table->foreignId('production_process_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('capacity_per_hour', 18, 4)->nullable();
            $table->foreignId('uom_id')->nullable()->constrained('units_of_measure')->nullOnDelete();
            $table->decimal('setup_cost_per_hour', 18, 4)->nullable();
            $table->decimal('run_cost_per_hour', 18, 4)->nullable();
            $table->decimal('labor_cost_per_hour', 18, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['company_id', 'code']);
            $table->index(['plant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_centers');
    }
};
