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
        Schema::create('clinical_exams', function (Blueprint $table) {
            $table->id();
           // Liên kết
    $table->foreignId('medical_record_id')
          ->constrained('medical_records')
          ->onDelete('cascade');

    $table->foreignId('entered_by')
          ->nullable()
          ->constrained('users')
          ->nullOnDelete();

    // Thông số sinh tồn
    $table->decimal('temperature', 5, 2)->nullable();
    $table->string('blood_pressure')->nullable();     // "120/80"
    $table->integer('pulse')->nullable();             // Nhịp tim
    $table->integer('respiratory_rate')->nullable();  // Nhịp thở
    $table->integer('spo2')->nullable();              // O2 saturation %
    $table->decimal('weight', 8, 2)->nullable();
    $table->decimal('height', 8, 2)->nullable();
    $table->decimal('bmi', 5, 2)->nullable();

    // Loại khám
    $table->string('exam_type')->nullable();

    // Ghi chú
    $table->text('notes')->nullable();

    // Dữ liệu mở rộng
    $table->json('measurements')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_exams');
    }
};
