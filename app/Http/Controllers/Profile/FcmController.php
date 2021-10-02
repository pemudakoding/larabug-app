<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    public function index()
    {
        $tokens = auth()->user()->fcmTokens()->get();

        return inertia('Profile/FcmTokens/Index', [
            'tokens' => $tokens
        ]);
    }
}
