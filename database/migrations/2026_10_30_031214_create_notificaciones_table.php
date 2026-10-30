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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuarios_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('tipo', 50)->default('general');
            $table->string('titulo', 150);
            $table->text('mensaje');
            $table->string('icono', 50)->default('notifications');
            $table->string('color', 50)->default('amber');
            $table->string('enlace', 255)->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
