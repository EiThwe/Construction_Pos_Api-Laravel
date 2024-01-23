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
            $table->integer("cost")->default(0);
            $table->integer("profit")->default(0);
            $table->integer("pay_amount")->default(0);
            $table->integer("reduce_amount")->default(0);
            $table->integer("change")->default(0);
            $table->integer("debt_amount")->default(0);
            $table->integer("promotion_amount")->default(0);
            $table->integer("item_count")->default(0);
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
