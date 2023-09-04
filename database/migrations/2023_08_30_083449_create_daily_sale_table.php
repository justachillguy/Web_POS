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
        Schema::create('daily_sale', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date");
            $table->unsignedInteger("vouchers");
            $table->integer('item count');
            $table->float("cash");
            $table->float("tax");
            $table->float("total");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sale');
    }
};
