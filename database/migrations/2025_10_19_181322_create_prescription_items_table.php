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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
             $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            $table->string('medicine_name', 100)->comment('Tên thuốc được kê');
            $table->string('dosage', 100)->nullable()->comment('Liều dùng, ví dụ: 2 viên/lần');
            $table->string('frequency', 100)->nullable()->comment('Số lần dùng trong ngày, ví dụ: 3 lần/ngày');
            $table->string('duration', 100)->nullable()->comment('Thời gian dùng, ví dụ: 5 ngày');
            $table->integer('quantity')->default(1)->comment('Số lượng thuốc');
            $table->decimal('price', 15, 2)->nullable()->comment('Giá bán lẻ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
