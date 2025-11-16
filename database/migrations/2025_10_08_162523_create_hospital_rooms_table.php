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
        Schema::create('hospital_rooms', function (Blueprint $table) {
            $table->id();
             $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('room_code', 20)->unique();
            $table->string('room_type', 100);
            $table->unsignedInteger('total_beds')->default(1);
            $table->unsignedInteger('occupied_beds')->default(0);
            $table->unsignedInteger('available_beds')->default(0);
            $table->enum('status', ['available', 'in_use', 'cleaning', 'maintenance'])->default('available');
  $table->json('user_ids')->nullable()->after('status')->comment('Danh sách ID bệnh nhân trong phòng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_rooms');
    }
};
