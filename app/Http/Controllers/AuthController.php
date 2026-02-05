<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    function index() 
    {
        // Create a simple option object to avoid errors
        $option = new class {
            public function getByKey($key) {
                return config('app.name', 'SARANAS');
            }
        };
        
        return view('auth', compact('option'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            
            return redirect()
                ->intended(route('dashboard.index'))
                ->with('status', 'success')
                ->with('message', 'Login berhasil! Selamat datang ' . Auth::user()->name);
        }

        return back()
            ->with('status', 'warning')
            ->with('message', 'Username/Password tidak sama')
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('auth.index')
            ->with('status', 'success')
            ->with('message', 'Anda telah logout');
    }    

}
