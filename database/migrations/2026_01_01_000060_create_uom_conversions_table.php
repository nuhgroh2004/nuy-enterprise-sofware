<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uom_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->foreignId('to_uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->decimal('conversion_factor', 18, 8);
            $table->boolean('is_base')->default(false);
            $table->timestamps();
            $table->unique(['from_uom_id', 'to_uom_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uom_conversions');
    }
};
