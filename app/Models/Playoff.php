<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playoff extends Model
{
    use HasFactory;

    protected $table = 'playoffs';
    protected $primaryKey = 'id_playoff';
    protected $fillable = ['id_temporada', 'ronda', 'id_equipo_local', 'id_equipo_visitante', 'goles_local', 'goles_visitante', 'fecha'];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada', 'id_temporada');
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
