<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function signup(){
        return view('auth.signup');
    }

    function create(Request $request){
        $request->validate([
            'name' => 'required',
            'email'=>'required|email|unique:App\Models\User',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'role' => 'READER',
            'password' => Hash::make($request->password),
        ]);

        $user->createToken('MyAppToken');

        return redirect()->route('signin.auth');
    }

    function signin(){
        return view('auth.signin');
    }

    function auth(Request $request){
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if(!Auth::attempt($credentials)){
            return back()
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended('/article');
    }

    function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('article.index');
    }
}
