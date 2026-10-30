<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->foreignId('usuarios_id')->nullable()->after('socios_id')->constrained('usuarios')->nullOnDelete();
            $table->integer('invitados')->default(0)->after('fecha_entrada');
            $table->string('observaciones', 255)->nullable()->after('invitados');
        });
    }

    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropForeign(['usuarios_id']);
            $table->dropColumn(['usuarios_id', 'invitados', 'observaciones']);
        });
    }
};
