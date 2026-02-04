<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    function index() 
    {
        return view('auth');
    }

    public function login(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        
        if((Auth::attempt($validated)))
        {
            return redirect()
                ->intended(route('dashboard.index'));
        };

        return back()
            ->with('status', 'warning')
            ->with('message', 'Username/Password tidak sama');
    }    

}
