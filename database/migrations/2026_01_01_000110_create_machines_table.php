<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 50);
            $table->string('name', 255);
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->decimal('capacity_per_hour', 18, 4)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'broken'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['company_id', 'code']);
            $table->index(['work_center_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
