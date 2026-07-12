<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\ResultadoConsulta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConsultaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $consultas = Consulta::where('user_id', $request->user()->id)
            ->with([
                'resultado',
                'user.perfilPaciente',
                'user.perfilMedico',
                'user.perfilEnfermera',
            ])
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        $consultas->getCollection()->transform(fn($consulta) => $this->formatConsulta($consulta));

        return response()->json($consultas);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $consulta = Consulta::where('user_id', $request->user()->id)
            ->with([
                'resultado',
                'user.perfilPaciente',
                'user.perfilMedico',
                'user.perfilEnfermera',
            ])
            ->find($id);

        if (!$consulta) {
            return response()->json(['message' => 'Consulta no encontrada'], 404);
        }

        return response()->json($this->formatConsulta($consulta));
    }

    private function formatConsulta(Consulta $consulta): array
    {
        $user = $consulta->user;
        $rol = $user->role ?? 'user';

        $paciente = null;
        $profesional = null;

        if (in_array($rol, ['paciente', 'user'])) {
            $paciente = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'perfil' => $user->perfilPaciente,
            ];
        } else {
            $profesional = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $rol,
                'perfil_medico' => $user->perfilMedico,
                'perfil_enfermera' => $user->perfilEnfermera,
            ];
        }

        return [
            'id' => $consulta->id,
            'user_id' => $consulta->user_id,
            'sintomas' => $consulta->sintomas,
            'idioma' => $consulta->idioma,
            'modo_aprendizaje' => $consulta->modo_aprendizaje,
            'estado' => $consulta->estado,
            'created_at' => $consulta->created_at,
            'updated_at' => $consulta->updated_at,
            'resultado' => $consulta->resultado,
            'paciente' => $paciente,
            'profesional' => $profesional,
        ];
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sintomas' => 'required|string|min:3',
            'idioma' => 'sometimes|string|max:5',
            'modo_aprendizaje' => 'sometimes|string|in:estudiante,profesional',
        ]);

        $user = $request->user();

        $consulta = Consulta::create([
            'user_id' => $user->id,
            'sintomas' => $validated['sintomas'],
            'idioma' => $validated['idioma'] ?? 'es',
            'modo_aprendizaje' => $validated['modo_aprendizaje'] ?? $user->learning_mode ?? 'estudiante',
            'estado' => 'procesando',
        ]);

        try {
            $resultado = $this->procesarConsultaAI($consulta, $user);

            $consulta->update(['estado' => 'completada']);

            ResultadoConsulta::create([
                'consulta_id' => $consulta->id,
                'respuesta_json' => $resultado['respuesta'],
                'proveedor_usado' => $resultado['proveedor'] ?? 'openrouter',
                'tokens_usados' => $resultado['tokens'] ?? null,
                'latencia_ms' => $resultado['latencia'] ?? null,
            ]);

            $consulta->load('resultado');

            return response()->json([
                'message' => 'Consulta procesada exitosamente',
                'consulta' => $consulta,
            ], 201);

        } catch (\Exception $e) {
            $consulta->update(['estado' => 'error']);

            return response()->json([
                'message' => 'Error al procesar la consulta',
                'error' => $e->getMessage(),
                'consulta' => $consulta,
            ], 500);
        }
    }

    private function procesarConsultaAI(Consulta $consulta, $user): array
    {
        $startTime = microtime(true);

        $systemPrompt = $this->buildSystemPrompt($user);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
        ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => config('services.openrouter.model', 'openai/gpt-4o-mini'),
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => 'Síntomas del paciente: ' . $consulta->sintomas . '. Modo: ' . $consulta->modo_aprendizaje],
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        $latencia = (int) ((microtime(true) - $startTime) * 1000);

        if ($response->failed()) {
            throw new \Exception('Error del proveedor de IA: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '{}';

        $respuesta = json_decode($content, true) ?? ['respuesta_texto' => $content];

        return [
            'respuesta' => $respuesta,
            'proveedor' => 'openrouter',
            'tokens' => $data['usage']['total_tokens'] ?? null,
            'latencia' => $latencia,
        ];
    }

    private function buildSystemPrompt($user): string
    {
        $modo = $user->learning_mode ?? 'estudiante';
        $nivel = $modo === 'estudiante' ? 'sencillo y fácil de entender' : 'técnico y profesional con terminología médica';

        return "Eres un asistente médico experto de MedExpert AR. Responde en español.
Nivel de explicación: {$nivel}.
Responde SIEMPRE con un JSON válido con esta estructura:
{
  \"enfermedades\": [
    {
      \"nombre\": \"Nombre de la enfermedad\",
      \"probabilidad\": 85,
      \"descripcion\": \"Descripción breve\",
      \"organo_id\": \"ID del órgano afectado (ej: digestive_stomach_001)\",
      \"tratamientos\": [\"Tratamiento 1\", \"Tratamiento 2\"],
      \"estudios\": [\"Estudio diagnóstico 1\"],
      \"urgencia\": \"baja|media|alta|critica\",
      \"especialidad\": \"Especialidad médica\"
    }
  ],
  \"mensaje_orientativo\": \"Mensaje orientativo general\",
  \"disclaimer\": \"Este es un análisis orientativo. Consulta a un médico profesional.\"
}
Órganos válidos: nervous_system_brain_001, respiratory_lung_001, respiratory_lung_002, digestive_stomach_001, digestive_liver_001, digestive_pancreas_001, digestive_intestine_001, circulatory_heart_001, urinary_kidney_001, urinary_kidney_002, urinary_bladder_001";
    }
}
