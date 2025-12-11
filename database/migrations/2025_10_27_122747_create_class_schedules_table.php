<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->integer('quota')->default(0); // Current participants
            $table->integer('max_quota')->default(20);
            $table->enum('type', ['Cardio', 'Strength', 'Yoga', 'HIIT', 'Pilates', 'Boxing', 'Dance', 'Cycling'])->default('Cardio');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
