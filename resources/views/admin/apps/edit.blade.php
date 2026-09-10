@extends('admin.layout')

@section('title', 'Edit Target App: ' . $app->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white">Edit Target App: {{ $app->name }}</h2>
            <p class="text-xs text-slate-400">Update app settings and icon</p>
        </div>
        <a href="{{ route('admin.apps.index') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to App List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.apps.update', $app) }}" enctype="multipart/form-data" class="bg-dark-900 border border-dark-700/70 rounded-2xl p-6 space-y-5 shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">App Name</label>
                <input type="text" name="name" required value="{{ old('name', $app->name) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Badge</label>
                <input type="text" name="badge" value="{{ old('badge', $app->badge) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Category</label>
                <select name="category" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
                    <option value="calling" {{ $app->category === 'calling' ? 'selected' : '' }}>Calling (imo, WhatsApp, etc.)</option>
                    <option value="messenger" {{ $app->category === 'messenger' ? 'selected' : '' }}>Messenger (Telegram, FB, etc.)</option>
                    <option value="gaming" {{ $app->category === 'gaming' ? 'selected' : '' }}>Gaming (Free Fire, Discord, etc.)</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Package Identifier</label>
                <input type="text" name="package_name" value="{{ old('package_name', $app->package_name) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Sort Order</label>
            <input type="number" name="order" required value="{{ old('order', $app->order) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <!-- Current Icon -->
        <div class="flex items-center gap-3 p-3 rounded-xl bg-dark-800/40 border border-dark-700">
            <img src="{{ asset($app->icon) }}" class="w-10 h-10 rounded-xl object-contain bg-dark-900 border border-dark-700 p-1">
            <div>
                <span class="text-xs font-bold text-slate-200">Current Icon:</span>
                <span class="text-[10px] text-slate-400 block font-mono">{{ $app->icon }}</span>
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Change App Icon</label>
            <input type="file" name="icon_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
            <input type="text" name="icon_url" value="{{ $app->icon }}" class="w-full bg-dark-800 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white mt-1">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Hook Instructions & Notes</label>
            <textarea name="guide_info" rows="3" class="w-full bg-dark-800 border border-dark-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-purple-500">{{ old('guide_info', $app->guide_info) }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ $app->is_active ? 'checked' : '' }} class="w-4 h-4 rounded accent-purple-500">
            <label class="text-xs font-bold text-slate-300">Active on Frontend</label>
        </div>

        <div class="pt-4 border-t border-dark-800 flex justify-end gap-3">
            <a href="{{ route('admin.apps.index') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 text-slate-300 text-xs font-bold">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded-xl gradient-btn text-white text-xs font-bold">Update Target App</button>
        </div>
    </form>

</div>
@endsection
