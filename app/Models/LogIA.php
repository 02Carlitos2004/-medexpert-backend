<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogIA extends Model
{
    protected $table = 'log_ia';

    protected $fillable = [
        'consulta_id', 'user_id', 'proveedor', 'modelo',
        'tokens_entrada', 'tokens_salida', 'costo', 'latencia_ms',
        'exitoso', 'error_message',
    ];

    protected $casts = [
        'exitoso' => 'boolean',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
