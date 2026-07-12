<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogIA;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = LogIA::with(['user', 'consulta']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('proveedor')) {
            $query->where('proveedor', $request->proveedor);
        }

        if ($request->has('exitoso')) {
            $query->where('exitoso', $request->boolean('exitoso'));
        }

        if ($request->has('fecha_desde')) {
            $query->where('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta')) {
            $query->where('created_at', '<=', $request->fecha_hasta);
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 50));

        return response()->json($logs);
    }

    public function show($id): JsonResponse
    {
        $log = LogIA::with(['user', 'consulta'])->find($id);

        if (!$log) {
            return response()->json(['message' => 'Log no encontrado'], 404);
        }

        return response()->json($log);
    }

    public function errores(Request $request): JsonResponse
    {
        $logs = LogIA::where('exitoso', false)
            ->with(['user', 'consulta'])
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 50));

        return response()->json($logs);
    }
}
