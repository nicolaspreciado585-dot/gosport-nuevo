<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancha; 
use App\Models\Reserva; // Asume este modelo para contar reservas
use App\Models\User;    // Asume este modelo para contar usuarios

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Datos para las tarjetas (contadores)
        // Usamos valores fijos basados en tu vista, o puedes descomentar las líneas para usar Eloquent
        $totalUsuarios = 120; // User::count();
        $totalReservas = 85;  // Reserva::count();
        $totalPagos = "3.500.000"; // Valor mostrado en la tarjeta (string)
        $totalEventos = 12; // Valor mostrado en la tarjeta
        
        // 2. Traemos todas las canchas para la lista/tarjetas inferiores
        $canchas = Cancha::select(
            'id_cancha', 
            'nombre',
            'estado',
            'foto',
            'id_direccion',      
            'id_deporte'         
        )
        // Optimizamos la carga de relaciones (eager loading)
        ->with(['direccion:id_direccion,barrio,calle'])
        ->with(['deporte:id_deporte,nombre'])
        ->get();
        
        // 3. Configuración de datos para los gráficos (El dato que faltaba)
        $chartsConfig = [
            'reservasChart' => [
                'type' => 'bar',
                'data' => [45, 25, 15], 
                'labels' => ["Fútbol", "Tenis", "Básquet"]
            ],
            'pagosChart' => [
                'type' => 'doughnut',
                'data' => [2000000, 1000000, 500000], 
                'labels' => ["Tarjeta", "Efectivo", "Transferencia"]
            ],
            'usuariosChart' => [
                'type' => 'pie',
                'data' => [50, 40, 20, 10], 
                'labels' => ["18-25", "26-35", "36-50", "50+"]
            ],
            'eventosChart' => [
                'type' => 'line',
                'data' => [2, 3, 1, 4, 2, 5], 
                'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio"]
            ]
        ];
        
        return view('dashboard', compact(
            'canchas',
            'totalUsuarios',
            'totalReservas',
            'totalPagos',
            'totalEventos',
            'chartsConfig' // ¡CRÍTICO! Pasamos la configuración del gráfico
        ));
    }
}
