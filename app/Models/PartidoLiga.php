<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoLiga extends Model
{
    use HasFactory;

    protected $table = 'partidos_liga';
    protected $primaryKey = 'id_partido_liga';
    protected $fillable = ['id_liga', 'id_equipo_local', 'id_equipo_visitante', 'fecha', 'hora', 'jornada', 'goles_local', 'goles_visitante', 'estado'];

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'id_liga', 'id_liga');
    }

    public function local()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_local', 'id_equipo');
    }

    public function visitante()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_visitante', 'id_equipo');
    }
}
