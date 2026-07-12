<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo3D extends Model
{
    protected $table = 'modelos_3d';

    protected $fillable = ['organo_id', 'tipo', 'url', 'version', 'tamano_bytes', 'poligonos'];

    public function organo()
    {
        return $this->belongsTo(Organo::class);
    }
}
