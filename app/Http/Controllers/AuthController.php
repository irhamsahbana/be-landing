<?php

namespace App\Http\Controllers;

use App\Models\Signup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function attempt(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('signup.index');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('auth.login');
    }

    public function verifyEmail($token)
    {
        $data = Signup::where('token', $token)->first();

        if ($data) {
            $data->verified_at = now('UTC');
            $data->save();
        }

        return redirect()->away("https://coachcube-testing.vercel.app");
    }
}
