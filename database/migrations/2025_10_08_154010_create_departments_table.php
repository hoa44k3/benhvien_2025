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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
              $table->string('code', 20)->unique()->comment('Mã chuyên khoa, VD: SK001');
            $table->string('name', 100)->comment('Tên chuyên khoa hoặc dịch vụ');
            $table->text('description')->nullable()->comment('Mô tả chi tiết chuyên khoa');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Trưởng khoa');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('num_doctors')->default(0)->comment('Số lượng bác sĩ');
            $table->integer('num_nurses')->default(0)->comment('Số lượng y tá');
            $table->integer('num_rooms')->default(0)->comment('Số lượng phòng');
            $table->decimal('fee', 15, 2)->default(0)->comment('Phí khám (VNĐ)');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Trạng thái hoạt động');
            $table->string('image', 255)->nullable()->comment('Ảnh đại diện chuyên khoa (lưu trong storage)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
