<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['user_id', 'sintomas', 'idioma', 'modo_aprendizaje', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resultado()
    {
        return $this->hasOne(ResultadoConsulta::class);
    }

    public function logs()
    {
        return $this->hasMany(LogIA::class, 'consulta_id');
    }
}
