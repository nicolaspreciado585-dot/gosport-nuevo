<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    use HasFactory;

    protected $table = 'temporadas';
    protected $primaryKey = 'id_temporada';
    protected $fillable = ['nombre', 'anio', 'categoria', 'estado'];

    public function ligas()
    {
        return $this->hasMany(Liga::class, 'id_temporada', 'id_temporada');
    }
}
