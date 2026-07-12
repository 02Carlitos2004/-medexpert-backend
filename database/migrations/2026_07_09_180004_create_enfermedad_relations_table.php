<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enfermedad_sintoma', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained('enfermedades')->cascadeOnDelete();
            $table->foreignId('sintoma_id')->constrained('sintomas')->cascadeOnDelete();
            $table->decimal('peso', 5, 2)->default(1.0);
            $table->primary(['enfermedad_id', 'sintoma_id']);
        });

        Schema::create('enfermedad_organo', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained('enfermedades')->cascadeOnDelete();
            $table->foreignId('organo_id')->constrained('organos')->cascadeOnDelete();
            $table->primary(['enfermedad_id', 'organo_id']);
        });

        Schema::create('enfermedad_tratamiento', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained('enfermedades')->cascadeOnDelete();
            $table->foreignId('tratamiento_id')->constrained('tratamientos')->cascadeOnDelete();
            $table->primary(['enfermedad_id', 'tratamiento_id']);
        });

        Schema::create('enfermedad_estudio', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained('enfermedades')->cascadeOnDelete();
            $table->foreignId('estudio_id')->constrained('estudios_diagnosticos')->cascadeOnDelete();
            $table->primary(['enfermedad_id', 'estudio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enfermedad_estudio');
        Schema::dropIfExists('enfermedad_tratamiento');
        Schema::dropIfExists('enfermedad_organo');
        Schema::dropIfExists('enfermedad_sintoma');
    }
};
