<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferenciaBibliografica;
use App\Models\Enfermedad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferenciaAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ReferenciaBibliografica::with('enfermedades');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%");
            });
        }

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $referencias = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json($referencias);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|in:libro,guia_clinica,protocolo,articulo,otro',
            'autor' => 'sometimes|string',
            'titulo' => 'required|string',
            'edicion' => 'sometimes|string',
            'anio' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            'editorial' => 'sometimes|string',
            'isbn' => 'sometimes|string',
            'doi' => 'sometimes|string',
            'pmid' => 'sometimes|string',
            'url' => 'sometimes|url',
            'nivel_evidencia' => 'sometimes|in:A,B,C,D',
            'enfermedades' => 'sometimes|array',
            'enfermedades.*' => 'integer|exists:enfermedades,id',
        ]);

        $enfermedades = $validated['enfermedades'] ?? [];
        unset($validated['enfermedades']);

        $referencia = ReferenciaBibliografica::create($validated);
        $referencia->enfermedades()->sync($enfermedades);

        return response()->json([
            'message' => 'Referencia creada',
            'referencia' => $referencia->load('enfermedades'),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $referencia = ReferenciaBibliografica::find($id);

        if (!$referencia) {
            return response()->json(['message' => 'Referencia no encontrada'], 404);
        }

        $validated = $request->validate([
            'tipo' => 'sometimes|in:libro,guia_clinica,protocolo,articulo,otro',
            'autor' => 'sometimes|string',
            'titulo' => 'sometimes|string',
            'nivel_evidencia' => 'sometimes|in:A,B,C,D',
            'enfermedades' => 'sometimes|array',
        ]);

        if (isset($validated['enfermedades'])) {
            $referencia->enfermedades()->sync($validated['enfermedades']);
            unset($validated['enfermedades']);
        }

        $referencia->update($validated);

        return response()->json(['message' => 'Referencia actualizada', 'referencia' => $referencia]);
    }

    public function destroy($id): JsonResponse
    {
        $referencia = ReferenciaBibliografica::find($id);

        if (!$referencia) {
            return response()->json(['message' => 'Referencia no encontrada'], 404);
        }

        $referencia->delete();

        return response()->json(['message' => 'Referencia eliminada']);
    }
}
