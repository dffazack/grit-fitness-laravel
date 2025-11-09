<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop the unique constraint
        Schema::table('membership_packages', function (Blueprint $table) {
            $table->dropUnique('membership_packages_type_unique');
        });

        // Step 2: Update existing data to 'regular'
        DB::table('membership_packages')
            ->whereIn('type', ['basic', 'premium', 'vip'])
            ->update(['type' => 'regular']);

        // Step 3: Alter the column to the new ENUM definition
        DB::statement("ALTER TABLE membership_packages MODIFY COLUMN type ENUM('regular', 'student') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this is complex and potentially data-destructive.

        // Step 1: Change ENUM to allow old values back
        DB::statement("ALTER TABLE membership_packages MODIFY COLUMN type ENUM('regular', 'student', 'basic', 'premium', 'vip') NOT NULL");

        // Step 2: Revert 'regular' types to a default 'basic'
        // We can't know the original type, so this is a best-effort rollback.
        DB::table('membership_packages')
            ->where('type', 'regular')
            ->update(['type' => 'basic']);
        
        // Step 3: Remove 'regular' and 'student' from ENUM, leaving original values
        DB::statement("ALTER TABLE membership_packages MODIFY COLUMN type ENUM('basic', 'premium', 'vip') NOT NULL");

        // Step 4: Re-add the unique constraint. This might fail if data is not unique.
        Schema::table('membership_packages', function (Blueprint $table) {
            $table->unique('type');
        });
    }
};