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
            $table->string('code', 20)->unique()->comment('Mã lịch hẹn');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Các thông tin bệnh nhân (có thể null nếu lấy từ profile)
            $table->string('patient_code', 20)->nullable()->comment('Mã bệnh nhân');
            $table->string('patient_name', 255)->comment('Tên bệnh nhân');
            $table->string('patient_phone', 20)->nullable()->comment('Số điện thoại bệnh nhân');
            
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            
            $table->text('reason')->nullable()->comment('Lý do khám');
            
            // 🔥 SỬA: Thêm nullable() ngay tại đây
            $table->text('diagnosis')->nullable()->comment('Chuẩn đoán'); 
            $table->text('notes')->nullable()->comment('Ghi chú'); 
            
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            
            $table->enum('status', ['Đang chờ', 'Đã xác nhận', 'Đang khám', 'Hoàn thành', 'Đã hẹn', 'Hủy'])
                  ->default('Đang chờ');
            
            // 🔥 SỬA: Thêm nullable() ngay tại đây để tránh lỗi 1832 sau này
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            // Lưu tên phòng họp (VD: SmartHospital_LH123)
        $table->string('meeting_room')->nullable()->after('status');
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