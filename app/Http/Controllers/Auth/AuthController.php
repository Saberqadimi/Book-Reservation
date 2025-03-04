<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\Register;
use App\Services\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(Register $request)
    {
        return $this->authService->register($request);
    }

    public function login(Login $request)
    {
        return $this->authService->login($request);
    }
}
