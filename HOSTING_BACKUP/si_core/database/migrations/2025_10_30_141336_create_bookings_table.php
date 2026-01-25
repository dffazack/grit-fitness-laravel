<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_bookings_table.php

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained('class_schedules')->onDelete('cascade');
            $table->date('booking_date'); // Tanggal kelas yang dibooking
            $table->string('status')->default('confirmed'); // misal: confirmed, cancelled
            $table->timestamps(); // Kapan booking ini dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
