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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('amount')->default(0);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'debit', 'credit', 'transfer'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable(); // Catatan Pembayaran
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};