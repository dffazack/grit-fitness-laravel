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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('membership_package_id')->nullable()->after('transaction_code')->constrained('membership_packages');
            $table->dropColumn('package');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['membership_package_id']);
            $table->dropColumn('membership_package_id');
            $table->enum('package', ['basic', 'premium', 'vip'])->after('transaction_code');
        });
    }
};
