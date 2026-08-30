<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_substitutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_component_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->decimal('conversion_factor', 18, 6)->default(1);
            $table->boolean('is_preferred')->default(false);
            $table->timestamps();
            $table->index(['bom_component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_substitutes');
    }
};
