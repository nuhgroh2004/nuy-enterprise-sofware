<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('maintenance_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('machine_id')->constrained()->restrictOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('maintenance_type', 50);
            $table->text('description');
            $table->text('performed_actions')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->decimal('actual_duration_hours', 8, 2)->nullable();
            $table->decimal('actual_cost', 18, 4)->nullable();
            $table->enum('result', ['completed', 'partial', 'deferred'])->default('completed');
            $table->date('performed_date');
            $table->timestamps();
            $table->index(['machine_id', 'performed_date']);
            $table->index(['company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_histories');
    }
};
