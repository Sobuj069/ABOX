<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'credits' => 50,
            'is_vip' => false,
            'avatar' => '/images/voices/ai_male4.svg',
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function switchRole(Request $request)
    {
        $role = $request->input('role', 'user');
        $targetEmail = $role === 'admin' ? 'admin@abox.com' : 'user@abox.com';
        $user = User::where('email', $targetEmail)->first();

        if ($user) {
            Auth::login($user);
            return response()->json([
                'success' => true,
                'message' => "Switched to " . ucfirst($role) . " account ({$user->name})",
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_vip' => $user->hasVip(),
                    'credits' => $user->credits,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}
