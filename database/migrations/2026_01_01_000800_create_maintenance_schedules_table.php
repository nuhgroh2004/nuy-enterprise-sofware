<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('machine_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 50);
            $table->string('name', 255);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'hours_run'])->default('monthly');
            $table->integer('interval_value')->default(1);
            $table->integer('hours_threshold')->nullable();
            $table->date('last_performed_date')->nullable();
            $table->date('next_due_date')->nullable();
            $table->decimal('estimated_duration_hours', 8, 2)->nullable();
            $table->decimal('estimated_cost', 18, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'code']);
            $table->index(['machine_id', 'next_due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
