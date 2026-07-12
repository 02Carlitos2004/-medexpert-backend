<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigAIEngine;
use App\Models\LogIA;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIConfigAdminController extends Controller
{
    public function index(): JsonResponse
    {
        $config = ConfigAIEngine::firstOrCreate(['id' => 1], [
            'proveedor_activo' => 'openrouter',
            'modelo' => 'gpt-4o-mini',
            'temperatura' => 0.7,
            'max_tokens' => 2000,
            'timeout' => 30,
            'cache_ttl' => 3600,
            'limite_diario_por_usuario' => 50,
        ]);

        return response()->json($config);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'proveedor_activo' => 'sometimes|string',
            'orden_fallback' => 'sometimes|array',
            'api_key_cifrada' => 'sometimes|string',
            'modelo' => 'sometimes|string',
            'temperatura' => 'sometimes|numeric|min:0|max:2',
            'max_tokens' => 'sometimes|integer|min:100|max:8000',
            'timeout' => 'sometimes|integer|min:5|max:120',
            'cache_ttl' => 'sometimes|integer|min:60',
            'limite_diario_por_usuario' => 'sometimes|integer|min:1',
        ]);

        $config = ConfigAIEngine::first();
        $config->update($validated);

        return response()->json(['message' => 'Configuración actualizada', 'config' => $config]);
    }

    public function logs(Request $request): JsonResponse
    {
        $query = LogIA::with(['user', 'consulta']);

        if ($request->has('proveedor')) {
            $query->where('proveedor', $request->proveedor);
        }

        if ($request->has('exitoso')) {
            $query->where('exitoso', $request->boolean('exitoso'));
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 50));

        return response()->json($logs);
    }

    public function estadisticas(): JsonResponse
    {
        $stats = [
            'total_llamadas' => LogIA::count(),
            'llamadas_hoy' => LogIA::whereDate('created_at', today())->count(),
            'tokens_totales' => LogIA::sum('tokens_salida'),
            'costo_total' => LogIA::sum('costo'),
            'tasa_exito' => LogIA::count() > 0
                ? round(LogIA::where('exitoso', true)->count() / LogIA::count() * 100, 2)
                : 0,
            'latencia_promedio' => LogIA::whereNotNull('latencia_ms')->avg('latencia_ms'),
            'por_proveedor' => LogIA::select('proveedor', LogIA::raw('count(*) as total'), LogIA::raw('sum(tokens_salida) as tokens'), LogIA::raw('sum(costo) as costo'))
                ->groupBy('proveedor')
                ->get(),
        ];

        return response()->json($stats);
    }
}
