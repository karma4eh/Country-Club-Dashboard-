<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar fecha_salida para control de aforo y permanencia
        if (!Schema::hasColumn('visitas', 'fecha_salida')) {
            Schema::table('visitas', function (Blueprint $table) {
                $table->dateTime('fecha_salida')->nullable()->after('fecha_entrada');
            });
        }

        // 2. Libro de Novedades e Incidencias de Guardia
        if (!Schema::hasTable('novedades_garita')) {
            Schema::create('novedades_garita', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuarios_id')->constrained('usuarios')->onDelete('cascade');
                $table->string('tipo', 50)->default('Operativa'); // Seguridad, Operativa, Entrega de Turno, Proveedor
                $table->string('titulo', 150);
                $table->text('descripcion');
                $table->dateTime('fecha')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('novedades_garita');
        if (Schema::hasColumn('visitas', 'fecha_salida')) {
            Schema::table('visitas', function (Blueprint $table) {
                $table->dropColumn('fecha_salida');
            });
        }
    }
};
