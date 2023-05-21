<?php

namespace App\Http\Controllers;
Use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        //bei der login-methode wird nicht überprüft, da sich der user ja sonst nicht anmelden könnte
        $this->middleware('auth:api', ['except' => ['login']]);

        //sollte bei der create auch nicht überprüft werden? Da ein nicht-eingeloggter user ja auch was erstellen können soll?
    }

    public function login(){
        $credentials = \request(['email', 'password']);

        if(! $token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function me(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }


}
