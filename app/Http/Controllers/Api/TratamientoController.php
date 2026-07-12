<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tratamiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Tratamiento::query();

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $tratamientos = $query->withCount('enfermedades')
            ->paginate($request->get('per_page', 20));

        return response()->json($tratamientos);
    }

    public function show($id): JsonResponse
    {
        $tratamiento = Tratamiento::with(['enfermedades'])->find($id);

        if (!$tratamiento) {
            return response()->json(['message' => 'Tratamiento no encontrado'], 404);
        }

        return response()->json($tratamiento);
    }

    public function porEnfermedad($enfermedadId): JsonResponse
    {
        $tratamientos = Tratamiento::whereHas('enfermedades', function ($q) use ($enfermedadId) {
            $q->where('enfermedades.id', $enfermedadId);
        })->get();

        return response()->json($tratamientos);
    }
}
