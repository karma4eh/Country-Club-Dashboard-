<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasa_dolar', function (Blueprint $table) {
            $table->id();
            $table->decimal('tasa', 10, 4);
            $table->dateTime('fecha_actualizacion');
            $table->boolean('scraped_today')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasa_dolar');
    }
};
