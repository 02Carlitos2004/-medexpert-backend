<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'tipo'];

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_tratamiento', 'tratamiento_id', 'enfermedad_id');
    }
}
