<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudioDiagnostico extends Model
{
    protected $table = 'estudios_diagnosticos';

    protected $fillable = ['nombre', 'descripcion', 'preparacion'];

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_estudio', 'estudio_id', 'enfermedad_id');
    }
}
