<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultaAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Consulta::with(['user', 'resultado']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $consultas = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json($consultas);
    }

    public function show($id): JsonResponse
    {
        $consulta = Consulta::with(['user', 'resultado', 'logs'])->find($id);

        if (!$consulta) {
            return response()->json(['message' => 'Consulta no encontrada'], 404);
        }

        return response()->json($consulta);
    }

    public function destroy($id): JsonResponse
    {
        $consulta = Consulta::find($id);

        if (!$consulta) {
            return response()->json(['message' => 'Consulta no encontrada'], 404);
        }

        $consulta->delete();

        return response()->json(['message' => 'Consulta eliminada']);
    }
}
