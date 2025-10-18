<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $temporadas = Temporada::with('ligas')->orderBy('anio', 'desc')->get();
        return view('temporadas.index', compact('temporadas'));
    }
}

