<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Deporte;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with(['deporte', 'cancha', 'admin'])
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $deportes = Deporte::orderBy('nombre')->get(['id_deporte', 'nombre']);
        $canchas = Cancha::where('estado', 'disponible')
            ->orderBy('nombre')
            ->get(['id_cancha', 'nombre']);

        return view('eventos.create', compact('deportes', 'canchas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:now',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'capacidad_participantes' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:abierto,cerrado,en_progreso,finalizado',
            'precio_inscripcion' => 'nullable|numeric|min:0',
            'id_deporte' => 'nullable|integer|exists:deporte,id_deporte', // CORREGIDO
            'id_cancha' => 'nullable|integer|exists:canchas,id_cancha',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('eventos', 'public');
        }

        Evento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'capacidad_participantes' => $request->capacidad_participantes,
            'ubicacion' => $request->ubicacion,
            'foto' => $fotoPath,
            'estado' => $request->estado,
            'precio_inscripcion' => $request->precio_inscripcion ?? 0,
            'id_deporte' => $request->id_deporte,
            'id_cancha' => $request->id_cancha,
            'id_admin' => Auth::id(),
        ]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado correctamente.');
    }

    public function show(Evento $evento)
    {
        $evento->load(['deporte', 'cancha', 'admin', 'participante_eventos']);
        $participanteCount = $evento->contarParticipantes();
        $lugaresDisponibles = $evento->lugaresDisponibles();

        return view('eventos.show', compact('evento', 'participanteCount', 'lugaresDisponibles'));
    }

    public function edit(Evento $evento)
    {
        $deportes = Deporte::orderBy('nombre')->get(['id_deporte', 'nombre']);
        $canchas = Cancha::where('estado', 'disponible')
            ->orderBy('nombre')
            ->get(['id_cancha', 'nombre']);

        return view('eventos.edit', compact('evento', 'deportes', 'canchas'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'capacidad_participantes' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:abierto,cerrado,en_progreso,finalizado',
            'precio_inscripcion' => 'nullable|numeric|min:0',
            'id_deporte' => 'nullable|integer|exists:deporte,id_deporte', // CORREGIDO
            'id_cancha' => 'nullable|integer|exists:canchas,id_cancha',
        ]);

        $fotoPath = $evento->foto;
        if ($request->hasFile('foto')) {
            if ($evento->foto) {
                Storage::disk('public')->delete($evento->foto);
            }
            $fotoPath = $request->file('foto')->store('eventos', 'public');
        }

        $evento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'capacidad_participantes' => $request->capacidad_participantes,
            'ubicacion' => $request->ubicacion,
            'foto' => $fotoPath,
            'estado' => $request->estado,
            'precio_inscripcion' => $request->precio_inscripcion ?? 0,
            'id_deporte' => $request->id_deporte,
            'id_cancha' => $request->id_cancha,
        ]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        try {
            if ($evento->foto) {
                Storage::disk('public')->delete($evento->foto);
            }
            $evento->delete();
            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento eliminado correctamente.');
        } catch (\Throwable $e) {
            return back()->withErrors('No se puede eliminar: tiene registros relacionados.');
        }
    }

    public function reporte()
    {
        $eventos = Evento::with(['deporte', 'cancha', 'admin'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $datosInforme = $eventos->map(function ($evento) {
            return [
                'ID' => $evento->id_evento,
                'Nombre' => $evento->nombre,
                'Deporte' => $evento->deporte->nombre ?? 'N/A',
                'Inicio' => $evento->fecha_inicio->format('d/m/Y H:i'),
                'Fin' => $evento->fecha_fin->format('d/m/Y H:i'),
                'Participantes' => $evento->contarParticipantes() . '/' . $evento->capacidad_participantes,
                'Estado' => ucfirst(str_replace('_', ' ', $evento->estado)),
                'Precio' => '$' . number_format($evento->precio_inscripcion ?? 0, 2),
            ];
        });

        return view('eventos.reporte', [
            'datosInforme' => $datosInforme,
            'totalEventos' => $eventos->count(),
            'fechaGeneracion' => Carbon::now()
        ]);
    }
}
