<?php

namespace App\Http\Controllers\Profile;

use Validator;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return inertia('Profile/Index', [
            'user' => auth()->user(),
            'settings' => auth()->user()->settings,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('users')->ignore(auth()->user()->id)
            ],
        ]);

        $request->user()->update($request->all());

        return redirect()->route('panel.profile.show')->with('success', 'Profile updated');
    }

    public function changePassword(Request $request)
    {
        $data = $request->only([
            'password',
            'password_confirmation',
        ]);

        $validator = Validator::make($data, [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('panel.profile.show')->with('error', $validator->errors()->first());
        }

        if (! Hash::check($request->current, $request->user()->password)) {
            return redirect()->route('panel.profile.show')->with('error', 'Your current password is incorrect');
        }

        $request->user()->update([
            'password' => $request->new,
        ]);

        return redirect()->route('panel.profile.show')->with('success', 'Your password has been successfully changed!');
    }

    public function settings(Request $request)
    {
        $request->user()->update([
            'settings' => $request->input('settings'),
            'newsletter' => $request->input('newsletter', true),
        ]);

        return redirect()->route('panel.profile.show')->with('success', 'Profile settings updated');
    }
}
