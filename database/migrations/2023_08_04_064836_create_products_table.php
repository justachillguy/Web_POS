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
            $table->integer('actual_price');
            $table->integer('sale_price');
            $table->integer('total_stock')->default('0');
            $table->string('unit');
            $table->text('more_information');
            $table->foreignId('user_id');
            $table->string('photo')->nullable();
            // $table->softDeletes();
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
