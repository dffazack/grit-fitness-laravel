<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->string('custom_class_name')->nullable()->after('class_list_id');
        });
    }

    public function down()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn('custom_class_name');
        });
    }
};
