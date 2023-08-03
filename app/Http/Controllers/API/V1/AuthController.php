<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repository\UserRepository;

class AuthController extends Controller
{
    protected $token;
    protected $response;
    public function register (RegisterRequest $request, UserRepository $userRepository){
        $user = $userRepository->create($request);
        $this->token = $user->createToken('mind')-> accessToken;
        $this->response = [
            'token' => $this->token,
            'user' => $user
        ];
        return ResponseFormatter::success($this->response, 'User created successfully', 201,'single');
    }

    public function login (LoginRequest $request){
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            return ResponseFormatter::error(null, 'Email or password is wrong', 401);
        }
        $user = auth()->user();
        $this->response = [
            'token' => $user->createToken('mind')-> accessToken,
            'user' => $user
        ];
        return ResponseFormatter::success($this->response, 'Login successfully', 200,'single');
    }

    public function logout (){
        $token = auth()->user()->token();
        $token->revoke();
        return ResponseFormatter::success(null, 'Logout successfully', 200,'single');
    }
}
