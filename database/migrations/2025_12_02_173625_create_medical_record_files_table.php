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
        Schema::create('medical_record_files', function (Blueprint $table) {
             $table->id(); // ảnh/phiếu kèm theo
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');

            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('file_type')->nullable(); 
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('status', ['active', 'archived'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_record_files');
    }
};
