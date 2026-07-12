<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaCuerpo extends Model
{
    protected $table = 'sistemas_cuerpo';

    protected $fillable = ['nombre', 'descripcion', 'color_hex', 'visible_por_defecto', 'orden_jerarquico'];

    protected $casts = [
        'visible_por_defecto' => 'boolean',
    ];

    public function organos()
    {
        return $this->hasMany(Organo::class, 'sistema_id');
    }
}
