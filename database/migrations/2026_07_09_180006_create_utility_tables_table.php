<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sinonimos_sintomas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintoma_id')->constrained()->cascadeOnDelete();
            $table->string('termino_coloquial');
            $table->string('idioma', 5)->default('es');
            $table->timestamps();
        });

        Schema::create('modelos_3d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organo_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('tipo', ['completo', 'individual', 'lod'])->default('individual');
            $table->string('url');
            $table->integer('version')->default(1);
            $table->bigInteger('tamano_bytes')->nullable();
            $table->integer('poligonos')->nullable();
            $table->timestamps();
        });

        Schema::create('cache_consultas', function (Blueprint $table) {
            $table->id();
            $table->string('hash_consulta', 64)->unique();
            $table->text('sintomas');
            $table->json('respuesta_json');
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_consultas');
        Schema::dropIfExists('modelos_3d');
        Schema::dropIfExists('sinonimos_sintomas');
    }
};
