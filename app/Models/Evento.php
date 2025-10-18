<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evento
 * 
 * @property int $id_evento
 * @property string $nombre
 * @property string|null $descripcion
 * @property Carbon $fecha_inicio
 * @property Carbon $fecha_fin
 * @property int $capacidad_participantes
 * @property string|null $ubicacion
 * @property string|null $foto
 * @property string $estado (abierto, cerrado, en_progreso, finalizado)
 * @property float|null $precio_inscripcion
 * @property int|null $id_deporte
 * @property int|null $id_cancha
 * @property int|null $id_reserva
 * @property int|null $id_admin
 * 
 * @property Deporte|null $deporte
 * @property Cancha|null $cancha
 * @property Reserva|null $reserva
 * @property User|null $admin
 * @property Collection|ParticipanteEvento[] $participante_eventos
 *
 * @package App\Models
 */
class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';
    public $timestamps = true;

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'capacidad_participantes' => 'int',
        'precio_inscripcion' => 'float',
        'id_deporte' => 'int',
        'id_cancha' => 'int',
        'id_reserva' => 'int',
        'id_admin' => 'int'
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'capacidad_participantes',
        'ubicacion',
        'foto',
        'estado',
        'precio_inscripcion',
        'id_deporte',
        'id_cancha',
        'id_reserva',
        'id_admin'
    ];

    // Relaciones
    public function deporte()
    {
        return $this->belongsTo(Deporte::class, 'id_deporte');
    }

    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'id_cancha');
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    public function participante_eventos()
    {
        return $this->hasMany(ParticipanteEvento::class, 'id_evento');
    }

    // Métodos útiles
    public function contarParticipantes()
    {
        return $this->participante_eventos()->count();
    }

    public function lugaresDisponibles()
    {
        return max(0, $this->capacidad_participantes - $this->contarParticipantes());
    }

    public function estaLleno()
    {
        return $this->lugaresDisponibles() <= 0;
    }

    public function puedeInscribirse()
    {
        return $this->estado === 'abierto' && !$this->estaLleno();
    }
}
