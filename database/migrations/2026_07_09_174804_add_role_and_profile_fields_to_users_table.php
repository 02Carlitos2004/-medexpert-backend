<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password');
            $table->foreignId('role_id')->nullable()->after('role')->constrained('roles')->nullOnDelete();
            $table->string('learning_mode')->default('student')->after('role_id');
            $table->integer('age')->nullable()->after('learning_mode');
            $table->string('gender')->nullable()->after('age');
            $table->boolean('activo')->default(true)->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'role_id', 'learning_mode', 'age', 'gender', 'activo']);
        });
    }
};
