@extends('admin.layout')

@section('title', 'Add New Target App')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white">Add Target App</h2>
            <p class="text-xs text-slate-400">Configure app icon, badge, and hook instructions</p>
        </div>
        <a href="{{ route('admin.apps.index') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to App List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.apps.store') }}" enctype="multipart/form-data" class="bg-dark-900 border border-dark-700/70 rounded-2xl p-6 space-y-5 shadow-xl">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">App Name</label>
                <input type="text" name="name" required placeholder="e.g. imo HD" value="{{ old('name') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Badge (Optional)</label>
                <input type="text" name="badge" placeholder="e.g. HD, PRO, GAME" value="{{ old('badge') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Category</label>
                <select name="category" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
                    <option value="calling">Calling (imo, WhatsApp, etc.)</option>
                    <option value="messenger">Messenger (Telegram, FB, etc.)</option>
                    <option value="gaming">Gaming (Free Fire, Discord, etc.)</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Package Identifier (Optional)</label>
                <input type="text" name="package_name" placeholder="com.imo.android.imohd" value="{{ old('package_name') }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Sort Order</label>
            <input type="number" name="order" required value="{{ old('order', 10) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">App Icon (SVG / PNG / WebP)</label>
            <input type="file" name="icon_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-dark-800 file:text-purple-400 hover:file:bg-dark-700 cursor-pointer">
            <input type="text" name="icon_url" placeholder="Or custom path: /images/apps/imo_hd.svg" class="w-full bg-dark-800 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white mt-1">
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-300">Hook Instructions & Notes</label>
            <textarea name="guide_info" rows="3" placeholder="Explain how users can route microphone through this app..." class="w-full bg-dark-800 border border-dark-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-purple-500"></textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded accent-purple-500">
            <label class="text-xs font-bold text-slate-300">Active on Frontend</label>
        </div>

        <div class="pt-4 border-t border-dark-800 flex justify-end gap-3">
            <a href="{{ route('admin.apps.index') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 text-slate-300 text-xs font-bold">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded-xl gradient-btn text-white text-xs font-bold">Save Target App</button>
        </div>
    </form>

</div>
@endsection
