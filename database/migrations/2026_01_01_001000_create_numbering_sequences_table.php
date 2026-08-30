<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('numbering_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->string('document_type', 50);
            $table->string('prefix', 50)->nullable();
            $table->string('plant_code', 10)->nullable();
            $table->boolean('include_year')->default(true);
            $table->boolean('include_month')->default(false);
            $table->integer('padding')->default(6);
            $table->unsignedBigInteger('current_sequence')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numbering_sequences');
    }
};
