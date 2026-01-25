<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('booking_date');
            $table->enum('status', ['booked', 'attended', 'cancelled'])->default('booked');
            $table->timestamps();

            // Prevent duplicate bookings
            $table->unique(['class_schedule_id', 'user_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_participants');
    }
};
