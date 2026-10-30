<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socios_id')->constrained('socios')->onDelete('cascade');
            $table->string('descripcion', 255);
            $table->decimal('monto', 10, 2);
            $table->timestamp('fecha_pago')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
