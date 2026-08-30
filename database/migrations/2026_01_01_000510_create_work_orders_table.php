<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('production_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_center_id')->constrained()->restrictOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('routing_operation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 50);
            $table->integer('sequence');
            $table->decimal('planned_quantity', 18, 4);
            $table->decimal('actual_quantity', 18, 4)->default(0);
            $table->decimal('rejected_quantity', 18, 4)->default(0);
            $table->decimal('scrap_quantity', 18, 4)->default(0);
            $table->decimal('setup_time_minutes', 10, 2)->nullable();
            $table->decimal('run_time_minutes', 10, 2)->nullable();
            $table->decimal('downtime_minutes', 10, 2)->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'paused', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'document_number']);
            $table->index(['production_order_id']);
            $table->index(['work_center_id', 'status']);
            $table->index(['machine_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
