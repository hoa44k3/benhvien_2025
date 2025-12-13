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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code', 20)->unique()->comment('Mã nhân viên');
            $table->string('name', 100)->comment('Tên nhân viên');
            $table->string('position', 100)->comment('Chức vụ');
              $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('rating', 2, 1)->nullable();
$table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->enum('status', ['Hoạt động', 'Nghỉ phép', 'Nghỉ việc'])->default('Hoạt động');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
