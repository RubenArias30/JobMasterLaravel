<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Aplicar middleware de autenticación JWT a todas las rutas excepto 'login'
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Inicia sesión y devuelve el token JWT.
     */
    public function login()
    {
        $credentials = request(['nif', 'password']);

        try {
            // Autenticar al usuario
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }



        // Responder con el token y el rol
        return $this->respondWithToken($token);
    }

    /**
     * Devuelve la información del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Cierra la sesión del usuario (invalida el token JWT).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresca el token JWT.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Construye la respuesta con el token JWT.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // Obtener el usuario autenticado
    $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'roles' => $user->roles,
        ]);
    }
}
