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
        Schema::create('yearly_sale', function (Blueprint $table) {
            $table->id();
            $table->dateTime("year");
            $table->unsignedBigInteger("vouchers");
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
        Schema::dropIfExists('yearly_sale');
    }
};
