<?php

namespace Database\Seeders;

use App\Models\Organo;
use App\Models\SistemaCuerpo;
use App\Models\RegionAnatomica;
use Illuminate\Database\Seeder;

class OrganoSeeder extends Seeder
{
    public function run(): void
    {
        $regiones = [
            ['nombre' => 'Cráneo', 'descripcion' => 'Cabeza y encéfalo'],
            ['nombre' => 'Tórax', 'descripcion' => 'Pecho y cavidad torácica'],
            ['nombre' => 'Abdomen', 'descripcion' => 'Cavidad abdominal'],
            ['nombre' => 'Pelvis', 'descripcion' => 'Cavidad pélvica'],
            ['nombre' => 'Extremidades', 'descripcion' => 'Brazos y piernas'],
        ];

        foreach ($regiones as $region) {
            RegionAnatomica::updateOrCreate(['nombre' => $region['nombre']], $region);
        }

        $organos = [
            // Sistema Nervioso
            ['id_unico' => 'nervioso_brain_001', 'nombre_es' => 'Cerebro', 'nombre_en' => 'Brain', 'nombre_tecnico' => 'Cerebrum', 'sistema' => 'Sistema Nervioso', 'region' => 'Cráneo', 'color_resalte' => '#9C27B0', 'orden_capa' => 5],
            ['id_unico' => 'nervioso_spine_001', 'nombre_es' => 'Médula Espinal', 'nombre_en' => 'Spinal Cord', 'nombre_tecnico' => 'Medulla Spinalis', 'sistema' => 'Sistema Nervioso', 'region' => 'Cráneo', 'color_resalte' => '#9C27B0', 'orden_capa' => 5],

            // Sistema Respiratorio
            ['id_unico' => 'respiratory_lung_001', 'nombre_es' => 'Pulmón Derecho', 'nombre_en' => 'Right Lung', 'nombre_tecnico' => 'Pulmo Dexter', 'sistema' => 'Sistema Respiratorio', 'region' => 'Tórax', 'color_resalte' => '#2196F3', 'orden_capa' => 3],
            ['id_unico' => 'respiratory_lung_002', 'nombre_es' => 'Pulmón Izquierdo', 'nombre_en' => 'Left Lung', 'nombre_tecnico' => 'Pulmo Sinister', 'sistema' => 'Sistema Respiratorio', 'region' => 'Tórax', 'color_resalte' => '#2196F3', 'orden_capa' => 3],
            ['id_unico' => 'respiratory_trachea_001', 'nombre_es' => 'Tráquea', 'nombre_en' => 'Trachea', 'nombre_tecnico' => 'Trachea', 'sistema' => 'Sistema Respiratorio', 'region' => 'Tórax', 'color_resalte' => '#2196F3', 'orden_capa' => 3],

            // Sistema Digestivo
            ['id_unico' => 'digestive_stomach_001', 'nombre_es' => 'Estómago', 'nombre_en' => 'Stomach', 'nombre_tecnico' => 'Gaster', 'sistema' => 'Sistema Digestivo', 'region' => 'Abdomen', 'color_resalte' => '#4CAF50', 'orden_capa' => 3, 'tiene_modelo_individual' => true, 'soporta_ar' => true],
            ['id_unico' => 'digestive_liver_001', 'nombre_es' => 'Hígado', 'nombre_en' => 'Liver', 'nombre_tecnico' => 'Hepar', 'sistema' => 'Sistema Digestivo', 'region' => 'Abdomen', 'color_resalte' => '#4CAF50', 'orden_capa' => 3, 'tiene_modelo_individual' => true],
            ['id_unico' => 'digestive_pancreas_001', 'nombre_es' => 'Páncreas', 'nombre_en' => 'Pancreas', 'nombre_tecnico' => 'Pancreas', 'sistema' => 'Sistema Digestivo', 'region' => 'Abdomen', 'color_resalte' => '#4CAF50', 'orden_capa' => 3, 'tiene_modelo_individual' => true],
            ['id_unico' => 'digestive_intestine_001', 'nombre_es' => 'Intestino Delgado', 'nombre_en' => 'Small Intestine', 'nombre_tecnico' => 'Intestinum Tenue', 'sistema' => 'Sistema Digestivo', 'region' => 'Abdomen', 'color_resalte' => '#4CAF50', 'orden_capa' => 3, 'tiene_modelo_individual' => true],
            ['id_unico' => 'digestive_intestine_002', 'nombre_es' => 'Intestino Grueso', 'nombre_en' => 'Large Intestine', 'nombre_tecnico' => 'Intestinum Crassum', 'sistema' => 'Sistema Digestivo', 'region' => 'Abdomen', 'color_resalte' => '#4CAF50', 'orden_capa' => 3],

            // Sistema Circulatorio
            ['id_unico' => 'circulatory_heart_001', 'nombre_es' => 'Corazón', 'nombre_en' => 'Heart', 'nombre_tecnico' => 'Cor', 'sistema' => 'Sistema Circulatorio', 'region' => 'Tórax', 'color_resalte' => '#F44336', 'orden_capa' => 4, 'tiene_modelo_individual' => true, 'soporta_ar' => true],

            // Sistema Urinario
            ['id_unico' => 'urinary_kidney_001', 'nombre_es' => 'Riñón Derecho', 'nombre_en' => 'Right Kidney', 'nombre_tecnico' => 'Ren Dexter', 'sistema' => 'Sistema Urinario', 'region' => 'Abdomen', 'color_resalte' => '#00BCD4', 'orden_capa' => 3, 'tiene_modelo_individual' => true],
            ['id_unico' => 'urinary_kidney_002', 'nombre_es' => 'Riñón Izquierdo', 'nombre_en' => 'Left Kidney', 'nombre_tecnico' => 'Ren Sinister', 'sistema' => 'Sistema Urinario', 'region' => 'Abdomen', 'color_resalte' => '#00BCD4', 'orden_capa' => 3],
            ['id_unico' => 'urinary_bladder_001', 'nombre_es' => 'Vejiga', 'nombre_en' => 'Bladder', 'nombre_tecnico' => 'Vesica Urinaria', 'sistema' => 'Sistema Urinario', 'region' => 'Pelvis', 'color_resalte' => '#00BCD4', 'orden_capa' => 3],
        ];

        foreach ($organos as $data) {
            $sistema = SistemaCuerpo::where('nombre', $data['sistema'])->first();
            $region = RegionAnatomica::where('nombre', $data['region'])->first();

            Organo::updateOrCreate(
                ['id_unico' => $data['id_unico']],
                [
                    'nombre_es' => $data['nombre_es'],
                    'nombre_en' => $data['nombre_en'],
                    'nombre_tecnico' => $data['nombre_tecnico'],
                    'sistema_id' => $sistema?->id,
                    'region_anatomica_id' => $region?->id,
                    'color_resalte' => $data['color_resalte'],
                    'color_base' => $data['color_resalte'],
                    'orden_capa' => $data['orden_capa'],
                    'tiene_modelo_individual' => $data['tiene_modelo_individual'] ?? false,
                    'soporta_ar' => $data['soporta_ar'] ?? false,
                    'zoom_recomendado' => 2.5,
                    'transparencia_default' => 0.0,
                    'visible_inicialmente' => true,
                    'descripcion_estudiante' => 'Órgano del cuerpo humano',
                    'descripcion_profesional' => $data['nombre_tecnico'],
                ]
            );
        }
    }
}
