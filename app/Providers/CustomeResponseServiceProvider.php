<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Response;

class CustomeResponseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Response::macro('auth', function ($user , $token, $statusCode = 200) {
            return response()->json([
                'user' => $user,
                'token' => $token,
                'errorCode' => '0',
            ], $statusCode);
        });

        Response::macro('successJson', function ($data, $statusCode = 200) {
            return response()->json([
                'content' => $data,
                'errorCode' => '0',
            ], $statusCode);
        });

        Response::macro('errorJson', function ($errorCode, $statusCode = 400, $message = null) {
            $response = [
                'errorCode' => $errorCode,
            ];
            if (!empty($message)) {
                $response['message'] = $message;
            }
            return response()->json($response, $statusCode);
        });
    }
}
