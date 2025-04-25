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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->integer('queue_number');
            $table->enum('status', ['Menunggu', 'Dalam Pemeriksaan', 'Selesai'])->default('Menunggu');
            $table->text('complaint')->nullable();
            $table->integer('daily_quota')->default(20)->after('clinic_end_time'); // Kuota harian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};