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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
             $table->string('name', 150)->comment('Tên dịch vụ, ví dụ: Khám tổng quát');
            $table->text('description')->nullable()->comment('Mô tả chi tiết dịch vụ');
             $table->text('content')->nullable()->comment('Mô tả chi tiết dịch vụ, hiển thị trang người dùng');
            $table->decimal('fee', 15, 2)->default(0)->comment('Phí khám');
            $table->integer('duration')->default(30)->comment('Thời gian dịch vụ (phút)');
            $table->tinyInteger('status')->default(1)->comment('Trạng thái: 1 active, 0 inactive');
            $table->string('image')->nullable()->comment('Ảnh minh họa dịch vụ');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
