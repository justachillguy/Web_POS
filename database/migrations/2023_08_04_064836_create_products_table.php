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
            $table->string("name");
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->float('actual_price');
            $table->float('sale_price');
            $table->unsignedBigInteger('total_stock')->default('0');
            $table->string('unit');
            $table->text('more_information')->nullable();
            $table->foreignId('user_id');
            $table->string('photo')->nullable();
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
