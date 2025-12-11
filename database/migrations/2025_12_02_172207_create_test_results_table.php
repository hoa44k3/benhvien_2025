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
        Schema::create('test_results', function (Blueprint $table) {
               $table->id();//thông tin xét nghiệm

        // Bệnh nhân
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Bác sĩ phụ trách
        $table->foreignId('doctor_id')->nullable()
            ->constrained('users')->nullOnDelete();

        // Khoa
        $table->foreignId('department_id')->nullable()
            ->constrained('departments')->nullOnDelete();

        // Thông tin xét nghiệm
        $table->string('lab_name');                // Tên phòng xét nghiệm
        $table->string('test_name');               // Tên xét nghiệm
        $table->date('date');                      // Ngày xét nghiệm
        $table->string('result');                  // Kết quả
        $table->string('unit')->nullable();        // Đơn vị đo
        $table->string('normal_range')->nullable(); // Giá trị tham chiếu
        $table->string('evaluation')->nullable();   // Bác sĩ đánh giá kết quả
        $table->text('note')->nullable();           // Ghi chú thêm

        // File PDF/ảnh của kết quả xét nghiệm
        $table->string('file_main')->nullable();

        // Trạng thái xét nghiệm
        $table->enum('status', [
            'pending',      // Chờ kết quả
            'completed',    // Đã có kết quả
            'reviewed',     // Bác sĩ đã duyệt
            'archived'      // Lưu trữ
        ])->default('pending');

        // Người tạo (admin, bác sĩ)
        $table->foreignId('created_by')->nullable()
            ->constrained('users')->nullOnDelete();

        $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
