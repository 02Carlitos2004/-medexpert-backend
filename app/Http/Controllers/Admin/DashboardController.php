<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Consulta;
use App\Models\LogIA;
use App\Models\Enfermedad;
use App\Models\Organo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $stats = [
            'usuarios_total' => User::count(),
            'usuarios_nuevos_hoy' => User::whereDate('created_at', today())->count(),
            'consultas_total' => Consulta::count(),
            'consultas_hoy' => Consulta::whereDate('created_at', today())->count(),
            'consultas_completadas' => Consulta::where('estado', 'completada')->count(),
            'consultas_error' => Consulta::where('estado', 'error')->count(),
            'enfermedades_total' => Enfermedad::where('activo', true)->count(),
            'organos_total' => Organo::count(),
            'logs_ia_hoy' => LogIA::whereDate('created_at', today())->count(),
            'tokens_consumidos_hoy' => LogIA::whereDate('created_at', today())->sum('tokens_salida'),
            'costo_total_hoy' => LogIA::whereDate('created_at', today())->sum('costo'),
        ];

        $consultasPorDia = Consulta::select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $consultasPorEstado = Consulta::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        $usuariosPorRol = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        return response()->json([
            'stats' => $stats,
            'consultas_por_dia' => $consultasPorDia,
            'consultas_por_estado' => $consultasPorEstado,
            'usuarios_por_rol' => $usuariosPorRol,
        ]);
    }
}
