<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_substitutes', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('is_preferred');
            $table->boolean('active')->default(true)->after('priority');
            $table->text('notes')->nullable()->after('active');
        });
    }

    public function down(): void
    {
        Schema::table('bom_substitutes', function (Blueprint $table) {
            $table->dropColumn(['priority', 'active', 'notes']);
        });
    }
};
