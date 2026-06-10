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
        Schema::create('smart_homes', function (Blueprint $table) {
            $table->id();
            $table->boolean('pintu_terbuka')->default(false);
            $table->boolean('asap_terdeteksi')->default(false);
            $table->boolean('hujan_turun')->default(false);
            $table->boolean('jemuran_ditarik')->default(false);
            $table->float('suhu')->nullable()->default(0);
            $table->float('kelembapan')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_homes');
    }
};
