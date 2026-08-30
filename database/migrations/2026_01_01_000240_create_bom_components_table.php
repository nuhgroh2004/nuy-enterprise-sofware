<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->decimal('quantity', 18, 6);
            $table->decimal('scrap_percentage', 5, 2)->default(0);
            $table->decimal('yield_percentage', 5, 2)->default(100);
            $table->boolean('is_fixed_quantity')->default(false);
            $table->boolean('is_critical')->default(false);
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['bom_version_id']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_components');
    }
};
