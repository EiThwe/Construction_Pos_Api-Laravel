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
        Schema::create('pay_salaries', function (Blueprint $table) {
            $table->id();
            $table->integer("actual_salary");
            $table->enum("type", ["normal", "bonus", "reduce"])->default("normal");
            $table->integer("amount")->nullable();
            $table->foreignId("user_id");
            $table->string("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_salaries');
    }
};
