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
        Schema::create('yearly_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vouchers");
            $table->double("total_actual_price");
            $table->double("total");
            $table->double("tax");
            $table->double("net_total");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_sales');
    }
};
