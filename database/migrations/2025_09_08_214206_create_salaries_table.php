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
        Schema::create('salaries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
        $table->decimal('basic', 10, 2)->default(0);
        $table->decimal('bonus', 10, 2)->default(0);
        $table->decimal('loan_repayment', 10, 2)->default(0);
        $table->decimal('health', 10, 2)->default(0);
        $table->decimal('gross', 10, 2)->default(0);
        $table->decimal('net_pay', 10, 2)->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
