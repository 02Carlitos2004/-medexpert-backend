<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionAnatomica extends Model
{
    protected $table = 'regiones_anatomicas';

    protected $fillable = ['nombre', 'descripcion', 'posicion_camara', 'zoom_recomendado'];

    protected $casts = [
        'posicion_camara' => 'array',
    ];

    public function organos()
    {
        return $this->hasMany(Organo::class, 'region_anatomica_id');
    }
}
