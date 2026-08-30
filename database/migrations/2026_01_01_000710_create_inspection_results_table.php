<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_inspection_id')->constrained()->cascadeOnDelete();
            $table->string('parameter_name', 255);
            $table->string('specification', 255)->nullable();
            $table->string('actual_value', 255);
            $table->string('unit', 50)->nullable();
            $table->enum('result', ['pass', 'fail', 'na'])->default('pass');
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['quality_inspection_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_results');
    }
};
