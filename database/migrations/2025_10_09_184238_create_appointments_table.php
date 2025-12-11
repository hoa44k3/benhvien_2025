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
        Schema::create('appointments', function (Blueprint $table) {
    $table->id();

    // 🔹 Mã lịch hẹn duy nhất
    $table->string('code', 20)->unique()->comment('Mã lịch hẹn');

    // 🔹 Khóa ngoại người đặt lịch (bệnh nhân)
    $table->foreignId('user_id')
        ->constrained('users')
        ->onDelete('cascade')
        ->comment('Người đặt lịch (bệnh nhân)');
         // 🔹 Bác sĩ -> users.id (chính là user có role doctor)
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Bác sĩ phụ trách');

    // 🔹 Thông tin bệnh nhân
    $table->string('patient_code', 20)->nullable()->comment('Mã bệnh nhân');
    $table->string('patient_name')->comment('Tên bệnh nhân');
    $table->string('patient_phone', 20)->nullable()->comment('Số điện thoại bệnh nhân');

    // 🔹 Mối quan hệ chuyên khoa
    $table->foreignId('department_id')
        ->nullable()
        ->constrained('departments')
        ->onDelete('set null')
        ->comment('Chuyên khoa');

    // 🔹 Lý do khám
    $table->text('reason')->nullable()->comment('Lý do khám / triệu chứng ban đầu');

    // 🔹 Bổ sung 2 cột mới
    $table->text('diagnosis')->nullable()->comment('Chuẩn đoán / kết luận của bác sĩ');
    $table->text('notes')->nullable()->comment('Ghi chú thêm của bác sĩ hoặc hệ thống');

    // 🔹 Thông tin cuộc hẹn
    $table->date('date')->nullable()->comment('Ngày hẹn');
    $table->time('time')->nullable()->comment('Giờ hẹn');

    // 🔹 Trạng thái lịch hẹn
    $table->enum('status', [
        'Đang chờ',
        'Đã xác nhận',
        'Đang khám',
        'Hoàn thành',
        'Đã hẹn',
        'Hủy'
    ])->default('Đang chờ')->comment('Trạng thái lịch hẹn');

    // ai duyệt
    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    // ai check-in
    $table->foreignId('checked_in_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
