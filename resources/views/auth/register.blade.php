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
        <h2 class="text-2xl font-black text-white">Create Account</h2>
        <p class="text-xs text-slate-400">Get 50 free credits to transform your voice</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Full Name</label>
            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Alex Smith" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Email Address</label>
            <input type="email" name="email" required value="{{ old('email') }}" placeholder="alex@example.com" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Password</label>
            <input type="password" name="password" required placeholder="Minimum 6 characters" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Confirm Password</label>
            <input type="password" name="password_confirmation" required placeholder="Re-type password" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <button type="submit" class="w-full py-3 rounded-xl gradient-btn text-white font-bold text-sm shadow-lg shadow-purple-600/30">
            Create Free Account
        </button>
    </form>

    <div class="text-center text-xs text-slate-400">
        Already have an account? <a href="{{ route('login') }}" class="text-purple-400 font-bold hover:underline">Sign In</a>
    </div>

</div>
@endsection
