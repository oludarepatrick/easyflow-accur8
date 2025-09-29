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
        Schema::table('staff_salaries', function (Blueprint $table) {
             $table->decimal('tax_deduction', 10, 2)
                  ->nullable()
                  ->default(0.00)
                  ->after('net_pay');

            $table->decimal('social_deduction', 10, 2)
                  ->nullable()
                  ->default(0.00)
                  ->after('tax_deduction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->dropColumn(['tax_deduction', 'social_deduction']);
        });
    }
};
