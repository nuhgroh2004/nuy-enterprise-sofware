<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_versions', function (Blueprint $table) {
            $table->string('revision', 20)->nullable()->after('version');
            $table->foreignId('routing_version_id')->nullable()->constrained()->nullOnDelete()->after('bom_header_id');
            $table->decimal('output_qty', 18, 6)->nullable()->after('expiry_date');
            $table->foreignId('output_uom_id')->nullable()->constrained('units_of_measure')->nullOnDelete()->after('output_qty');
            $table->decimal('yield_percent', 5, 2)->default(100)->after('output_uom_id');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
            $table->timestamp('submitted_at')->nullable()->after('submitted_by');
        });
    }

    public function down(): void
    {
        Schema::table('bom_versions', function (Blueprint $table) {
            $table->dropForeign(['routing_version_id']);
            $table->dropForeign(['output_uom_id']);
            $table->dropForeign(['submitted_by']);
            $table->dropColumn([
                'revision', 'routing_version_id', 'output_qty',
                'output_uom_id', 'yield_percent', 'submitted_by', 'submitted_at',
            ]);
        });
    }
};
