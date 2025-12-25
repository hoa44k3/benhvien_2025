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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Mã thuốc, ví dụ: MED001');
            $table->string('name', 100)->comment('Tên thuốc');
            $table->string('category', 100)->nullable()->comment('Phân loại, ví dụ: Giảm đau, Kháng sinh...');
            // $table->integer('stock')->unsigned()->default(0)->comment('Số lượng tồn kho');
            // $table->integer('min_stock')->unsigned()->default(0)->comment('Ngưỡng cảnh báo tồn tối thiểu');
            $table->string('unit', 50)->default('Viên')->comment('Đơn vị tính');
            // $table->decimal('price', 15, 2)->default(0)->comment('Giá bán hoặc giá nhập');
            // $table->date('expiry_date')->nullable()->comment('Hạn sử dụng thuốc');
            // $table->enum('status', ['Trống', 'Sắp hết', 'Hết hạn'])->default('Trống')->comment('Trạng thái thuốc');
            // $table->string('supplier', 100)->nullable()->comment('Nhà cung cấp thuốc (nếu có)');
            $table->unsignedBigInteger('medicine_category_id')->nullable()->after('category');
         $table->unsignedBigInteger('medicine_unit_id')->nullable()->after('unit');
              // Thêm khóa ngoại mới
            $table->foreignId('medicine_category_id')
                ->nullable()
                ->after('name')
                ->constrained('medicine_categories')
                ->nullOnDelete()
                ->comment('Danh mục thuốc');

            $table->foreignId('medicine_unit_id')
                ->nullable()
                ->after('medicine_category_id')
                ->constrained('medicine_units')
                ->nullOnDelete()
                ->comment('Đơn vị thuốc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
