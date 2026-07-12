<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPaciente extends Model
{
    protected $table = 'perfiles_pacientes';

    protected $fillable = [
        'user_id', 'fecha_nacimiento', 'sexo', 'telefono',
        'direccion', 'alergias', 'enfermedades_cronicas', 'grupo_sanguineo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
