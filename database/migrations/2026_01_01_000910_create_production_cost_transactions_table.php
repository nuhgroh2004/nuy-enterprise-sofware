<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_cost_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('production_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cost_type', 50);
            $table->decimal('amount', 18, 4);
            $table->decimal('quantity', 18, 4)->nullable();
            $table->decimal('rate', 18, 4)->nullable();
            $table->string('description', 255)->nullable();
            $table->date('transaction_date');
            $table->timestamps();
            $table->index(['production_order_id']);
            $table->index(['company_id', 'cost_type']);
            $table->index(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_cost_transactions');
    }
};
