<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nombre', 'slug', 'descripcion', 'permisos'];

    protected $casts = [
        'permisos' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
