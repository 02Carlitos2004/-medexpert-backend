<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenciaBibliografica;
use Illuminate\Http\JsonResponse;

class ReferenciaController extends Controller
{
    public function porEnfermedad($enfermedadId): JsonResponse
    {
        $referencias = ReferenciaBibliografica::whereHas('enfermedades', function ($q) use ($enfermedadId) {
            $q->where('enfermedades.id', $enfermedadId);
        })->get();

        return response()->json($referencias);
    }

    public function index()
    {
        return response()->json(ReferenciaBibliografica::paginate(20));
    }

    public function show($id): JsonResponse
    {
        $referencia = ReferenciaBibliografica::find($id);

        if (!$referencia) {
            return response()->json(['message' => 'Referencia no encontrada'], 404);
        }

        return response()->json($referencia);
    }
}
