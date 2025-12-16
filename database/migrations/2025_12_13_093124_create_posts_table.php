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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề
        $table->string('slug')->unique(); // Đường dẫn thân thiện (VD: so-cuu-dau-tim)
        $table->text('description')->nullable(); // Mô tả ngắn (hiện ở trang chủ)
        $table->longText('content'); // Nội dung chính (soạn thảo văn bản)
        $table->string('image')->nullable(); // Ảnh đại diện bài viết
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Người đăng (Admin/Bác sĩ)
        $table->boolean('is_featured')->default(false); // Bài viết nổi bật (để hiện bên phải)
        $table->enum('status', ['published', 'draft'])->default('published'); // Trạng thái
        $table->integer('views')->default(0); // Lượt xem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
