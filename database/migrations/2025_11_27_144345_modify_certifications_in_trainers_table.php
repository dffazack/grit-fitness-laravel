<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            // Assume the 'certifications' column exists and is of type TEXT or VARCHAR
            // and we want to change it to be nullable.
            $table->text('certifications')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            // Revert it back to not nullable
            $table->text('certifications')->nullable(false)->change();
        });
    }
};