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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('position')->nullable();
            $table->enum('role', ["admin", "cashier", "manager", "staff"]);
            $table->enum('gender', ["ကျား", "မ"]);
            $table->integer('salary')->nullable();
            $table->timestamp("birth_date")->nullable();
            $table->timestamp("join_date")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
