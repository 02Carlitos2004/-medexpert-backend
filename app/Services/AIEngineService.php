<?php

namespace App\Services;

use App\Models\Consulta;
use App\Models\ResultadoConsulta;
use App\Models\ConfigAIEngine;
use App\Models\LogIA;
use App\Models\CacheConsulta;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class AIEngineService
{
    private MedicalKnowledgeService $knowledgeService;

    public function __construct(MedicalKnowledgeService $knowledgeService)
    {
        $this->knowledgeService = $knowledgeService;
    }

    public function procesarConsulta(Consulta $consulta, $user): array
    {
        $cacheHash = md5(strtolower(trim($consulta->sintomas)));

        $cached = CacheConsulta::where('hash_consulta', $cacheHash)
            ->where('expires_at', '>', now())
            ->first();

        if ($cached) {
            return $cached->respuesta_json;
        }

        $config = ConfigAIEngine::first();
        $proveedor = $config?->proveedor_activo ?? 'openrouter';

        $knowledgeContext = $this->knowledgeService->buscarEnfermedadesPorSintomas(
            explode(',', $consulta->sintomas)
        );

        $startTime = microtime(true);

        try {
            $resultado = $this->llamarProveedor($proveedor, $consulta, $user, $knowledgeContext, $config);
        } catch (\Exception $e) {
            $resultado = $this->llamarFallback($consulta, $user, $knowledgeContext, $config, $e);
        }

        $latencia = (int) ((microtime(true) - $startTime) * 1000);

        $tokens = $resultado['tokens'] ?? null;

        LogIA::create([
            'user_id' => $user->id,
            'consulta_id' => $consulta->id,
            'proveedor' => $proveedor,
            'modelo' => $config?->modelo ?? 'gpt-4o-mini',
            'tokens_entrada' => $tokens ? intval($tokens * 0.3) : null,
            'tokens_salida' => $tokens,
            'latencia_ms' => $latencia,
            'exitoso' => true,
        ]);

        CacheConsulta::create([
            'hash_consulta' => $cacheHash,
            'sintomas' => $consulta->sintomas,
            'respuesta_json' => $resultado['respuesta'],
            'expires_at' => now()->addSeconds($config?->cache_ttl ?? 3600),
        ]);

        return $resultado['respuesta'];
    }

    private function llamarProveedor(string $proveedor, Consulta $consulta, $user, array $knowledgeContext, ?ConfigAIEngine $config): array
    {
        $systemPrompt = $this->buildSystemPrompt($user, $knowledgeContext);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . Crypt::decryptString($config?->api_key_cifrada ?? ''),
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
        ])->timeout($config?->timeout ?? 30)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $config?->modelo ?? 'openai/gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => 'Síntomas: ' . $consulta->sintomas . '. Modo: ' . $consulta->modo_aprendizaje],
            ],
            'temperature' => $config?->temperatura ?? 0.7,
            'max_tokens' => $config?->max_tokens ?? 2000,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error del proveedor: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '{}';
        $respuesta = json_decode($content, true) ?? ['respuesta_texto' => $content];

        return [
            'respuesta' => $respuesta,
            'tokens' => $data['usage']['total_tokens'] ?? null,
        ];
    }

    private function llamarFallback(Consulta $consulta, $user, array $knowledgeContext, ?ConfigAIEngine $config, \Exception $originalError): array
    {
        $fallbackOrder = $config?->orden_fallback ?? ['openrouter'];

        foreach ($fallbackOrder as $proveedor) {
            try {
                return $this->llamarProveedor($proveedor, $consulta, $user, $knowledgeContext, $config);
            } catch (\Exception $e) {
                continue;
            }
        }

        throw $originalError;
    }

    private function buildSystemPrompt($user, array $knowledgeContext): string
    {
        $modo = $user->learning_mode ?? 'estudiante';
        $nivel = $modo === 'estudiante' ? 'sencillo y fácil de entender' : 'técnico y profesional con terminología médica';

        $contextoMedico = '';
        if (!empty($knowledgeContext)) {
            $contextoMedico = "\n\nCONOCIMIENTO MÉDICO DISPONIBLE:\n";
            foreach ($knowledgeContext as $enf) {
                $contextoMedico .= "- {$enf['nombre']} (urgencia: {$enf['nivel_urgencia']})\n";
            }
        }

        return "Eres un asistente médico experto de MedExpert AR. Responde en español.
Nivel de explicación: {$nivel}
{$contextoMedico}

Responde SIEMPRE con un JSON válido con esta estructura:
{
  \"enfermedades\": [{
    \"nombre\": \"Nombre\",
    \"probabilidad\": 85,
    \"descripcion\": \"Descripción\",
    \"organo_id\": \"ID_organico\",
    \"tratamientos\": [\"Tratamiento 1\"],
    \"estudios\": [\"Estudio 1\"],
    \"urgencia\": \"baja|media|alta|critica\",
    \"especialidad\": \"Especialidad\"
  }],
  \"mensaje_orientativo\": \"Mensaje\",
  \"disclaimer\": \"Consulta a un médico profesional.\"
}";
    }
}
