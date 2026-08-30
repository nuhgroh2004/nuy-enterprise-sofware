<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('maintenance_schedule_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('machine_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('document_number', 50);
            $table->enum('maintenance_type', ['preventive', 'corrective', 'predictive', 'emergency'])->default('preventive');
            $table->text('description');
            $table->text('notes')->nullable();
            $table->date('scheduled_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('actual_duration_hours', 8, 2)->nullable();
            $table->decimal('actual_cost', 18, 4)->nullable();
            $table->enum('status', ['draft', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->unique(['company_id', 'document_number']);
            $table->index(['machine_id', 'status']);
            $table->index(['scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_orders');
    }
};
