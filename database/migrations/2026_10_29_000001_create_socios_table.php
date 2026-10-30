<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->text('direccion')->nullable();
            $table->string('cedula', 20)->unique();
            $table->string('numero', 30)->nullable();
            $table->string('correo', 150)->unique();
            $table->string('accion', 50)->unique();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->decimal('saldo', 10, 2)->default(0.00);
            $table->dateTime('ultimo_mes_pagado')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('socio_desde')->nullable();
            $table->date('vencimiento')->nullable();
            $table->string('imagen_perfil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
