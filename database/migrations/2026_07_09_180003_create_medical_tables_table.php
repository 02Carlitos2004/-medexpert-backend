<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organos', function (Blueprint $table) {
            $table->id();
            $table->string('id_unico')->unique();
            $table->string('nombre_es');
            $table->string('nombre_en')->nullable();
            $table->string('nombre_tecnico')->nullable();
            $table->foreignId('sistema_id')->constrained('sistemas_cuerpo')->nullOnDelete();
            $table->foreignId('region_anatomica_id')->nullable()->constrained('regiones_anatomicas')->nullOnDelete();
            $table->json('regiones_internas')->nullable();
            $table->json('subestructuras')->nullable();
            $table->json('organos_vecinos')->nullable();
            $table->integer('orden_capa')->default(3);
            $table->string('modelo_3d_url')->nullable();
            $table->string('modelo_lod_url')->nullable();
            $table->boolean('tiene_modelo_individual')->default(false);
            $table->boolean('soporta_ar')->default(false);
            $table->json('posicion_camara')->nullable();
            $table->json('rotacion_camara')->nullable();
            $table->decimal('zoom_recomendado', 4, 2)->default(1.0);
            $table->string('color_base', 7)->nullable();
            $table->string('color_resalte', 7)->nullable();
            $table->string('color_estudiante', 7)->nullable();
            $table->string('color_profesional', 7)->nullable();
            $table->decimal('transparencia_default', 3, 2)->default(0.0);
            $table->boolean('visible_inicialmente')->default(true);
            $table->json('animaciones_disponibles')->nullable();
            $table->text('descripcion_estudiante')->nullable();
            $table->text('descripcion_profesional')->nullable();
            $table->string('icono_referencia')->nullable();
            $table->timestamps();
        });

        Schema::create('enfermedades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('nivel_urgencia', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->string('especialidad')->nullable();
            $table->boolean('activo')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sintomas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['medicamento', 'cirugia', 'terapia', 'dieta', 'otro'])->default('otro');
            $table->timestamps();
        });

        Schema::create('estudios_diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->text('preparacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios_diagnosticos');
        Schema::dropIfExists('tratamientos');
        Schema::dropIfExists('sintomas');
        Schema::dropIfExists('enfermedades');
        Schema::dropIfExists('organos');
    }
};
