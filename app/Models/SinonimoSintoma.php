<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinonimoSintoma extends Model
{
    protected $table = 'sinonimos_sintomas';

    protected $fillable = ['sintoma_id', 'termino_coloquial', 'idioma'];

    public function sintoma()
    {
        return $this->belongsTo(Sintoma::class);
    }
}
