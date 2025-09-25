<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**basic', 'bonus', 'loan_repayment',
        'health', 'gross', 'net_pay'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->integer('month'); // 1–12
            $table->integer('year');
            $table->decimal('basic', 10, 2)->nullable();
            $table->decimal('bonus', 10, 2)->nullable();
            $table->decimal('lesson_amount', 10, 2)->nullable();
            $table->decimal('loan_repayment', 10, 2)->nullable();
            $table->decimal('health', 10, 2)->nullable();
            $table->decimal('net_pay', 10, 2)->nullable();
            $table->decimal('gross', 10, 2)->nullable();
            $table->date('date_paid')->nullable();
            $table->enum('status', ['pending','paid'])->default('pending');
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_salaries');
    }
};
