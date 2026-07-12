<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organo;
use App\Models\SistemaCuerpo;
use App\Models\RegionAnatomica;
use Illuminate\Http\JsonResponse;

class OrganoController extends Controller
{
    public function index()
    {
        $organos = Organo::with(['sistema', 'regionAnatomica'])
            ->orderBy('orden_capa')
            ->get();

        return response()->json($organos);
    }

    public function show($id): JsonResponse
    {
        $organo = Organo::with(['sistema', 'regionAnatomica', 'enfermedades', 'modelos3d'])
            ->find($id);

        if (!$organo) {
            return response()->json(['message' => 'Órgano no encontrado'], 404);
        }

        return response()->json($organo);
    }

    public function porIdUnico($idUnico): JsonResponse
    {
        $organo = Organo::with(['sistema', 'regionAnatomica', 'enfermedades'])
            ->where('id_unico', $idUnico)
            ->first();

        if (!$organo) {
            return response()->json(['message' => 'Órgano no encontrado'], 404);
        }

        return response()->json($organo);
    }

    public function sistemas(): JsonResponse
    {
        $sistemas = SistemaCuerpo::with(['organos'])->get();
        return response()->json($sistemas);
    }

    public function regiones(): JsonResponse
    {
        $regiones = RegionAnatomica::with(['organos'])->get();
        return response()->json($regiones);
    }
}
