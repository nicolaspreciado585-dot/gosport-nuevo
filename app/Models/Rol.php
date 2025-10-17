<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // <-- Aseguramos la importación del modelo User

/**
 * Class Rol
 * * @property int $id_rol
 * @property string|null $nombre_rol
 * * @property Collection|User[] $users // <-- Comentario actualizado
 *
 * @package App\Models
 */
class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol'
    ];

    public function users() // <-- Renombrado a 'users' por convención
    {
        // CAMBIO: Apunta a la clase de modelo correcta 'User::class'
        return $this->hasMany(User::class, 'id_rol');
    }
}