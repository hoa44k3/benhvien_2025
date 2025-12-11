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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
           $table->string('code', 50)->unique();

    // Liên kết
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
    $table->foreignId('medical_record_id')->nullable()->constrained('medical_records')->nullOnDelete();

    // Thanh toán
    $table->decimal('total', 12, 2)->default(0);
    $table->enum('status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
    $table->enum('payment_method', ['cash','bank','momo','vnpay'])->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->decimal('refund_amount', 12, 2)->default(0);

    // Người thao tác
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

    // Ghi chú
    $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
