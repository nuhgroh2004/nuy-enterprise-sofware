<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_variances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('production_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_cost_id')->nullable()->constrained()->nullOnDelete();
            $table->string('variance_type', 50);
            $table->decimal('standard_amount', 18, 4);
            $table->decimal('actual_amount', 18, 4);
            $table->decimal('variance_amount', 18, 4);
            $table->decimal('variance_percentage', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['production_order_id']);
            $table->index(['company_id', 'variance_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_variances');
    }
};
