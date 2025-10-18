<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AscensoDescenso extends Model
{
    use HasFactory;

    protected $table = 'ascensos_descensos';
    protected $primaryKey = 'id_registro';
    protected $fillable = ['id_equipo', 'id_temporada', 'movimiento', 'fecha_registro'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada', 'id_temporada');
    }
}
