<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sintoma extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_sintoma', 'sintoma_id', 'enfermedad_id')->withPivot('peso');
    }

    public function sinonimos()
    {
        return $this->hasMany(SinonimoSintoma::class, 'sintoma_id');
    }
}
