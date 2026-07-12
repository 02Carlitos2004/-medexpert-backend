<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfigAIEngine;
use Illuminate\Http\JsonResponse;

class AIConfigController extends Controller
{
    public function index(): JsonResponse
    {
        $config = ConfigAIEngine::first();

        if (!$config) {
            return response()->json(['message' => 'No hay configuración del AI Engine'], 404);
        }

        return response()->json([
            'proveedor_activo' => $config->proveedor_activo,
            'modelo' => $config->modelo,
            'temperatura' => $config->temperatura,
            'max_tokens' => $config->max_tokens,
            'timeout' => $config->timeout,
            'cache_ttl' => $config->cache_ttl,
            'limite_diario_por_usuario' => $config->limite_diario_por_usuario,
            'orden_fallback' => $config->orden_fallback,
        ]);
    }
}
