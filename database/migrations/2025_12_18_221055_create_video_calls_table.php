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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
        $table->foreignId('doctor_id')->constrained('users');
        $table->foreignId('patient_id')->constrained('users');
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->string('duration')->nullable(); // Lưu dạng "15 phút 30 giây"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
