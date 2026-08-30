<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_header_id')->constrained()->cascadeOnDelete();
            $table->string('version', 50);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->enum('approval_state', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->string('approved_by', 100)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['bom_header_id', 'version']);
            $table->index(['approval_state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_versions');
    }
};
