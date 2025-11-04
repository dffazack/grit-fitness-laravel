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
            // Tambahkan kolom 'clients' (sebagai string) setelah 'experience'
            // Kita buat 'nullable' untuk jaga-jaga
            $table->string('clients')->nullable()->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            // Hapus kolom 'clients' jika migrasi di-rollback
            $table->dropColumn('clients');
        });
    }
};