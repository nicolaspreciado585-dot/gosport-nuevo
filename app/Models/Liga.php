<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    use HasFactory;

    protected $table = 'ligas';
    protected $primaryKey = 'id_liga';
    protected $fillable = ['id_temporada', 'nombre', 'descripcion', 'id_deporte', 'estado'];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada', 'id_temporada');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipos_liga', 'id_liga', 'id_equipo');
    }

    public function partidos()
    {
        return $this->hasMany(PartidoLiga::class, 'id_liga', 'id_liga');
    }

    public function tablaPosiciones()
    {
        return $this->hasMany(TablaPosicion::class, 'id_liga', 'id_liga');
    }
}
