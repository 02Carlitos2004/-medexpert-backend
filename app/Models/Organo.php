<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organo extends Model
{
    protected $fillable = [
        'id_unico', 'nombre_es', 'nombre_en', 'nombre_tecnico',
        'sistema_id', 'region_anatomica_id',
        'regiones_internas', 'subestructuras', 'organos_vecinos',
        'orden_capa', 'modelo_3d_url', 'modelo_lod_url',
        'tiene_modelo_individual', 'soporta_ar',
        'posicion_camara', 'rotacion_camara', 'zoom_recomendado',
        'color_base', 'color_resalte', 'color_estudiante', 'color_profesional',
        'transparencia_default', 'visible_inicialmente',
        'animaciones_disponibles', 'descripcion_estudiante', 'descripcion_profesional',
        'icono_referencia',
    ];

    protected $casts = [
        'regiones_internas' => 'array',
        'subestructuras' => 'array',
        'organos_vecinos' => 'array',
        'posicion_camara' => 'array',
        'rotacion_camara' => 'array',
        'animaciones_disponibles' => 'array',
        'tiene_modelo_individual' => 'boolean',
        'soporta_ar' => 'boolean',
        'visible_inicialmente' => 'boolean',
    ];

    public function sistema()
    {
        return $this->belongsTo(SistemaCuerpo::class, 'sistema_id');
    }

    public function regionAnatomica()
    {
        return $this->belongsTo(RegionAnatomica::class, 'region_anatomica_id');
    }

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_organo', 'organo_id', 'enfermedad_id');
    }

    public function modelos3d()
    {
        return $this->hasMany(Modelo3D::class, 'organo_id');
    }
}
