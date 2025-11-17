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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_package_id')->nullable()->after('membership_package');
            $table->foreign('membership_package_id')->references('id')->on('membership_packages')->onDelete('set null');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_package_id')->nullable()->after('package');
            $table->foreign('membership_package_id')->references('id')->on('membership_packages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['membership_package_id']);
            $table->dropColumn('membership_package_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['membership_package_id']);
            $table->dropColumn('membership_package_id');
        });
    }
};
