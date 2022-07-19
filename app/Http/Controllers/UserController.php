<?php

namespace App\Http\Controllers;

use App\Mail\SendMailCreateUser;
use App\Mail\SendMailDeleteUser;
use App\Mail\SendMailEditUser;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirm', 'is_admin');

        $user = UserService::create($data);
        Mail::to($user->email)->queue(new SendMailCreateUser($user));

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário criado com sucesso!',
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirm', 'is_admin');

        $user = UserService::update($data, $id);

        Mail::to($data['email'])->queue(new SendMailEditUser($data));
        return response()->json([
            'status' => 'success',
            'message' => 'Usuário alterado com sucesso!',
            'data' => $user,
        ]);
    }

    public function show($id)
    {
        $user = User::query()->find($id);


        if (empty($user)) {
            return response()->json(['error' => 'Não encontrado.'], 200);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::query()->find($id);

        if (empty($user)) {
            return response()->json(['error' => 'Não encontrado.'], 400);
        }

        Mail::to($user->email)->queue(new SendMailDeleteUser($user));
        $user->tasks()->delete();
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário removido com sucesso!',
            'data' => $user
        ]);
    }
}
