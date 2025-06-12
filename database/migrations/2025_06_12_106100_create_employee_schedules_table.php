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
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('availability_id')->nullable()->constrained('employee_availabilities')->onDelete('set null');
            $table->date('date');
            $table->string('start_time', 5); // Format HH:MM
            $table->string('end_time', 5);   // Format HH:MM
            $table->enum('status', ['pending', 'confirmed', 'completed'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Un employé ne peut pas avoir plusieurs horaires de travail qui se chevauchent
            $table->unique(['user_id', 'restaurant_id', 'date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
