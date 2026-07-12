<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sintoma;
use App\Models\SinonimoSintoma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SintomaAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sintoma::with(['sinonimos', 'enfermedades']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $sintomas = $query->orderBy('nombre')
            ->paginate($request->get('per_page', 20));

        return response()->json($sintomas);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:sintomas,nombre',
            'descripcion' => 'sometimes|string',
            'sinonimos' => 'sometimes|array',
            'sinonimos.*' => 'string',
        ]);

        $sinonimos = $validated['sinonimos'] ?? [];
        unset($validated['sinonimos']);

        $sintoma = Sintoma::create($validated);

        foreach ($sinonimos as $termino) {
            SinonimoSintoma::create([
                'sintoma_id' => $sintoma->id,
                'termino_coloquial' => $termino,
            ]);
        }

        return response()->json([
            'message' => 'Síntoma creado',
            'sintoma' => $sintoma->load('sinonimos'),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $sintoma = Sintoma::find($id);

        if (!$sintoma) {
            return response()->json(['message' => 'Síntoma no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'sinonimos' => 'sometimes|array',
        ]);

        if (isset($validated['sinonimos'])) {
            SinonimoSintoma::where('sintoma_id', $sintoma->id)->delete();
            foreach ($validated['sinonimos'] as $termino) {
                SinonimoSintoma::create([
                    'sintoma_id' => $sintoma->id,
                    'termino_coloquial' => $termino,
                ]);
            }
            unset($validated['sinonimos']);
        }

        $sintoma->update($validated);

        return response()->json(['message' => 'Síntoma actualizado', 'sintoma' => $sintoma->load('sinonimos')]);
    }

    public function destroy($id): JsonResponse
    {
        $sintoma = Sintoma::find($id);

        if (!$sintoma) {
            return response()->json(['message' => 'Síntoma no encontrado'], 404);
        }

        $sintoma->delete();

        return response()->json(['message' => 'Síntoma eliminado']);
    }
}
