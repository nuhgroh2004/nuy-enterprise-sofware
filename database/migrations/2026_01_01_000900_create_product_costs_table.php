<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('bom_version_id')->nullable()->constrained()->nullOnDelete();
            $table->string('version', 50);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('material_cost', 18, 4)->default(0);
            $table->decimal('labor_cost', 18, 4)->default(0);
            $table->decimal('machine_cost', 18, 4)->default(0);
            $table->decimal('overhead_cost', 18, 4)->default(0);
            $table->decimal('total_cost', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);
            $table->enum('cost_type', ['standard', 'actual', 'estimated'])->default('standard');
            $table->boolean('is_current')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'product_id', 'version']);
            $table->index(['product_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_costs');
    }
};
