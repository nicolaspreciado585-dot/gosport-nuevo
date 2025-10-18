<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\PartidoLiga;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function calendar($id)
    {
        $liga = Liga::with(['partidos.local', 'partidos.visitante'])->findOrFail($id);
        $partidos = $liga->partidos->sortBy('fecha');

        return view('ligas.calendario', compact('liga', 'partidos'));
    }

    public function byRound($id, $jornada = null)
    {
        $liga = Liga::findOrFail($id);
        $query = PartidoLiga::where('id_liga', $id);
        if ($jornada) {
            $query->where('jornada', $jornada);
        }
        $partidos = $query->with(['local', 'visitante'])->get();

        return view('ligas.partidos', compact('liga', 'partidos', 'jornada'));
    }

    public function storeResult(Request $request, $liga, $partido)
    {
        $request->validate([
            'goles_local' => 'required|integer|min:0',
            'goles_visitante' => 'required|integer|min:0',
        ]);

        $match = PartidoLiga::findOrFail($partido);
        $match->update([
            'goles_local' => $request->goles_local,
            'goles_visitante' => $request->goles_visitante,
            'estado' => 'Finalizado'
        ]);

        return back()->with('success', 'Resultado actualizado correctamente.');
    }
}
