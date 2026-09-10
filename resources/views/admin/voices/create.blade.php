@extends('admin.layout')

@section('title', 'Add New AI Voice Model')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white">Create AI Voice Profile</h2>
            <p class="text-xs text-slate-400">Configure sound transposition settings and media files</p>
        </div>
        <a href="{{ route('admin.voices.index') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Voice List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.voices.store') }}" enctype="multipart/form-data" class="bg-dark-900 border border-dark-700/70 rounded-2xl p-6 space-y-5 shadow-xl">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Voice Name -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Voice Name</label>
                <input type="text" name="name" required placeholder="e.g. AI Anime Girl" value="{{ old('name') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <!-- Gender -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Gender</label>
                <select name="gender" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                    <option value="other">Other / Robot</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Category -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Category</label>
                <input type="text" name="category" required placeholder="natural, anime, gaming, deep" value="{{ old('category', 'natural') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <!-- Sort Order -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Display Order</label>
                <input type="number" name="order" required value="{{ old('order', 10) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <!-- DSP Audio Transposition Parameters -->
        <div class="p-4 rounded-xl bg-dark-800/60 border border-dark-700 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-purple-400">Audio Modulation Parameters</h4>

            <div class="grid grid-cols-3 gap-3">
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Pitch Shift (st)</label>
                    <input type="number" step="0.5" name="pitch_shift" value="{{ old('pitch_shift', 3.0) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Speed Multiplier</label>
                    <input type="number" step="0.05" name="speed" value="{{ old('speed', 1.0) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Reverb / Room</label>
                    <input type="number" step="0.05" name="reverb" value="{{ old('reverb', 0.1) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- Media Files -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Avatar Image -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Avatar Image File (SVG / PNG / JPG)</label>
                <input type="file" name="avatar_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
                <p class="text-[10px] text-slate-500">Or use URL below if uploading externally</p>
                <input type="text" name="avatar_url" placeholder="/images/voices/ai_male4.svg" class="w-full bg-dark-800 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white mt-1">
            </div>

            <!-- Preview Audio -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Preview Audio Sample (WAV / MP3)</label>
                <input type="file" name="preview_audio_file" accept="audio/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
            </div>
        </div>

        <!-- Toggles: VIP & Active -->
        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-300">
                <input type="checkbox" name="is_vip" value="1" {{ old('is_vip') ? 'checked' : '' }} class="w-4 h-4 rounded accent-amber-500">
                <span class="text-amber-400"><i class="fa-solid fa-gem text-xs mr-1"></i> VIP Voice (Requires VIP Subscription)</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-300">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded accent-purple-500">
                <span>Active on Frontend</span>
            </label>
        </div>

        <div class="pt-4 border-t border-dark-800 flex justify-end gap-3">
            <a href="{{ route('admin.voices.index') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 text-slate-300 text-xs font-bold">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded-xl gradient-btn text-white text-xs font-bold">Create Voice Profile</button>
        </div>
    </form>

</div>
@endsection
