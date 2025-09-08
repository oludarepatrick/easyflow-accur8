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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('category', ['student', 'staff'])->nullable()->after('id');
            $table->string('firstname')->after('id');
            $table->string('lastname')->after('firstname');
            $table->string('class')->nullable()->after('lastname');
            $table->string('phone')->nullable()->after('email'); // ✅ only phone goes after email
            $table->string('term')->nullable()->after('phone');
            $table->string('session')->nullable()->after('term');
            $table->enum('status', ['active', 'inactive'])->default('active');
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname','lastname','class','phone','term','session', 'status','category']);
        });
    }
};
