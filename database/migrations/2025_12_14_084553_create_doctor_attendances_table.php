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
        Schema::create('doctor_attendances', function (Blueprint $table) {
            $table->id();
             $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // Link tới user_id của bác sĩ
        $table->date('date'); // Ngày chấm công
        $table->time('check_in')->nullable(); // Giờ đến
        $table->time('check_out')->nullable(); // Giờ về
        $table->string('status')->default('present'); // present: đi làm, late: đi muộn, absent: vắng
        $table->string('note')->nullable(); // Ghi chú (nếu quên chấm công phải báo admin sửa)
        $table->string('shift')->nullable(); // Ca làm việc (sáng, chiều, tối)
        $table->decimal('working_hours', 5, 2)->default(0);
        $table->decimal('overtime_hours', 5, 2)->default(0);
        $table->decimal('total_hours', 5, 2)->default(0);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_attendances');
    }
};
