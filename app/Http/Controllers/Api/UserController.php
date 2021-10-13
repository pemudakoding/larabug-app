<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Http\Requests\Api\RegisterFcmTokenRequest;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function registerFcmToken(RegisterFcmTokenRequest $request)
    {
        return $request->user('api')->fcmTokens()->create([
            'token' => $request->input('token'),
            'device' => $request->input('device')
        ]);
    }
}
