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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
           $table->string('code', 20)->unique()->comment('Mã đơn thuốc, ví dụ: PRES001');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->text('diagnosis')->nullable()->comment('Chẩn đoán bệnh');
            $table->text('note')->nullable()->comment('Ghi chú thêm của bác sĩ');
            $table->enum('status', ['Đang kê', 'Đã duyệt', 'Đã phát thuốc'])->default('Đang kê');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
