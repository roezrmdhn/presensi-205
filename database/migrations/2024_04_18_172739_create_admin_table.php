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
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Menambahkan kolom email dengan sifat unique
            $table->timestamp('email_verified_at')->nullable(); // Menambahkan kolom verifikasi email
            $table->string('institusi');
            $table->string('departemen');
            $table->string('address');
            $table->string('phone');
            $table->string('more');
            $table->string('password');
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
