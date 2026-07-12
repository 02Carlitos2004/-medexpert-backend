<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enfermedad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnfermedadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Enfermedad::where('activo', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->has('urgencia')) {
            $query->where('nivel_urgencia', $request->urgencia);
        }

        if ($request->has('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        $enfermedades = $query->with(['sintomas', 'organos', 'tratamientos', 'estudios', 'referencias'])
            ->paginate($request->get('per_page', 20));

        return response()->json($enfermedades);
    }

    public function show($id): JsonResponse
    {
        $enfermedad = Enfermedad::with(['sintomas', 'organos', 'tratamientos', 'estudios', 'referencias'])
            ->find($id);

        if (!$enfermedad) {
            return response()->json(['message' => 'Enfermedad no encontrada'], 404);
        }

        return response()->json($enfermedad);
    }

    public function porOrgano($organoId): JsonResponse
    {
        $enfermedades = Enfermedad::whereHas('organos', function ($q) use ($organoId) {
            $q->where('organos.id', $organoId);
        })->where('activo', true)->get();

        return response()->json($enfermedades);
    }

    public function porSintomas(Request $request): JsonResponse
    {
        $request->validate(['sintomas' => 'required|array']);

        $enfermedades = Enfermedad::whereHas('sintomas', function ($q) use ($request) {
            $q->whereIn('sintomas.id', $request->sintomas);
        })->where('activo', true)
          ->with(['sintomas', 'organos'])
          ->get();

        return response()->json($enfermedades);
    }
}
