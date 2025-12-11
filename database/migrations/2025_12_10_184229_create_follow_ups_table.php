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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('medical_record_id')->nullable()->constrained('medical_records')->nullOnDelete();
    $table->date('date');
    $table->time('time')->nullable();
    $table->enum('status',['upcoming','completed','cancelled'])->default('upcoming');
    $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
