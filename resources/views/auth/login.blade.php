@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col justify-center px-6 py-12 max-w-sm mx-auto w-full space-y-6">

    <!-- Logo & Title -->
    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pink-500 via-purple-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-pink-500/30 mx-auto">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M2 10v4M6 6v12M10 3v18M14 8v8M18 5v14M22 10v4"/>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-white">Sign In to ABox</h2>
        <p class="text-xs text-slate-400">Manage your AI voices, recordings, and VIP features</p>
    </div>

    <!-- 1-Click Quick Demo Login Shortcuts -->
    <div class="p-3.5 rounded-2xl bg-dark-800/80 border border-purple-500/30 space-y-2">
        <span class="text-[10px] uppercase tracking-wider text-purple-300 font-black block text-center">⚡ 1-Click Demo Login</span>
        <div class="grid grid-cols-2 gap-2">
            <button onclick="fillLogin('user@abox.com', 'password123')" type="button" class="py-2 px-2.5 rounded-xl bg-dark-700 hover:bg-dark-600 text-slate-200 text-xs font-bold border border-dark-600 text-center transition">
                <i class="fa-solid fa-user text-purple-400 text-[10px] mr-1"></i> Demo User
            </button>
            <button onclick="fillLogin('admin@abox.com', 'password123')" type="button" class="py-2 px-2.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/40 text-purple-200 text-xs font-bold text-center transition">
                <i class="fa-solid fa-shield text-amber-400 text-[10px] mr-1"></i> Admin User
            </button>
        </div>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Email Address</label>
            <input type="email" name="email" id="loginEmail" required value="{{ old('email', 'user@abox.com') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Password</label>
            <input type="password" name="password" id="loginPassword" required value="password123" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded accent-purple-500">
                <span>Remember me</span>
            </label>
            <a href="{{ route('home') }}" class="text-purple-400 hover:underline">Back to app</a>
        </div>

        <button type="submit" class="w-full py-3 rounded-xl gradient-btn text-white font-bold text-sm shadow-lg shadow-purple-600/30">
            Sign In
        </button>
    </form>

    <div class="text-center text-xs text-slate-400">
        Don't have an account? <a href="{{ route('register') }}" class="text-purple-400 font-bold hover:underline">Create Account</a>
    </div>

</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('loginEmail').value = email;
        document.getElementById('loginPassword').value = password;
    }
</script>
@endsection
