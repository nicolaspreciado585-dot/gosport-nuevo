<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\Temporada;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        $ligas = Liga::with('temporada')->paginate(6);
        return view('ligas.index', compact('ligas'));
    }

    public function show($id)
    {
        $liga = Liga::with(['temporada', 'partidos.local', 'partidos.visitante', 'tablaPosiciones.equipo'])
            ->findOrFail($id);

        return view('ligas.show', compact('liga'));
    }
}
