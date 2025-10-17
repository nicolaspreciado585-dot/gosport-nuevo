<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReservaController extends Controller
{
    /**
     * Muestra una lista de todas las reservas.
     */
    public function index()
    {
        $reservas = Reserva::with([
            'usuario', 
            'cancha' => function ($query) {
                $query->with(['deporte', 'direccion' => function ($q) {
                    $q->select('id_direccion', 'calle', 'barrio');
                }]);
            }
        ])
        ->orderBy('fecha_inicio', 'desc')
        ->get();

        return view('reservas.admin.index', compact('reservas'));
    }

    /**
     * Muestra el formulario para editar la reserva.
     */
    public function edit(Reserva $reserva)
    {
        $reserva->load(['usuario', 'cancha' => function ($query) {
            $query->with(['deporte', 'direccion']);
        }]);

        $estados = ['pendiente', 'confirmada', 'cancelada', 'finalizada'];

        return view('reservas.admin.edit', compact('reserva', 'estados'));
    }

    /**
     * Actualiza la reserva: estado y horario, evitando solapamientos.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,finalizada',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        // Verificar solapamiento de reservas en la misma cancha
        $solapamiento = Reserva::where('id_cancha', $reserva->id_cancha)
            ->where('id_reserva', '!=', $reserva->id_reserva)
            ->where(function($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhere(function($q) use ($request) {
                          $q->where('fecha_inicio', '<=', $request->fecha_inicio)
                            ->where('fecha_fin', '>=', $request->fecha_fin);
                      });
            })
            ->exists();

        if ($solapamiento) {
            return back()
                ->withErrors(['fecha_inicio' => 'El horario seleccionado se solapa con otra reserva en esta cancha.'])
                ->withInput();
        }

        // Actualizar datos
        $reserva->estado = $request->estado;
        $reserva->fecha_inicio = $request->fecha_inicio;
        $reserva->fecha_fin = $request->fecha_fin;
        $reserva->save();

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    /**
     * Elimina la reserva de la base de datos.
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva eliminada permanentemente.');
    }

    /**
     * Genera un informe de reservas.
     */
    public function reporte()
    {
        $reservas = Reserva::with(['usuario', 'cancha'])->get();

        $datosInforme = $reservas->map(function ($reserva) {
            return [
                'ID' => $reserva->id_reserva,
                'Fecha Inicio' => $reserva->fecha_inicio->format('d/M/Y H:i'),
                'Fecha Fin' => $reserva->fecha_fin->format('d/M/Y H:i'),
                'Cancha' => $reserva->cancha->nombre ?? 'Cancha Eliminada',
                'Usuario' => $reserva->usuario->nombre ?? 'Usuario Eliminado',
                'Estado' => $reserva->estado,
            ];
        });

        return view('reservas.admin.reporte', [
            'datosInforme' => $datosInforme,
            'fechaGeneracion' => Carbon::now()
        ]);
    }
}
