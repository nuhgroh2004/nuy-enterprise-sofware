<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 50);
            $table->enum('source_type', ['sales_order', 'forecast', 'manual', 'other'])->default('manual');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('source_number', 100)->nullable();
            $table->date('demand_date');
            $table->date('required_date');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['draft', 'confirmed', 'planned', 'fulfilled', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'document_number']);
            $table->index(['company_id', 'status']);
            $table->index(['required_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
