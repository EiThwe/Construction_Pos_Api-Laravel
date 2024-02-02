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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("voucher_number");
            $table->integer("cost");
            $table->integer("profit");
            $table->integer("pay_amount");
            $table->integer("reduce_amount");
            $table->integer("change");
            $table->integer("debt_amount");
            $table->integer("promotion_amount");
            $table->foreignId("user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
