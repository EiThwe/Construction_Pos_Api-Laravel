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
            $table->decimal("cost", 20, 2);
            $table->decimal("profit", 20, 2);
            $table->decimal("pay_amount", 20, 2);
            $table->decimal("reduce_amount", 20, 2)->default(0);
            $table->decimal("change", 20, 2);
            $table->decimal("debt_amount", 20, 2);
            $table->decimal("promotion_amount", 20, 2);
            $table->integer("item_count");
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
