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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
             // Liên kết hóa đơn
    $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');

    // Liên kết đến dịch vụ / thuốc / gói
    $table->enum('item_type', ['service','medicine','package','other'])->default('service');
    $table->unsignedBigInteger('item_id')->nullable();

    // Thông tin mục tính tiền
    $table->string('description'); 
    $table->integer('quantity')->default(1);
    $table->decimal('unit_price', 12, 2)->default(0);
    $table->decimal('total_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
