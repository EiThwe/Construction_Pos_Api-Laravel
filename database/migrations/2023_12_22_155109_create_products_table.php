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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("name");
            $table->float("actual_price");
            $table->string("primary_unit");
            $table->float("primary_price");
            $table->text("remark")->nullable();
            $table->float("stock");
            $table->foreignId("user_id");
            $table->foreignId("promotion_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
