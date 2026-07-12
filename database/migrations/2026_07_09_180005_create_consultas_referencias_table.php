<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('sintomas');
            $table->string('idioma', 5)->default('es');
            $table->enum('modo_aprendizaje', ['estudiante', 'profesional'])->default('estudiante');
            $table->enum('estado', ['pendiente', 'procesando', 'completada', 'error'])->default('pendiente');
            $table->timestamps();
        });

        Schema::create('resultados_consulta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained()->cascadeOnDelete();
            $table->json('respuesta_json')->nullable();
            $table->string('proveedor_usado')->nullable();
            $table->integer('tokens_usados')->nullable();
            $table->integer('latencia_ms')->nullable();
            $table->timestamps();
        });

        Schema::create('referencias_bibliograficas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['libro', 'guia_clinica', 'protocolo', 'articulo', 'otro'])->default('otro');
            $table->string('autor')->nullable();
            $table->string('titulo');
            $table->string('edicion')->nullable();
            $table->year('anio')->nullable();
            $table->string('editorial')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('doi')->nullable();
            $table->string('pmid', 20)->nullable();
            $table->string('url')->nullable();
            $table->enum('nivel_evidencia', ['A', 'B', 'C', 'D'])->default('C');
            $table->timestamps();
        });

        Schema::create('enfermedad_referencia', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained('enfermedades')->cascadeOnDelete();
            $table->foreignId('referencia_id')->constrained('referencias_bibliograficas')->cascadeOnDelete();
            $table->decimal('peso_relevancia', 5, 2)->default(1.0);
            $table->primary(['enfermedad_id', 'referencia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enfermedad_referencia');
        Schema::dropIfExists('referencias_bibliograficas');
        Schema::dropIfExists('resultados_consulta');
        Schema::dropIfExists('consultas');
    }
};
