<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sintoma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SintomaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sintoma::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $sintomas = $query->withCount('enfermedades')
            ->orderBy('nombre')
            ->paginate($request->get('per_page', 50));

        return response()->json($sintomas);
    }

    public function show($id): JsonResponse
    {
        $sintoma = Sintoma::with(['enfermedades', 'sinonimos'])->find($id);

        if (!$sintoma) {
            return response()->json(['message' => 'Síntoma no encontrado'], 404);
        }

        return response()->json($sintoma);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2']);

        $sintomas = Sintoma::where('nombre', 'like', "%{$request->q}%")
            ->withCount('enfermedades')
            ->limit(10)
            ->get();

        return response()->json($sintomas);
    }
}
