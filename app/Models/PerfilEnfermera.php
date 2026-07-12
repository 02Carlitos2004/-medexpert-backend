<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilEnfermera extends Model
{
    protected $table = 'perfiles_enfermeras';

    protected $fillable = [
        'user_id', 'area_trabajo', 'cedula_profesional',
        'hospital', 'turno',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
