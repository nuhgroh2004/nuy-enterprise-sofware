<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routing_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routing_version_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence');
            $table->string('code', 50)->nullable();
            $table->string('name', 255);
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('production_process_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('setup_time_minutes', 10, 2)->nullable();
            $table->decimal('run_time_minutes', 10, 2)->nullable();
            $table->decimal('queue_time_minutes', 10, 2)->nullable();
            $table->decimal('wait_time_minutes', 10, 2)->nullable();
            $table->integer('labor_required')->nullable();
            $table->integer('machine_required')->nullable();
            $table->decimal('standard_output', 18, 4)->nullable();
            $table->foreignId('output_uom_id')->nullable()->constrained('units_of_measure')->nullOnDelete();
            $table->decimal('scrap_percentage', 5, 2)->default(0);
            $table->text('quality_checkpoint')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['routing_version_id', 'sequence']);
            $table->index(['work_center_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routing_operations');
    }
};
