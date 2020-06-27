<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        /*$result = Auth::guard('web')->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ]);*/
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect('/');
        }

        /*if ($result) {
            return redirect('/');
        }*/

        return redirect()
            ->back()
            ->withInput()
            ->with('alert.error', 'Invalid username and password');
    }
}
