<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            if (!Schema::hasColumn('eventos', 'fecha_inicio')) {
                $table->dateTime('fecha_inicio')->nullable()->after('descripcion');
            }
            if (!Schema::hasColumn('eventos', 'fecha_fin')) {
                $table->dateTime('fecha_fin')->nullable()->after('fecha_inicio');
            }
            if (!Schema::hasColumn('eventos', 'capacidad_participantes')) {
                $table->integer('capacidad_participantes')->default(0)->after('fecha_fin');
            }
            if (!Schema::hasColumn('eventos', 'estado')) {
                $table->string('estado')->default('abierto')->after('capacidad_participantes');
            }
            if (!Schema::hasColumn('eventos', 'precio_inscripcion')) {
                $table->decimal('precio_inscripcion', 10, 2)->nullable()->default(0)->after('estado');
            }
            if (!Schema::hasColumn('eventos', 'ubicacion')) {
                $table->string('ubicacion')->nullable()->after('precio_inscripcion');
            }
            if (!Schema::hasColumn('eventos', 'id_cancha')) {
                $table->unsignedBigInteger('id_cancha')->nullable()->after('id_deporte');
            }
            if (!Schema::hasColumn('eventos', 'id_admin')) {
                $table->unsignedBigInteger('id_admin')->nullable()->after('id_cancha');
            }
            if (!Schema::hasColumn('eventos', 'created_at')) {
                $table->timestamps();
            }

            // Llaves foráneas
            $table->foreign('id_cancha')->references('id_cancha')->on('canchas')->onDelete('set null');
            $table->foreign('id_deporte')->references('id_deporte')->on('deportes')->onDelete('set null');
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $columnsToRemove = ['fecha_inicio', 'fecha_fin', 'capacidad_participantes', 'estado', 'precio_inscripcion', 'ubicacion', 'id_cancha', 'id_admin'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('eventos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
