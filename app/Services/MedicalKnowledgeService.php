<?php

namespace App\Services;

use App\Models\Enfermedad;
use App\Models\Sintoma;
use App\Models\Organo;
use App\Models\SinonimoSintoma;

class MedicalKnowledgeService
{
    public function buscarEnfermedadesPorSintomas(array $sintomasTexto): array
    {
        $sintomasIds = [];

        foreach ($sintomasTexto as $texto) {
            $sintoma = Sintoma::where('nombre', 'like', "%{$texto}%")->first();

            if (!$sintoma) {
                $sinonimo = SinonimoSintoma::where('termino_coloquial', 'like', "%{$texto}%")->first();
                if ($sinonimo) {
                    $sintoma = $sinonimo->sintoma;
                }
            }

            if ($sintoma) {
                $sintomasIds[] = $sintoma->id;
            }
        }

        if (empty($sintomasIds)) {
            return [];
        }

        $enfermedades = Enfermedad::whereHas('sintomas', function ($q) use ($sintomasIds) {
            $q->whereIn('sintomas.id', $sintomasIds);
        })->where('activo', true)
          ->with(['sintomas', 'organos', 'tratamientos', 'estudios', 'referencias'])
          ->get();

        return $enfermedades->toArray();
    }

    public function obtenerOrgano(string $organoId): ?array
    {
        $organo = Organo::with(['sistema', 'regionAnatomica'])
            ->where('id_unico', $organoId)
            ->orWhere('id', $organoId)
            ->first();

        return $organo?->toArray();
    }

    public function obtenerTratamientos(int $enfermedadId): array
    {
        $enfermedad = Enfermedad::with('tratamientos')->find($enfermedadId);
        return $enfermedad?->tratamientos->toArray() ?? [];
    }

    public function obtenerEstudios(int $enfermedadId): array
    {
        $enfermedad = Enfermedad::with('estudios')->find($enfermedadId);
        return $enfermedad?->estudios->toArray() ?? [];
    }

    public function obtenerReferencias(int $enfermedadId): array
    {
        $enfermedad = Enfermedad::with('referencias')->find($enfermedadId);
        return $enfermedad?->referencias->toArray() ?? [];
    }

    public function buscarPorTexto(string $texto): array
    {
        $enfermedades = Enfermedad::where('nombre', 'like', "%{$texto}%")
            ->orWhere('descripcion', 'like', "%{$texto}%")
            ->where('activo', true)
            ->with(['sintomas', 'organos'])
            ->get();

        return $enfermedades->toArray();
    }
}
