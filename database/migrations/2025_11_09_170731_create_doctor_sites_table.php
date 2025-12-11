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
        Schema::create('doctor_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('specialization')->nullable()->comment('Chuyên khoa chính');
             // Số năm kinh nghiệm
            $table->integer('experience_years')->default(0)->comment('Số năm kinh nghiệm');
            $table->text('bio')->nullable()->comment('Giới thiệu ngắn');
            $table->decimal('rating', 2, 1)->default(0)->comment('Điểm trung bình đánh giá');
            $table->integer('reviews_count')->default(0)->comment('Số lượt đánh giá');
            $table->string('image')->nullable()->comment('Ảnh bác sĩ');
            $table->boolean('status')->default(1)->comment('1 = hoạt động, 0 = ẩn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_sites');
    }
};
