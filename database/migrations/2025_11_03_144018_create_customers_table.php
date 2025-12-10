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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('nrc')->nullable();
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->string('password');
            $table->text('address');
            $table->text('remark');
            $table->string('gender');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_ban')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
