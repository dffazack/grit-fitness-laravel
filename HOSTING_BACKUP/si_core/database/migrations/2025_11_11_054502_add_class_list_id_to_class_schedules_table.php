<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->foreignId('class_list_id')->nullable()->constrained('class_lists')->onDelete('cascade');
        });

        DB::statement('UPDATE class_schedules SET class_list_id = (SELECT cl.id FROM class_lists cl WHERE cl.name = class_schedules.name)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['class_list_id']);
            $table->dropColumn('class_list_id');
        });
    }
};
