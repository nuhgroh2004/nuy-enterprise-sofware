<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downtime_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('machine_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('maintenance_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reason_code_id')->nullable()->constrained()->nullOnDelete();
            $table->string('downtime_type', 50);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->decimal('duration_minutes', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['machine_id', 'started_at']);
            $table->index(['work_order_id']);
            $table->index(['company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downtime_records');
    }
};
