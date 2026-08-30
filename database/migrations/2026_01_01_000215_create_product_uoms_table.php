<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_uoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->enum('usage_type', ['purchasing', 'sales', 'production']);
            $table->decimal('conversion_factor', 18, 8)->default(1);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'uom_id', 'usage_type']);
            $table->index(['product_id', 'usage_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_uoms');
    }
};
