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
        Schema::table('notifications', function (Blueprint $table) {
            // Tambahkan kolom 'content' (tipe TEXT agar muat banyak)
            // 'nullable' berarti boleh kosong
            // 'after' menempatkannya setelah kolom 'title' (opsional, tapi rapi)
            $table->text('content')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus kolom 'content' jika migrasi di-rollback
            $table->dropColumn('content');
        });
    }
};
