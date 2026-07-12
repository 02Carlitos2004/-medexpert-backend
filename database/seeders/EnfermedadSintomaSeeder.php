<?php

namespace Database\Seeders;

use App\Models\Enfermedad;
use App\Models\Sintoma;
use Illuminate\Database\Seeder;

class EnfermedadSintomaSeeder extends Seeder
{
    public function run(): void
    {
        $enfermedades = [
            [
                'nombre' => 'Gastritis',
                'descripcion' => 'Inflamación de la mucosa del estómago que puede causar dolor, náuseas y malestar abdominal.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Gastroenterología',
                'sintomas' => ['Dolor abdominal', 'Náuseas', 'Vómitos', 'Acidez estomacal', 'Hinchazón abdominal'],
            ],
            [
                'nombre' => 'Úlcera Gástrica',
                'descripcion' => 'Lesión abierta en el revestimiento del estómago o duodeno.',
                'nivel_urgencia' => 'alta',
                'especialidad' => 'Gastroenterología',
                'sintomas' => ['Dolor abdominal intenso', 'Náuseas', 'Vómitos con sangre', 'Pérdida de peso', 'Heces oscuras'],
            ],
            [
                'nombre' => 'Apendicitis',
                'descripcion' => 'Inflamación del apéndice vermiforme que requiere intervención quirúrgica.',
                'nivel_urgencia' => 'critica',
                'especialidad' => 'Cirugía General',
                'sintomas' => ['Dolor abdominal bajo derecho', 'Fiebre', 'Náuseas', 'Vómitos', 'Pérdida de apetito'],
            ],
            [
                'nombre' => 'Neumonía',
                'descripcion' => 'Infección que inflama los sacos de aire en uno o ambos pulmones.',
                'nivel_urgencia' => 'alta',
                'especialidad' => 'Neumología',
                'sintomas' => ['Tos con flema', 'Fiebre alta', 'Dificultad para respirar', 'Dolor torácico', 'Escalofríos'],
            ],
            [
                'nombre' => 'Asma',
                'descripcion' => 'Enfermedad crónica que inflama y estrecha las vías aéreas.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Neumología',
                'sintomas' => ['Dificultad para respirar', 'Sibilancias', 'Tos', 'Opresión en el pecho'],
            ],
            [
                'nombre' => 'Infarto Agudo de Miocardio',
                'descripcion' => 'Interrupción del flujo sanguíneo al músculo cardíaco.',
                'nivel_urgencia' => 'critica',
                'especialidad' => 'Cardiología',
                'sintomas' => ['Dolor en el pecho', 'Dificultad para respirar', 'Sudoración', 'Náuseas', 'Mareos', 'Dolor en el brazo izquierdo'],
            ],
            [
                'nombre' => 'Hipertensión Arterial',
                'descripcion' => 'Presión arterial crónicamente elevada que puede dañar órganos.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Cardiología',
                'sintomas' => ['Dolor de cabeza', 'Mareos', 'Visión borrosa', 'Sangrado nasal'],
            ],
            [
                'nombre' => 'Diabetes Mellitus Tipo 2',
                'descripcion' => 'Trastorno metabólico que afecta la forma en que el cuerpo usa la insulina.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Endocrinología',
                'sintomas' => ['Aumento de la sed', 'Micción frecuente', 'Fatiga', 'Visión borrosa', 'Heridas que no cicatrizan'],
            ],
            [
                'nombre' => 'Colecistitis',
                'descripcion' => 'Inflamación de la vesícula biliar, generalmente por cálculos biliares.',
                'nivel_urgencia' => 'alta',
                'especialidad' => 'Cirugía General',
                'sintomas' => ['Dolor en hipocondrio derecho', 'Náuseas', 'Vómitos', 'Fiebre', 'Ictericia'],
            ],
            [
                'nombre' => 'Pancreatitis',
                'descripcion' => 'Inflamación del páncreas que puede ser aguda o crónica.',
                'nivel_urgencia' => 'alta',
                'especialidad' => 'Gastroenterología',
                'sintomas' => ['Dolor abdominal intenso', 'Náuseas', 'Vómitos', 'Fiebre', 'Aceleración del pulso'],
            ],
            [
                'nombre' => 'Cefalea Tensional',
                'descripcion' => 'Dolor de cabeza más común, generalmente por tensión muscular.',
                'nivel_urgencia' => 'baja',
                'especialidad' => 'Neurología',
                'sintomas' => ['Dolor de cabeza', 'Tensión en cuello', 'Sensibilidad en cuero cabelludo'],
            ],
            [
                'nombre' => 'Migraña',
                'descripcion' => 'Dolor de cabeza recurrente intenso, frecuentemente unilateral.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Neurología',
                'sintomas' => ['Dolor de cabeza intenso', 'Náuseas', 'Sensibilidad a la luz', 'Sensibilidad al sonido', 'Aura visual'],
            ],
            [
                'nombre' => 'Infección Urinaria',
                'descripcion' => 'Infección que afecta cualquier parte del sistema urinario.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Urología',
                'sintomas' => ['Orina frecuente', 'Dolor al orinar', 'Orina turbia', 'Dolor pélvico', 'Fiebre'],
            ],
            [
                'nombre' => 'Gastroenteritis',
                'descripcion' => 'Inflamación del estómago e intestinos, generalmente viral.',
                'nivel_urgencia' => 'media',
                'especialidad' => 'Gastroenterología',
                'sintomas' => ['Diarrea', 'Vómitos', 'Dolor abdominal', 'Fiebre', 'Deshidratación'],
            ],
            [
                'nombre' => 'Hepatitis',
                'descripcion' => 'Inflamación del hígado, puede ser viral, alcohólica o autoinmune.',
                'nivel_urgencia' => 'alta',
                'especialidad' => 'Gastroenterología',
                'sintomas' => ['Ictericia', 'Dolor en hipocondrio derecho', 'Fatiga', 'Náuseas', 'Orina oscura'],
            ],
        ];

        foreach ($enfermedades as $data) {
            $sintomaNames = $data['sintomas'];
            unset($data['sintomas']);

            $enfermedad = Enfermedad::updateOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );

            foreach ($sintomaNames as $nombreSintoma) {
                $sintoma = Sintoma::firstOrCreate(['nombre' => $nombreSintoma]);
                $enfermedad->sintomas()->syncWithoutDetaching([
                    $sintoma->id => ['peso' => 1.0]
                ]);
            }
        }
    }
}
