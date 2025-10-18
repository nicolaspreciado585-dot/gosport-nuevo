<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoLiga extends Model
{
    use HasFactory;

    protected $table = 'equipos_liga';
    protected $primaryKey = 'id_equipo_liga';
    protected $fillable = ['id_liga', 'id_equipo', 'puntos', 'goles_favor', 'goles_contra', 'partidos_jugados'];

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'id_liga', 'id_liga');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
}
