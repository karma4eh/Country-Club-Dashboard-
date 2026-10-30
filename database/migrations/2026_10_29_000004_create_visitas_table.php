<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socios_id')->constrained('socios')->onDelete('cascade');
            $table->dateTime('fecha_entrada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
