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
        Schema::create('service_steps', function (Blueprint $table) {
            $table->id();
        $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            $table->text('title');       // Tên bước
            $table->text('description')->nullable(); // Mô tả
            $table->string('image')->nullable();     // 🔥 THÊM CỘT ẢNH (Lưu đường dẫn file)
            $table->integer('step_order')->default(0); // Thứ tự
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_steps');
    }
};
