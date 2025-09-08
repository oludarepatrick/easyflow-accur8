<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('schoolname');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo_url')->nullable();

            // Banking details
            $table->string('bank1')->nullable();
            $table->string('accountname1')->nullable();
            $table->string('accountno1')->nullable();

            $table->string('bank2')->nullable();
            $table->string('accountname2')->nullable();
            $table->string('accountno2')->nullable();

            $table->string('bank3')->nullable();
            $table->string('accountname3')->nullable();
            $table->string('accountno3')->nullable();

            // Academic info
            $table->string('term')->nullable();
            $table->string('session')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
