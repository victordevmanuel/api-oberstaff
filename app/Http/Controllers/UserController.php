<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function store(Request $request)
    {
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Correo ya registrado'], 409);
        }

        // Asignar el rol basado en el valor de 'role' en la solicitud
        $role = ($request->role === 'admin') ? 1 : 0; // Puedes personalizar estos valores si lo deseas

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario creado correctamente']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => ['email' => $user->email],
                'role' => $user->role == 1 ? 'admin' : 'user'
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

}
