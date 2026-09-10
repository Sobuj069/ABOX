@extends('admin.layout')

@section('title', 'Site & Voice Changer Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h2 class="text-xl font-bold text-white">General & Guide Settings</h2>
        <p class="text-xs text-slate-400">Configure global website branding and the 3-step Voice Changer Guide</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-dark-900 border border-dark-700/70 rounded-2xl p-6 space-y-5 shadow-xl">
        @csrf

        <div class="space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-purple-400 border-b border-dark-800 pb-2">Branding</h3>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Application Name</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Tagline</label>
                <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>
        </div>

        <div class="space-y-4 pt-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-purple-400 border-b border-dark-800 pb-2">Voice Change Guide Steps</h3>

            <!-- Step 1 -->
            <div class="p-3.5 rounded-xl bg-dark-800/60 border border-dark-700 space-y-2">
                <label class="text-xs font-bold text-slate-200">Guide Step 1</label>
                <input type="text" name="guide_step1_title" value="{{ $settings['guide_step1_title'] }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white">
                <textarea name="guide_step1_desc" rows="2" class="w-full bg-dark-900 border border-dark-700 rounded-lg p-2.5 text-xs text-slate-300">{{ $settings['guide_step1_desc'] }}</textarea>
            </div>

            <!-- Step 2 -->
            <div class="p-3.5 rounded-xl bg-dark-800/60 border border-dark-700 space-y-2">
                <label class="text-xs font-bold text-slate-200">Guide Step 2</label>
                <input type="text" name="guide_step2_title" value="{{ $settings['guide_step2_title'] }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white">
                <textarea name="guide_step2_desc" rows="2" class="w-full bg-dark-900 border border-dark-700 rounded-lg p-2.5 text-xs text-slate-300">{{ $settings['guide_step2_desc'] }}</textarea>
            </div>

            <!-- Step 3 -->
            <div class="p-3.5 rounded-xl bg-dark-800/60 border border-dark-700 space-y-2">
                <label class="text-xs font-bold text-slate-200">Guide Step 3</label>
                <input type="text" name="guide_step3_title" value="{{ $settings['guide_step3_title'] }}" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-3 py-1.5 text-xs text-white">
                <textarea name="guide_step3_desc" rows="2" class="w-full bg-dark-900 border border-dark-700 rounded-lg p-2.5 text-xs text-slate-300">{{ $settings['guide_step3_desc'] }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-dark-800 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl gradient-btn text-white text-xs font-bold">Save Settings</button>
        </div>
    </form>

</div>
@endsection
