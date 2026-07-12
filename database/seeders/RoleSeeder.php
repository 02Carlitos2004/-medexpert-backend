<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Paciente', 'slug' => 'paciente', 'descripcion' => 'Usuario general que consulta síntomas y explora anatomía'],
            ['nombre' => 'Médico', 'slug' => 'medico', 'descripcion' => 'Profesional de la salud con acceso a funciones avanzadas'],
            ['nombre' => 'Enfermera', 'slug' => 'enfermera', 'descripcion' => 'Profesional de enfermería con acceso a funciones clínicas'],
            ['nombre' => 'Administrador', 'slug' => 'admin', 'descripcion' => 'Gestión completa del sistema, usuarios y configuración'],
            ['nombre' => 'Super Admin', 'slug' => 'super_admin', 'descripcion' => 'Control total incluyendo API keys y configuración global'],
        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate(['slug' => $rol['slug']], $rol);
        }
    }
}
