<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organo;
use App\Models\SistemaCuerpo;
use App\Models\RegionAnatomica;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganoAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Organo::with(['sistema', 'regionAnatomica', 'modelos3d']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre_es', 'like', "%{$search}%")
                  ->orWhere('nombre_tecnico', 'like', "%{$search}%");
            });
        }

        if ($request->has('sistema')) {
            $query->whereHas('sistema', fn($q) => $q->where('nombre', $request->sistema));
        }

        $organos = $query->orderBy('orden_capa')
            ->paginate($request->get('per_page', 20));

        return response()->json($organos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_unico' => 'required|string|unique:organos,id_unico',
            'nombre_es' => 'required|string|max:255',
            'nombre_en' => 'sometimes|string|max:255',
            'nombre_tecnico' => 'sometimes|string|max:255',
            'sistema_id' => 'required|exists:sistemas_cuerpo,id',
            'region_anatomica_id' => 'sometimes|nullable|exists:regiones_anatomicas,id',
            'color_resalte' => 'sometimes|string|max:7',
            'zoom_recomendado' => 'sometimes|numeric',
            'soporta_ar' => 'sometimes|boolean',
            'tiene_modelo_individual' => 'sometimes|boolean',
            'descripcion_estudiante' => 'sometimes|string',
            'descripcion_profesional' => 'sometimes|string',
        ]);

        $organo = Organo::create($validated);

        return response()->json([
            'message' => 'Órgano creado',
            'organo' => $organo->load(['sistema', 'regionAnatomica']),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $organo = Organo::find($id);

        if (!$organo) {
            return response()->json(['message' => 'Órgano no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre_es' => 'sometimes|string|max:255',
            'nombre_en' => 'sometimes|string|max:255',
            'nombre_tecnico' => 'sometimes|string|max:255',
            'sistema_id' => 'sometimes|exists:sistemas_cuerpo,id',
            'region_anatomica_id' => 'sometimes|nullable|exists:regiones_anatomicas,id',
            'color_resalte' => 'sometimes|string|max:7',
            'zoom_recomendado' => 'sometimes|numeric',
            'soporta_ar' => 'sometimes|boolean',
            'tiene_modelo_individual' => 'sometimes|boolean',
            'descripcion_estudiante' => 'sometimes|string',
            'descripcion_profesional' => 'sometimes|string',
        ]);

        $organo->update($validated);

        return response()->json(['message' => 'Órgano actualizado', 'organo' => $organo]);
    }

    public function destroy($id): JsonResponse
    {
        $organo = Organo::find($id);

        if (!$organo) {
            return response()->json(['message' => 'Órgano no encontrado'], 404);
        }

        $organo->delete();

        return response()->json(['message' => 'Órgano eliminado']);
    }

    public function sistemas(): JsonResponse
    {
        return response()->json(SistemaCuerpo::withCount('organos')->get());
    }

    public function regiones(): JsonResponse
    {
        return response()->json(RegionAnatomica::withCount('organos')->get());
    }
}
