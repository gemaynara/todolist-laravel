<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public static function create(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4|same:password_confirm',
            'is_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        }

        $data['password'] = Hash::make($data['password']);

        return User::query()->create($data);
    }

    public static function update(array $data, int $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,id,' . $id,
            'password' => 'required|string|min:6|same:password_confirm',
            'is_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        }


        return User::query()->where('id', $id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => $data['is_admin'],
        ]);
    }


}
