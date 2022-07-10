<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            /* @var $user \App\Models\User */
            $user = auth()->user();

            return $this->sendLoginResponse($request, $user);
        }

        return response()->json(['error' => trans('auth.failed')], 401);
    }

    public function sendLoginResponse(Request $request, User $user)
    {
        $user->last_mobile_login_at = now();
        $user->save();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->api_token
        ], 200);
    }

    public function sendFailedLoginResponse($message = 'An error occured.')
    {
        return response([
            'error' => $message,
        ], 422);
    }
}
