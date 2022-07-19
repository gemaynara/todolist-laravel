<?php

namespace App\Http\Controllers;


use App\Mail\SendMailCreateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        }


        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirm');

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4|same:password_confirm',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        }

        $data['is_admin']=1;
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        Mail::to($user->email)->queue(new SendMailCreateUser($user));
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário criado com sucesso!',
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'user' => $user
        ]);
    }

    public function getUser()
    {
        $user = JWTAuth::user();
//        $user = JWTAuth::authenticate($request->token);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário logado',
            'user' => $user
        ]);
    }


    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Não é possível atualizar o token'
        ], 400);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Sessão encerrada com sucesso!',
        ]);
    }


}
