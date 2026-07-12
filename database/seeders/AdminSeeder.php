<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\ConfigAIEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@medexpert.ar'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'role_id' => $adminRole?->id,
                'activo' => true,
            ]
        );

        ConfigAIEngine::updateOrCreate(
            ['id' => 1],
            [
                'proveedor_activo' => 'openrouter',
                'orden_fallback' => ['openrouter', 'openai', 'gemini', 'deepseek'],
                'modelo' => 'gpt-4o-mini',
                'temperatura' => 0.7,
                'max_tokens' => 2000,
                'timeout' => 30,
                'cache_ttl' => 3600,
                'limite_diario_por_usuario' => 50,
            ]
        );
    }
}
