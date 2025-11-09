<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['regular', 'student'])->default('regular');
            $table->string('name', 100);
            $table->decimal('price', 10, 2);
            $table->integer('duration_months');
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false)->comment('Recommended badge');
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_packages');
    }
};