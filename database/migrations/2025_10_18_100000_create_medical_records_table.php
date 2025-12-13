// NỘI DUNG MỚI CHO: 2025_12_02_172108_create_medical_records_table.php

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // bệnh nhân
            $table->string('title'); // tiêu đề hồ sơ
            $table->date('date'); // ngày khám
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete(); // bác sĩ thực hiện
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // khoa chuyên môn
            
            // 🔥 SỬA LỖI 1 (Cú pháp after) & LỖI 2 (Trùng khóa ngoại):
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->foreign('appointment_id', 'medical_records_app_fk_new') // Đổi tên khóa ngoại
                  ->references('id')->on('appointments')
                  ->onDelete('set null');
                  
            $table->text('diagnosis')->nullable(); // chẩn đoán
            $table->text('treatment')->nullable(); // điều trị
            $table->date('next_checkup')->nullable(); // tái khám
            $table->text('symptoms')->nullable();
            $table->json('vital_signs')->nullable(); // {"temp":"37.0","bp":"120/80","hr":"72"}
            $table->string('diagnosis_primary')->nullable();
            $table->string('diagnosis_secondary')->nullable();
            $table->enum('status', [
                'chờ_khám', 'đang_khám', 'đã_khám', 'hủy'
            ])->default('chờ_khám');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};