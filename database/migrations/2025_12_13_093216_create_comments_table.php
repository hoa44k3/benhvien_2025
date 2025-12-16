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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
        $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); // Thuộc bài nào
        $table->unsignedBigInteger('parent_id')->nullable(); // 🔥 Quan trọng: Để trả lời bình luận khác
        $table->string('name'); // Tên người bình luận (Khách)
        $table->string('email')->nullable(); // Email (để hiện avatar Gravatar nếu muốn)
        $table->text('content'); // Nội dung
        $table->timestamps(); // Lưu ngày giờ tự động
    $table->enum('status', ['pending', 'approved'])
          ->default('pending')
          ->comment('pending: chờ duyệt, approved: đã duyệt');
          // 👁️ Ẩn / hiện bình luận
    $table->boolean('is_visible')
          ->default(true)
          ->comment('1: hiển thị, 0: ẩn');
        // Khóa ngoại tự tham chiếu để làm chức năng Reply
        $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        // Thêm cột approved_by, cho phép null (vì lúc mới comment chưa ai duyệt)
        $table->unsignedBigInteger('approved_by')->nullable()->after('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
