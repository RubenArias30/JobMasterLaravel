<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Apply JWT authentication middleware to all routes except 'login'
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Log in and return the JWT token.
     */
    public function login()
    {
        $credentials = request(['nif', 'password']);

        //try {
            // Authenticate the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }

        // } catch (JWTException $e) {
        //     return response()->json(['error' => 'Could not create token'], 500);
        // }



        // Respond with the token and role
        return $this->respondWithToken($token);
    }

  /**
     * Returns information about the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
     $user = auth()->user();
    $employee = Employees::where('users_id', $user->id)->first();
    // Check if an employee record was found for the authenticated user
    if ($employee) {
        return response()->json(['name' => $employee->name]);
    } else {
        // If no employee record is found, return the user's NIF as the username
        return response()->json(['name' => $user->nif]);
    }
    }

    /**
     * Logs out the user (invalidates the JWT token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

      /**
     * Refreshes the JWT token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }
    /**
     * Constructs the response with the JWT token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // Get the authenticated user
        $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->setTTL(1440)->getTTL() * 60,
            'roles' => $user->roles,
        ]);
    }
}
