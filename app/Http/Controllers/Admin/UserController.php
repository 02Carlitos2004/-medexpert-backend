<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('role');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json($users);
    }

    public function show($id): JsonResponse
    {
        $user = User::with(['role', 'perfilPaciente', 'perfilMedico', 'perfilEnfermera', 'consultas'])
            ->find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    public function updateRole(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|in:user,admin,professional',
            'role_id' => 'sometimes|integer|exists:roles,id',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->update($validated);

        return response()->json(['message' => 'Rol actualizado', 'user' => $user]);
    }

    public function toggleActivo($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->update(['activo' => !$user->activo]);

        return response()->json([
            'message' => $user->activo ? 'Usuario activado' : 'Usuario desactivado',
            'user' => $user,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
