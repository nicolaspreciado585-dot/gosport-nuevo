<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\TablaPosicion;

class StandingsController extends Controller
{
    public function show($id)
    {
        $liga = Liga::findOrFail($id);
        $tabla = TablaPosicion::where('id_liga', $id)
            ->with('equipo')
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia_goles')
            ->get();

        return view('ligas.tabla', compact('liga', 'tabla'));
    }

    public function stats($id)
    {
        $liga = Liga::findOrFail($id);
        $estadisticas = $liga->partidos()
            ->with(['local', 'visitante'])
            ->get();

        return view('ligas.estadisticas', compact('liga', 'estadisticas'));
    }
}
