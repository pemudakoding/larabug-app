<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function registerFcmToken(Request $request)
    {
        $this->validate($request, [
            'token' => [
                'required',
                'string'
            ]
        ]);

        return $request->user()->fcmTokens()->create(['token' => $request->input('token')]);
    }
}
