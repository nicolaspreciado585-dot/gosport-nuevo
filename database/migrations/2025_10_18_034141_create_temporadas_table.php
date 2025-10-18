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
        Schema::create('temporadas', function (Blueprint $table) {
            $table->id('id_temporada');
            $table->string('nombre', 100); // Ej: "Temporada 2025 - Primer Semestre"
            $table->integer('id_torneo')->unsigned(); // Relación con torneos (que usaremos como ligas)
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activa', 'finalizada', 'pendiente'])->default('pendiente');
            $table->integer('numero_jornadas')->default(0); // Cantidad de fechas/jornadas
            $table->integer('jornada_actual')->default(1); // Jornada en curso
            $table->timestamps();

            // Relación con torneos
            $table->foreign('id_torneo')
                  ->references('id_torneo')
                  ->on('torneos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporadas');
    }
};