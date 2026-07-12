<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sistemas_cuerpo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->boolean('visible_por_defecto')->default(true);
            $table->integer('orden_jerarquico')->default(0);
            $table->timestamps();
        });

        Schema::create('regiones_anatomicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->json('posicion_camara')->nullable();
            $table->decimal('zoom_recomendado', 4, 2)->default(1.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regiones_anatomicas');
        Schema::dropIfExists('sistemas_cuerpo');
    }
};
