<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enfermedad;
use App\Models\Sintoma;
use App\Models\Organo;
use App\Models\Tratamiento;
use App\Models\EstudioDiagnostico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnfermedadAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Enfermedad::with(['sintomas', 'organos', 'tratamientos', 'estudios']);

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

        $enfermedades = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json($enfermedades);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'sometimes|string',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'especialidad' => 'sometimes|string|max:255',
            'sintomas' => 'sometimes|array',
            'sintomas.*' => 'integer|exists:sintomas,id',
            'organos' => 'sometimes|array',
            'organos.*' => 'integer|exists:organos,id',
            'tratamientos' => 'sometimes|array',
            'tratamientos.*' => 'integer|exists:tratamientos,id',
            'estudios' => 'sometimes|array',
            'estudios.*' => 'integer|exists:estudios_diagnosticos,id',
        ]);

        $sintomas = $validated['sintomas'] ?? [];
        $organos = $validated['organos'] ?? [];
        $tratamientos = $validated['tratamientos'] ?? [];
        $estudios = $validated['estudios'] ?? [];

        unset($validated['sintomas'], $validated['organos'], $validated['tratamientos'], $validated['estudios']);

        $enfermedad = Enfermedad::create($validated);

        $enfermedad->sintomas()->sync($sintomas);
        $enfermedad->organos()->sync($organos);
        $enfermedad->tratamientos()->sync($tratamientos);
        $enfermedad->estudios()->sync($estudios);

        return response()->json([
            'message' => 'Enfermedad creada exitosamente',
            'enfermedad' => $enfermedad->load(['sintomas', 'organos', 'tratamientos', 'estudios']),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $enfermedad = Enfermedad::find($id);

        if (!$enfermedad) {
            return response()->json(['message' => 'Enfermedad no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'nivel_urgencia' => 'sometimes|in:baja,media,alta,critica',
            'especialidad' => 'sometimes|string|max:255',
            'activo' => 'sometimes|boolean',
            'sintomas' => 'sometimes|array',
            'organos' => 'sometimes|array',
            'tratamientos' => 'sometimes|array',
            'estudios' => 'sometimes|array',
        ]);

        if (isset($validated['sintomas'])) {
            $enfermedad->sintomas()->sync($validated['sintomas']);
            unset($validated['sintomas']);
        }

        if (isset($validated['organos'])) {
            $enfermedad->organos()->sync($validated['organos']);
            unset($validated['organos']);
        }

        if (isset($validated['tratamientos'])) {
            $enfermedad->tratamientos()->sync($validated['tratamientos']);
            unset($validated['tratamientos']);
        }

        if (isset($validated['estudios'])) {
            $enfermedad->estudios()->sync($validated['estudios']);
            unset($validated['estudios']);
        }

        $enfermedad->update($validated);

        return response()->json([
            'message' => 'Enfermedad actualizada',
            'enfermedad' => $enfermedad->load(['sintomas', 'organos', 'tratamientos', 'estudios']),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $enfermedad = Enfermedad::find($id);

        if (!$enfermedad) {
            return response()->json(['message' => 'Enfermedad no encontrada'], 404);
        }

        $enfermedad->delete();

        return response()->json(['message' => 'Enfermedad eliminada']);
    }
}
