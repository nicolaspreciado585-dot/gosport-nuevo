<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /**
     * Muestra una lista de canchas para que el usuario pueda seleccionar una.
     * Mapea a la ruta: GET /reservas/create (reservas.create)
     */
    public function createEmpty()
    {
        $canchas = Cancha::where('estado', 'disponible')->get();
        // NOTA: Debes crear la vista 'reservas.seleccionar_cancha' si usas esta ruta.
        return view('reservas.seleccionar_cancha', compact('canchas'));
    }

    /**
     * Muestra el formulario de reserva para la cancha seleccionada,
     * cargando los horarios disponibles para hoy.
     * Mapea a la ruta: GET /reservas/create/{id_cancha} (reservas.create.cancha)
     */
    public function create(Cancha $cancha)
    {
        // 1. CARGA DE RELACIONES CORREGIDA: 
        // Forzamos la carga de las relaciones y especificamos 'calle' en lugar de 'direccion'.
        $cancha->load([
            'direccion' => function ($query) {
                // Seleccionamos id_direccion (necesario), barrio, y la columna 'calle'
                $query->select('id_direccion', 'barrio', 'calle'); 
            },
            'deporte' => function ($query) {
                $query->select('id_deporte', 'nombre');
            }
        ]);
        
        // 2. Definir horarios fijos de 8:00 a 22:00
        $horarios = [];
        for ($i = 8; $i <= 22; $i++) {
            $hora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $horarios[] = $hora;
        }

        // 3. Obtener reservas existentes para el día de hoy (para ejemplo visual de ocupación)
        $hoy = Carbon::now()->toDateString();
        
        $reservas_hoy = Reserva::where('id_cancha', $cancha->id_cancha)
            ->whereDate('fecha_inicio', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->get()
            ->map(function ($reserva) {
                // Obtenemos solo la hora de inicio (HH:MM)
                return Carbon::parse($reserva->fecha_inicio)->format('H:i');
            })
            ->toArray();

        return view('reservas.create', [
            'cancha' => $cancha,
            'horarios' => $horarios, // Lista completa de horarios
            'reservas_hoy' => $reservas_hoy, // Lista de horarios ya ocupados
        ]);
    }

    /**
     * Guarda la nueva reserva en la base de datos con validación.
     * Mapea a la nueva ruta: POST /reservas (reservas.store)
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $request->validate([
            'id_cancha' => 'required|exists:canchas,id_cancha', 
            'fecha_reserva' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio', 
        ], [
            'after:hora_inicio' => 'La hora de finalización debe ser posterior a la hora de inicio.',
            'after_or_equal:today' => 'La fecha de reserva no puede ser anterior al día de hoy.',
        ]);

        // 2. Combinar Fecha y Hora en objetos Carbon
        $fecha = $request->input('fecha_reserva');
        $horaInicio = $request->input('hora_inicio');
        $horaFin = $request->input('hora_fin');

        $fechaInicioDT = Carbon::parse("{$fecha} {$horaInicio}");
        $fechaFinDT = Carbon::parse("{$fecha} {$horaFin}");

        // 3. Verificar Disponibilidad
        $reservasExistentes = Reserva::where('id_cancha', $request->id_cancha)
            ->where('estado', '!=', 'cancelada')
            ->where(function ($query) use ($fechaInicioDT, $fechaFinDT) {
                $query->where(function ($q) use ($fechaInicioDT, $fechaFinDT) {
                    $q->where('fecha_inicio', '>=', $fechaInicioDT)
                      ->where('fecha_inicio', '<', $fechaFinDT);
                })->orWhere(function ($q) use ($fechaInicioDT, $fechaFinDT) {
                    $q->where('fecha_fin', '>', $fechaInicioDT)
                      ->where('fecha_fin', '<=', $fechaFinDT);
                })->orWhere(function ($q) use ($fechaInicioDT, $fechaFinDT) {
                    $q->where('fecha_inicio', '<', $fechaInicioDT)
                      ->where('fecha_fin', '>', $fechaFinDT);
                });
            })
            ->count();

        if ($reservasExistentes > 0) {
            return back()->withErrors(['reserva' => 'Lo sentimos, la cancha ya está reservada en el horario seleccionado.'])->withInput();
        }

        // 4. Crear el registro de la reserva
        Reserva::create([
            'id_usuario' => Auth::id(),
            'id_cancha' => $request->id_cancha,
            'fecha_inicio' => $fechaInicioDT,
            'fecha_fin' => $fechaFinDT,
            'estado' => 'pendiente',
        ]);

        // 5. Redirigir a la vista de confirmación
        return redirect()->route('reservas.confirmacion')->with('ok', 'Reserva creada con éxito. Proceda al pago para confirmarla.');
    }
}