<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('inspector_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('document_number', 50);
            $table->string('inspection_type', 50);
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('source_document_number', 100)->nullable();
            $table->foreignId('production_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('batch_number', 50)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->decimal('quantity_inspected', 18, 4);
            $table->decimal('quantity_accepted', 18, 4)->default(0);
            $table->decimal('quantity_rejected', 18, 4)->default(0);
            $table->date('inspection_date');
            $table->enum('result', ['pending', 'pass', 'fail', 'conditional'])->default('pending');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'document_number']);
            $table->index(['production_order_id']);
            $table->index(['product_id']);
            $table->index(['source_type', 'source_id']);
            $table->index(['inspection_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_inspections');
    }
};
