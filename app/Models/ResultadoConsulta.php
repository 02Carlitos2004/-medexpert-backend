<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoConsulta extends Model
{
    protected $table = 'resultados_consulta';

    protected $fillable = ['consulta_id', 'respuesta_json', 'proveedor_usado', 'tokens_usados', 'latencia_ms'];

    protected $casts = [
        'respuesta_json' => 'array',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
