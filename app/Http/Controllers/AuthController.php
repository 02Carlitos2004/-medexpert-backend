<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['sometimes', 'string', 'in:paciente,doctor,medico,enfermera,admin,user'],
            'learning_mode' => ['sometimes', 'string', 'in:student,professional'],
            'age' => ['sometimes', 'integer', 'min:1', 'max:150'],
            'gender' => ['sometimes', 'string', 'max:50'],
        ]);

        $role = $validated['role'] ?? 'paciente';
        if ($role === 'doctor') $role = 'medico';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'learning_mode' => $validated['learning_mode'] ?? 'student',
            'age' => $validated['age'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('role'),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'learning_mode' => ['sometimes', 'string', 'in:student,professional'],
            'age' => ['sometimes', 'integer', 'min:1', 'max:150'],
            'gender' => ['sometimes', 'string', 'max:50'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado',
            'user' => $user,
        ]);
    }
}
