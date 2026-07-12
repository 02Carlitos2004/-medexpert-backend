<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilMedico extends Model
{
    protected $table = 'perfiles_medicos';

    protected $fillable = [
        'user_id', 'especialidad', 'cedula_profesional',
        'hospital', 'telefono_consultorio', 'horario_atencion',
    ];

    protected $casts = [
        'horario_atencion' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
