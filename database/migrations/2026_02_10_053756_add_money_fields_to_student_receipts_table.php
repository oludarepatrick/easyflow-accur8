<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_receipts', function (Blueprint $table) {
            $table->decimal('stationeries', 12, 2)->default(0.00)->after('uniform');
            $table->decimal('previous_balance', 12, 2)->default(0.00)->after('amount_paid');
            $table->decimal('external_money', 12, 2)->default(0.00)->after('previous_balance');
        });
    }

    public function down(): void
    {
        Schema::table('student_receipts', function (Blueprint $table) {
            $table->dropColumn([
                'stationeries',
                'previous_balance',
                'external_money'
            ]);
        });
    }
};
