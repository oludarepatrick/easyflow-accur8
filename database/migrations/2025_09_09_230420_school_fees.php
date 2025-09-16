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
        Schema::create('school_fees', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->string('term');
            $table->string('session');
            $table->decimal('tuition', 10, 2)->default(0);
            $table->decimal('uniform', 10, 2)->default(0);
            $table->decimal('exam_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fees');
    }
};
