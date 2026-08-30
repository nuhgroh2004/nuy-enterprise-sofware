<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routing_operation_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routing_operation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('depends_on_operation_id')->constrained('routing_operations')->cascadeOnDelete();
            $table->enum('dependency_type', ['finish_to_start', 'start_to_start', 'finish_to_finish'])->default('finish_to_start');
            $table->decimal('lag_time_minutes', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['routing_operation_id', 'depends_on_operation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routing_operation_dependencies');
    }
};
