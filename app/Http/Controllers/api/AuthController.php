<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = auth()->user();
            $user->token=$user->createToken('myAppToken')->plainTextToken;
            return $this->response(self::SUCCEED, self::SUCCEED_CODE,
                new UserResource($user)
            );
        }

        return $this->response(self::ERROR, self::ERROR_AUTHENTICATION);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }
}
