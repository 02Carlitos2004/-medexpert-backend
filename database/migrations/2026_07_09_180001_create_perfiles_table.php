<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfiles_pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['masculino', 'femenino', 'otro'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->text('alergias')->nullable();
            $table->text('enfermedades_cronicas')->nullable();
            $table->string('grupo_sanguineo', 5)->nullable();
            $table->timestamps();
        });

        Schema::create('perfiles_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('especialidad')->nullable();
            $table->string('cedula_profesional', 50)->unique()->nullable();
            $table->string('hospital')->nullable();
            $table->string('telefono_consultorio', 20)->nullable();
            $table->json('horario_atencion')->nullable();
            $table->timestamps();
        });

        Schema::create('perfiles_enfermeras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('area_trabajo')->nullable();
            $table->string('cedula_profesional', 50)->unique()->nullable();
            $table->string('hospital')->nullable();
            $table->enum('turno', ['matutino', 'vespertino', 'nocturno'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles_enfermeras');
        Schema::dropIfExists('perfiles_medicos');
        Schema::dropIfExists('perfiles_pacientes');
    }
};
