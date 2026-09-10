@extends('admin.layout')

@section('title', 'Edit AI Voice Model: ' . $voice->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white">Edit AI Voice: {{ $voice->name }}</h2>
            <p class="text-xs text-slate-400">Update audio parameters, badges, and media</p>
        </div>
        <a href="{{ route('admin.voices.index') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Voice List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.voices.update', $voice) }}" enctype="multipart/form-data" class="bg-dark-900 border border-dark-700/70 rounded-2xl p-6 space-y-5 shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Voice Name -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Voice Name</label>
                <input type="text" name="name" required value="{{ old('name', $voice->name) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <!-- Gender -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Gender</label>
                <select name="gender" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
                    <option value="female" {{ $voice->gender === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="male" {{ $voice->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="other" {{ $voice->gender === 'other' ? 'selected' : '' }}>Other / Robot</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Category -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Category</label>
                <input type="text" name="category" required value="{{ old('category', $voice->category) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <!-- Sort Order -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Display Order</label>
                <input type="number" name="order" required value="{{ old('order', $voice->order) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <!-- Audio Parameters -->
        <div class="p-4 rounded-xl bg-dark-800/60 border border-dark-700 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-purple-400">Audio Modulation Parameters</h4>

            <div class="grid grid-cols-3 gap-3">
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Pitch Shift (st)</label>
                    <input type="number" step="0.5" name="pitch_shift" value="{{ old('pitch_shift', $voice->pitch_shift) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Speed Multiplier</label>
                    <input type="number" step="0.05" name="speed" value="{{ old('speed', $voice->speed) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-medium text-slate-300">Reverb / Room</label>
                    <input type="number" step="0.05" name="reverb" value="{{ old('reverb', $voice->reverb) }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- Current Avatar & Audio Preview -->
        <div class="flex items-center gap-4 p-3 rounded-xl bg-dark-800/40 border border-dark-700">
            <img src="{{ asset($voice->avatar) }}" class="w-12 h-12 rounded-xl object-cover border border-purple-500/40">
            <div class="flex-1">
                <span class="text-xs font-bold text-slate-200">Current Media:</span>
                @if($voice->preview_audio)
                    <div class="mt-1">
                        <audio src="{{ asset($voice->preview_audio) }}" controls class="h-8 max-w-[200px]"></audio>
                    </div>
                @endif
            </div>
        </div>

        <!-- Replace Media Files -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Avatar Image -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Change Avatar Image File</label>
                <input type="file" name="avatar_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
                <input type="text" name="avatar_url" placeholder="Or custom URL" value="{{ $voice->avatar }}" class="w-full bg-dark-800 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white mt-1">
            </div>

            <!-- Preview Audio -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Change Preview Audio Sample</label>
                <input type="file" name="preview_audio_file" accept="audio/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
            </div>
        </div>

        <!-- Toggles: VIP & Active -->
        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-300">
                <input type="checkbox" name="is_vip" value="1" {{ $voice->is_vip ? 'checked' : '' }} class="w-4 h-4 rounded accent-amber-500">
                <span class="text-amber-400"><i class="fa-solid fa-gem text-xs mr-1"></i> VIP Voice (Requires VIP Subscription)</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-300">
                <input type="checkbox" name="is_active" value="1" {{ $voice->is_active ? 'checked' : '' }} class="w-4 h-4 rounded accent-purple-500">
                <span>Active on Frontend</span>
            </label>
        </div>

        <div class="pt-4 border-t border-dark-800 flex justify-end gap-3">
            <a href="{{ route('admin.voices.index') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 text-slate-300 text-xs font-bold">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded-xl gradient-btn text-white text-xs font-bold">Update Voice Profile</button>
        </div>
    </form>

</div>
@endsection
