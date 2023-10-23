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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("phone_number");
            $table->date("date_of_birth");
            $table->enum("gender", ["male", "female"]);
            $table->longText("address");
            $table->enum('position', ['admin', 'staff'])->default('staff');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role',['admin','staff'])->default('staff');
            $table->string('user_photo')->nullable();
            $table->string('photo')->nullable();
            $table->enum("ban_status", ["true", "false"])->default("false");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
