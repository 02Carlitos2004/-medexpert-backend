<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enfermedad extends Model
{
    use SoftDeletes;

    protected $table = 'enfermedades';

    protected $fillable = ['nombre', 'descripcion', 'nivel_urgencia', 'especialidad', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sintomas()
    {
        return $this->belongsToMany(Sintoma::class, 'enfermedad_sintoma', 'enfermedad_id', 'sintoma_id')->withPivot('peso');
    }

    public function organos()
    {
        return $this->belongsToMany(Organo::class, 'enfermedad_organo', 'enfermedad_id', 'organo_id');
    }

    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class, 'enfermedad_tratamiento', 'enfermedad_id', 'tratamiento_id');
    }

    public function estudios()
    {
        return $this->belongsToMany(EstudioDiagnostico::class, 'enfermedad_estudio', 'enfermedad_id', 'estudio_id');
    }

    public function referencias()
    {
        return $this->belongsToMany(ReferenciaBibliografica::class, 'enfermedad_referencia', 'enfermedad_id', 'referencia_id')->withPivot('peso_relevancia');
    }
}
