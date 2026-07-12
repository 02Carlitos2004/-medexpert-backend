<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerfilPaciente;
use App\Models\PerfilMedico;
use App\Models\PerfilEnfermera;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['perfilPaciente', 'perfilMedico', 'perfilEnfermera']);
        return response()->json(['user' => $user]);
    }

    public function updatePaciente(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fecha_nacimiento' => 'sometimes|date',
            'sexo' => 'sometimes|in:masculino,femenino,otro',
            'telefono' => 'sometimes|string|max:20',
            'direccion' => 'sometimes|string',
            'alergias' => 'sometimes|string',
            'enfermedades_cronicas' => 'sometimes|string',
            'grupo_sanguineo' => 'sometimes|string|max:5',
        ]);

        $perfil = PerfilPaciente::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json(['message' => 'Perfil de paciente actualizado', 'perfil' => $perfil]);
    }

    public function updateMedico(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'especialidad' => 'sometimes|string',
            'cedula_profesional' => 'sometimes|string|max:50',
            'hospital' => 'sometimes|string',
            'telefono_consultorio' => 'sometimes|string|max:20',
            'horario_atencion' => 'sometimes|array',
        ]);

        $perfil = PerfilMedico::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json(['message' => 'Perfil médico actualizado', 'perfil' => $perfil]);
    }

    public function updateEnfermera(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'area_trabajo' => 'sometimes|string',
            'cedula_profesional' => 'sometimes|string|max:50',
            'hospital' => 'sometimes|string',
            'turno' => 'sometimes|in:matutino,vespertino,nocturno',
        ]);

        $perfil = PerfilEnfermera::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json(['message' => 'Perfil de enfermera actualizado', 'perfil' => $perfil]);
    }
}
