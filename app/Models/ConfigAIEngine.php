<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigAIEngine extends Model
{
    protected $table = 'config_ai_engine';

    protected $fillable = [
        'proveedor_activo', 'orden_fallback', 'api_key_cifrada',
        'modelo', 'temperatura', 'max_tokens', 'timeout',
        'cache_ttl', 'limite_diario_por_usuario',
    ];

    protected $casts = [
        'orden_fallback' => 'array',
    ];
}
