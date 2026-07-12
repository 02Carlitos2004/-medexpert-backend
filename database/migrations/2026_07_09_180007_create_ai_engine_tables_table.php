<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('config_ai_engine', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor_activo')->default('openrouter');
            $table->json('orden_fallback')->nullable();
            $table->text('api_key_cifrada')->nullable();
            $table->string('modelo')->nullable();
            $table->decimal('temperatura', 3, 2)->default(0.7);
            $table->integer('max_tokens')->default(2000);
            $table->integer('timeout')->default(30);
            $table->integer('cache_ttl')->default(3600);
            $table->integer('limite_diario_por_usuario')->default(50);
            $table->timestamps();
        });

        Schema::create('log_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('proveedor')->nullable();
            $table->string('modelo')->nullable();
            $table->integer('tokens_entrada')->nullable();
            $table->integer('tokens_salida')->nullable();
            $table->decimal('costo', 10, 6)->nullable();
            $table->integer('latencia_ms')->nullable();
            $table->boolean('exitoso')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_ia');
        Schema::dropIfExists('config_ai_engine');
    }
};
