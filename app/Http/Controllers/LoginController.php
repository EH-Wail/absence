<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';
    public function index()
    {
        return view('login');
    }
    public function attempt(Request $request)
    {
        $user_info = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ],[
            'username.required' => 'Veuillez saisir votre nom d\'utilisateur',
            'password.required' => 'Veuillez saisir votre mot de passe'
        ]);
        if (Auth::attempt($user_info, $request->filled('remember'))){
            $request->session()->regenerate();
            return redirect()->intended('home');
        }
        return back()->withErrors([
            'username' => "nom ou mot de passe incorrecte",
        ])->onlyInput('username');
    }

    
}
