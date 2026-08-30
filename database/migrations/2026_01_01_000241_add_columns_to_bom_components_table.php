<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_components', function (Blueprint $table) {
            $table->integer('operation_sequence')->nullable()->after('sort_order');
            $table->boolean('backflush')->default(false)->after('operation_sequence');
            $table->boolean('is_optional')->default(false)->after('backflush');
            $table->string('alternative_group', 50)->nullable()->after('is_optional');
            $table->enum('substitute_policy', ['automatic', 'manual', 'recommendation'])->default('manual')->after('alternative_group');
        });
    }

    public function down(): void
    {
        Schema::table('bom_components', function (Blueprint $table) {
            $table->dropColumn([
                'operation_sequence', 'backflush', 'is_optional',
                'alternative_group', 'substitute_policy',
            ]);
        });
    }
};
