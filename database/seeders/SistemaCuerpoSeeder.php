<?php

namespace Database\Seeders;

use App\Models\SistemaCuerpo;
use Illuminate\Database\Seeder;

class SistemaCuerpoSeeder extends Seeder
{
    public function run(): void
    {
        $sistemas = [
            ['nombre' => 'Sistema Nervioso', 'descripcion' => 'Cerebro, médula espinal y nervios', 'color_hex' => '#9C27B0', 'orden_jerarquico' => 1],
            ['nombre' => 'Sistema Respiratorio', 'descripcion' => 'Pulmones, tráquea, bronquios', 'color_hex' => '#2196F3', 'orden_jerarquico' => 2],
            ['nombre' => 'Sistema Digestivo', 'descripcion' => 'Estómago, intestinos, hígado, páncreas', 'color_hex' => '#4CAF50', 'orden_jerarquico' => 3],
            ['nombre' => 'Sistema Circulatorio', 'descripcion' => 'Corazón, arterias, venas', 'color_hex' => '#F44336', 'orden_jerarquico' => 4],
            ['nombre' => 'Sistema Muscular', 'descripcion' => 'Músculos esqueléticos principales', 'color_hex' => '#FF9800', 'orden_jerarquico' => 5],
            ['nombre' => 'Sistema Esquelético', 'descripcion' => 'Huesos, articulaciones, cartílagos', 'color_hex' => '#607D8B', 'orden_jerarquico' => 6],
            ['nombre' => 'Sistema Endocrino', 'descripcion' => 'Tiroides, páncreas, hipófisis, suprarrenales', 'color_hex' => '#E91E63', 'orden_jerarquico' => 7],
            ['nombre' => 'Sistema Urinario', 'descripcion' => 'Riñones, uréteres, vejiga', 'color_hex' => '#00BCD4', 'orden_jerarquico' => 8],
            ['nombre' => 'Sistema Reproductor', 'descripcion' => 'Órganos reproductivos masculinos y femeninos', 'color_hex' => '#FF5722', 'orden_jerarquico' => 9],
            ['nombre' => 'Sistema Linfático', 'descripcion' => 'Ganglios linfáticos, bazo, timo', 'color_hex' => '#8BC34A', 'orden_jerarquico' => 10],
            ['nombre' => 'Sistema Tegumentario', 'descripcion' => 'Piel, uñas, cabello', 'color_hex' => '#795548', 'orden_jerarquico' => 11],
            ['nombre' => 'Sistema Sensorial', 'descripcion' => 'Ojos, oídos, nariz, lengua', 'color_hex' => '#FFEB3B', 'orden_jerarquico' => 12],
        ];

        foreach ($sistemas as $sistema) {
            SistemaCuerpo::updateOrCreate(
                ['nombre' => $sistema['nombre']],
                $sistema
            );
        }
    }
}
