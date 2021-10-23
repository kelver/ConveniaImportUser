<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\{
    LoginValidate,
    RegisterValidate
};
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function login(LoginValidate $request):JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        $success['token'] =  $user->createToken('Client')->accessToken;
        return response()->json(['success' => $success], 200);
    }

    public function register(RegisterValidate $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $success['token'] =  $user->createToken('Client')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], 200);
    }
}
