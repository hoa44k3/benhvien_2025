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
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('Tên danh mục (Kháng sinh, Giảm đau...)');
            $table->text('description')->nullable()->comment('Mô tả chi tiết danh mục');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_categories');
    }
};
