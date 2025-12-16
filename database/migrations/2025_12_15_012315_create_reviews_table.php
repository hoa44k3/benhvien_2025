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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Người đánh giá (Bệnh nhân)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Người được đánh giá (Bác sĩ - dùng user_id của bác sĩ)
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Gắn với hồ sơ bệnh án nào (để tránh spam, mỗi lần khám chỉ đánh giá 1 lần)
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
            
            $table->tinyInteger('rating')->unsigned()->comment('Điểm sao từ 1-5');
            $table->text('comment')->nullable()->comment('Nội dung đánh giá');
            
            $table->timestamps();

            // Đảm bảo 1 user chỉ đánh giá 1 lần cho 1 hồ sơ bệnh án
            $table->unique(['user_id', 'medical_record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
