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
        Schema::create('revenues', function (Blueprint $table) {
            // báo cáo doanh thu
            $table->id();
               $table->date('date')->comment('Ngày ghi nhận doanh thu');
            $table->decimal('amount', 15, 2)->comment('Doanh thu trong ngày');
            $table->string('note')->nullable()->comment('Ghi chú thêm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
