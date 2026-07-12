<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenciaBibliografica extends Model
{
    protected $table = 'referencias_bibliograficas';

    protected $fillable = [
        'tipo', 'autor', 'titulo', 'edicion', 'anio', 'editorial',
        'isbn', 'doi', 'pmid', 'url', 'nivel_evidencia',
    ];

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_referencia', 'referencia_id', 'enfermedad_id')->withPivot('peso_relevancia');
    }
}
