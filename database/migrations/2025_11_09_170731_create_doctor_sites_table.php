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
        Schema::create('doctor_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('specialization')->nullable()->comment('Chuyên khoa chính');
             // Số năm kinh nghiệm
            $table->integer('experience_years')->default(0)->comment('Số năm kinh nghiệm');
            $table->text('bio')->nullable()->comment('Giới thiệu ngắn');
            $table->decimal('rating', 2, 1)->default(0)->comment('Điểm trung bình đánh giá');
            $table->integer('reviews_count')->default(0)->comment('Số lượt đánh giá');
            $table->string('image')->nullable()->comment('Ảnh bác sĩ');
            $table->boolean('status')->default(1)->comment('1 = hoạt động, 0 = ẩn');
            // Lương cứng
        $table->decimal('base_salary', 15, 2)->default(0)->after('status')->comment('Lương cứng hàng tháng');
        
        // % Hoa hồng
        $table->float('commission_exam_percent')->default(0)->after('base_salary')->comment('% Hoa hồng phí khám');
        $table->float('commission_prescription_percent')->default(0)->after('commission_exam_percent')->comment('% Hoa hồng tiền thuốc');
        $table->float('commission_service_percent')->default(0)->after('commission_prescription_percent')->comment('% Hoa hồng dịch vụ CLS');
$table->integer('max_patients')->default(20)->after('specialization'); // Số bệnh nhân tối đa mỗi ngày
        // Thông tin tài khoản nhận lương
    $table->string('bank_name')->nullable(); // Ví dụ: Vietcombank
    $table->string('bank_account_number')->nullable(); // STK: 0011...
    $table->string('bank_account_holder')->nullable(); // Tên chủ TK
// Thêm sau cột image
            $table->string('degree')->nullable()->after('image')->comment('Học vị: TS, ThS, BS.CKII...');
            $table->string('license_number')->nullable()->after('degree')->comment('Số chứng chỉ hành nghề');
            $table->string('license_issued_by')->nullable()->after('license_number')->comment('Nơi cấp chứng chỉ/đào tạo');
            $table->string('license_image')->nullable()->after('license_issued_by')->comment('Ảnh chụp chứng chỉ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_sites');
    }
};
