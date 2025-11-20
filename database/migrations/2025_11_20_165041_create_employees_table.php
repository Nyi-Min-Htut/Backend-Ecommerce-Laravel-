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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('phone_number');
            $table->date('dob');
            $table->string('password');
            $table->string('address');
            $table->string('remark');
            $table->string('gender');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_ban')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
