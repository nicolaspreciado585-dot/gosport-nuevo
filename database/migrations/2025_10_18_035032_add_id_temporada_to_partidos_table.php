<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_temporada')->nullable()->after('id_torneo');
            
            $table->foreign('id_temporada')
                  ->references('id_temporada')
                  ->on('temporadas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign(['id_temporada']);
            $table->dropColumn('id_temporada');
        });
    }
};