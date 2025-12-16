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
        Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('patient_code')->nullable()->unique()->comment('Mã bệnh nhân, VD: PT001');
        $table->string('name');
        $table->string('type')->default('patient')->after('id')->comment('doctor, admin, patient');
        $table->integer('age')->nullable()->comment('Tuổi bệnh nhân');
        $table->string('phone')->nullable()->comment('Số điện thoại');
        $table->string('last_visit')->nullable()->comment('Lần khám cuối');
        $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->comment('Trạng thái bệnh nhân');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('address')->nullable();
        $table->string('cccd', 20)->nullable();
        $table->string('occupation')->nullable();
        $table->string('avatar')->nullable();
        $table->string('gender')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->boolean('is_active')->default(true);
        $table->rememberToken();
        $table->timestamps();
});


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['patient_code', 'age', 'phone', 'last_visit', 'status']);
    });
    }
};
