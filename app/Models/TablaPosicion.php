<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaJugador extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_jugadores';
    protected $primaryKey = 'id_estadistica';
    protected $fillable = ['id_partido_liga', 'id_jugador', 'goles', 'asistencias', 'tarjetas_amarillas', 'tarjetas_rojas', 'minutos_jugados'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'id_jugador', 'id_jugador');
    }

    public function partido()
    {
        return $this->belongsTo(PartidoLiga::class, 'id_partido_liga', 'id_partido_liga');
    }
}
