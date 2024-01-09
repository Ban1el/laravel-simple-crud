<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login()
    {
        return view('students.login');
    }

    public function register()
    {
        return view('students.register');
    }

    public function store()
    {
        $validate = request()->validate([
            'name' => 'required|min:3|max:60',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
        ]);

        return redirect()->route('dashboard')->with('success', 'Account Created!');
    }

    public function authenticate(Request $request)
    {
        $validate = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $remmember_me = $request->has('remmember-me');

        if (auth()->attempt($validate, $remmember_me)) {
            request()->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in!');
        }
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
