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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // bệnh nhân
        $table->string('title'); // tiêu đề hồ sơ, ví dụ: "Khám tổng quát"
        $table->date('date'); // ngày khám
       $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete(); // bác sĩ thực hiện
    $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // khoa chuyên môn
        $table->text('diagnosis')->nullable(); // chẩn đoán
        $table->text('treatment')->nullable(); // điều trị
        $table->date('next_checkup')->nullable(); // tái khám
 $table->unsignedBigInteger('appointment_id')->nullable()->after('department_id');

        $table->foreign('appointment_id')
            ->references('id')->on('appointments')
            ->onDelete('set null');
        // $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
        $table->text('symptoms')->nullable();
        $table->json('vital_signs')->nullable(); // {"temp":"37.0","bp":"120/80","hr":"72"}
        $table->string('diagnosis_primary')->nullable();
        $table->string('diagnosis_secondary')->nullable();
        $table->enum('status', [
            'chờ_khám',
            'đang_khám',
            'đã_khám',
            'hủy'
        ])->default('chờ_khám');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
