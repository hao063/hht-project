<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    //

    public function login(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $user     = Auth::attempt([
            'email'    => $dataForm['email'],
            'password' => $dataForm['password'],
        ]);
        if (is_null($user)) {
            return $this->responseData([], 'Email or password not correct', 401);
        }
        $auth              = Auth::user();
        $auth->token_login = bin2hex(random_bytes(16));
        $auth->save();
        $data = [
            'username' => $auth->name,
            'email'    => $auth->email,
            'token'    => $auth->token_login,
        ];
        return $this->responseData($data, 'Logged in successfully');
    }

}
