<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('non_conformances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('quality_inspection_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('production_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reason_code_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 50);
            $table->string('severity', 20)->default('medium');
            $table->text('description');
            $table->string('disposition', 50)->default('hold');
            $table->decimal('affected_quantity', 18, 4)->default(0);
            $table->decimal('estimated_cost', 18, 4)->nullable();
            $table->enum('status', ['open', 'investigating', 'resolved', 'closed'])->default('open');
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->date('target_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'document_number']);
            $table->index(['production_order_id']);
            $table->index(['quality_inspection_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_conformances');
    }
};
