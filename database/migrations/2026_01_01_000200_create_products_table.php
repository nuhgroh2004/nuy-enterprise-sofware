<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uom_id')->constrained('units_of_measure')->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->boolean('is_purchasable')->default(false);
            $table->boolean('is_sellable')->default(false);
            $table->boolean('is_manufacturable')->default(false);
            $table->boolean('is_stockable')->default(true);
            $table->boolean('is_batch_tracked')->default(false);
            $table->boolean('is_serial_tracked')->default(false);
            $table->boolean('is_expiry_tracked')->default(false);
            $table->decimal('standard_cost', 18, 4)->nullable();
            $table->decimal('average_cost', 18, 4)->nullable();
            $table->decimal('last_purchase_cost', 18, 4)->nullable();
            $table->decimal('min_stock', 18, 4)->nullable();
            $table->decimal('max_stock', 18, 4)->nullable();
            $table->decimal('reorder_point', 18, 4)->nullable();
            $table->decimal('safety_stock', 18, 4)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['company_id', 'code']);
            $table->index(['company_id', 'status']);
            $table->index(['product_type_id']);
            $table->index(['product_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
