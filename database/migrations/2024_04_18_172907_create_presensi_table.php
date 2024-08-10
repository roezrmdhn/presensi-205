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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_acak')->unique();
            $table->foreignId('id_admin')->constrained('admin')->onDelete('cascade');
            $table->foreignId('id_organisasi')->constrained('organisasi')->onDelete('cascade');
            $table->dateTime('time_start');
            $table->dateTime('time_end');
            $table->string('event_name');
            $table->text('description')->nullable();
            $table->enum('status', ['Selesai', 'Belum'])->default('Belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
