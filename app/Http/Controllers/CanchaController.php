<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanchaController extends Controller
{
    /**
     * Muestra una lista de canchas disponibles para reservar,
     * incluyendo su información detallada de ubicación y deporte.
     * Es llamado por la ruta 'reservas.create'.
     */
    public function index()
    {
        // Consulta para obtener todas las canchas disponibles con todos sus detalles.
        $canchas = DB::table('canchas')
            ->select(
                'canchas.id_cancha', // Necesario para la URL de reserva
                'canchas.nombre as nombre_cancha',
                'canchas.capacidad',
                'canchas.precio_hora',
                'canchas.foto',
                'canchas.estado',
                'deporte.nombre as nombre_deporte',
                'deporte.jugadores_por_equipo',
                'tipocancha.nombre as tipo_cancha',
                'direccion.barrio',
                'direccion.calle',
                'direccion.coordenadas', // Para el enlace de Google Maps
                'localidad.nombre_localidad',
                'ciudad.nombre_ciudad'
            )
            ->join('deporte', 'canchas.id_deporte', '=', 'deporte.id_deporte')
            ->join('tipocancha', 'canchas.id_tipo_cancha', '=', 'tipocancha.id_tipo_cancha')
            ->join('direccion', 'canchas.id_direccion', '=', 'direccion.id_direccion')
            ->join('localidad', 'direccion.id_localidad', '=', 'localidad.id_localidad')
            ->join('ciudad', 'localidad.id_ciudad', '=', 'ciudad.id_ciudad')
            ->where('canchas.estado', '=', 'disponible') // Filtra solo las canchas disponibles
            ->get();

        // Retorna la vista 'canchas.index' (que está debajo) con la lista de canchas.
        return view('canchas.index', compact('canchas'));
    }
}