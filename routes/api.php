<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EnfermedadController;
use App\Http\Controllers\Api\OrganoController;
use App\Http\Controllers\Api\SintomaController;
use App\Http\Controllers\Api\TratamientoController;
use App\Http\Controllers\Api\ConsultaController;
use App\Http\Controllers\Api\ReferenciaController;
use App\Http\Controllers\Api\AIConfigController;
use App\Http\Controllers\Api\PerfilController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnfermedadAdminController;
use App\Http\Controllers\Admin\OrganoAdminController;
use App\Http\Controllers\Admin\SintomaAdminController;
use App\Http\Controllers\Admin\AIConfigAdminController;
use App\Http\Controllers\Admin\ConsultaAdminController;
use App\Http\Controllers\Admin\LogAdminController;
use App\Http\Controllers\Admin\ReferenciaAdminController;
use Illuminate\Support\Facades\Route;

// ============================================
// AUTH (público)
// ============================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ============================================
// AUTENTICADO (usuario logueado)
// ============================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Perfil
    Route::prefix('perfil')->group(function () {
        Route::get('/', [PerfilController::class, 'show']);
        Route::put('/paciente', [PerfilController::class, 'updatePaciente']);
        Route::put('/medico', [PerfilController::class, 'updateMedico']);
        Route::put('/enfermera', [PerfilController::class, 'updateEnfermera']);
    });

    // Consultas
    Route::prefix('consultas')->group(function () {
        Route::get('/', [ConsultaController::class, 'index']);
        Route::post('/', [ConsultaController::class, 'store']);
        Route::get('/{id}', [ConsultaController::class, 'show']);
    });

    // Datos médicos (lectura)
    Route::prefix('enfermedades')->group(function () {
        Route::get('/', [EnfermedadController::class, 'index']);
        Route::get('/{id}', [EnfermedadController::class, 'show']);
        Route::get('/organo/{organoId}', [EnfermedadController::class, 'porOrgano']);
        Route::post('/por-sintomas', [EnfermedadController::class, 'porSintomas']);
    });

    Route::prefix('organos')->group(function () {
        Route::get('/', [OrganoController::class, 'index']);
        Route::get('/sistemas', [OrganoController::class, 'sistemas']);
        Route::get('/regiones', [OrganoController::class, 'regiones']);
        Route::get('/{id}', [OrganoController::class, 'show']);
        Route::get('/unico/{idUnico}', [OrganoController::class, 'porIdUnico']);
    });

    Route::prefix('sintomas')->group(function () {
        Route::get('/', [SintomaController::class, 'index']);
        Route::get('/search', [SintomaController::class, 'search']);
        Route::get('/{id}', [SintomaController::class, 'show']);
    });

    Route::prefix('tratamientos')->group(function () {
        Route::get('/', [TratamientoController::class, 'index']);
        Route::get('/{id}', [TratamientoController::class, 'show']);
        Route::get('/enfermedad/{enfermedadId}', [TratamientoController::class, 'porEnfermedad']);
    });

    Route::prefix('referencias')->group(function () {
        Route::get('/', [ReferenciaController::class, 'index']);
        Route::get('/{id}', [ReferenciaController::class, 'show']);
        Route::get('/enfermedad/{enfermedadId}', [ReferenciaController::class, 'porEnfermedad']);
    });

    Route::get('/ai/config', [AIConfigController::class, 'index']);

    // ============================================
    // ADMIN (solo administradores)
    // ============================================
    Route::prefix('admin')->middleware('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Usuarios
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::put('/{id}/role', [UserController::class, 'updateRole']);
            Route::put('/{id}/toggle', [UserController::class, 'toggleActivo']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        // Enfermedades
        Route::prefix('enfermedades')->group(function () {
            Route::get('/', [EnfermedadAdminController::class, 'index']);
            Route::post('/', [EnfermedadAdminController::class, 'store']);
            Route::put('/{id}', [EnfermedadAdminController::class, 'update']);
            Route::delete('/{id}', [EnfermedadAdminController::class, 'destroy']);
        });

        // Órganos
        Route::prefix('organos')->group(function () {
            Route::get('/', [OrganoAdminController::class, 'index']);
            Route::post('/', [OrganoAdminController::class, 'store']);
            Route::put('/{id}', [OrganoAdminController::class, 'update']);
            Route::delete('/{id}', [OrganoAdminController::class, 'destroy']);
        });

        // Síntomas
        Route::prefix('sintomas')->group(function () {
            Route::get('/', [SintomaAdminController::class, 'index']);
            Route::post('/', [SintomaAdminController::class, 'store']);
            Route::put('/{id}', [SintomaAdminController::class, 'update']);
            Route::delete('/{id}', [SintomaAdminController::class, 'destroy']);
        });

        // Referencias
        Route::prefix('referencias')->group(function () {
            Route::get('/', [ReferenciaAdminController::class, 'index']);
            Route::post('/', [ReferenciaAdminController::class, 'store']);
            Route::put('/{id}', [ReferenciaAdminController::class, 'update']);
            Route::delete('/{id}', [ReferenciaAdminController::class, 'destroy']);
        });

        // Consultas
        Route::prefix('consultas')->group(function () {
            Route::get('/', [ConsultaAdminController::class, 'index']);
            Route::get('/{id}', [ConsultaAdminController::class, 'show']);
            Route::delete('/{id}', [ConsultaAdminController::class, 'destroy']);
        });

        // Configuración IA
        Route::prefix('ai-config')->group(function () {
            Route::get('/', [AIConfigAdminController::class, 'index']);
            Route::put('/', [AIConfigAdminController::class, 'update']);
            Route::get('/estadisticas', [AIConfigAdminController::class, 'estadisticas']);
        });

        // Logs
        Route::prefix('logs')->group(function () {
            Route::get('/', [LogAdminController::class, 'index']);
            Route::get('/errores', [LogAdminController::class, 'errores']);
            Route::get('/{id}', [LogAdminController::class, 'show']);
        });
    });
});
