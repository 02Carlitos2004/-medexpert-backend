<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheConsulta extends Model
{
    protected $table = 'cache_consultas';

    protected $fillable = ['hash_consulta', 'sintomas', 'respuesta_json', 'expires_at'];

    protected $casts = [
        'respuesta_json' => 'array',
        'expires_at' => 'datetime',
    ];
}
