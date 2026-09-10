@extends('admin.layout')

@section('title', 'Manage AI Voice Models')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-white">AI Voice Models</h2>
            <p class="text-xs text-slate-400">Configure avatars, pitch transposition parameters, and VIP locks</p>
        </div>
        <a href="{{ route('admin.voices.create') }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-purple-600/30 transition self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Add New Voice
        </a>
    </div>

    <!-- Voices Table -->
    <div class="bg-dark-900 border border-dark-700/70 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-dark-800 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-dark-700">
                    <tr>
                        <th class="p-4">Avatar & Name</th>
                        <th class="p-4">Type / Category</th>
                        <th class="p-4">Pitch Shift</th>
                        <th class="p-4">VIP Status</th>
                        <th class="p-4">Preview Audio</th>
                        <th class="p-4">Active</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @foreach($voices as $voice)
                        <tr class="hover:bg-dark-850/60 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($voice->avatar) }}" class="w-10 h-10 rounded-xl object-cover bg-dark-800 border border-dark-700">
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $voice->name }}</div>
                                        <span class="text-[10px] text-slate-400">Order: #{{ $voice->order }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="capitalize font-medium text-slate-200">{{ $voice->gender }}</span>
                                <span class="block text-[10px] text-purple-400">{{ ucfirst($voice->category) }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-200">
                                {{ $voice->pitch_shift > 0 ? '+' : '' }}{{ $voice->pitch_shift }} st
                            </td>
                            <td class="p-4">
                                @if($voice->is_vip)
                                    <span class="px-2 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 font-black text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-gem text-[8px]"></i> VIP ONLY
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-purple-500/20 border border-purple-500/40 text-purple-300 font-bold text-[10px]">
                                        FREE
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($voice->preview_audio)
                                    <audio src="{{ asset($voice->preview_audio) }}" controls class="h-8 max-w-[150px]"></audio>
                                @else
                                    <span class="text-slate-500">None</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($voice->is_active)
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.voices.edit', $voice) }}" class="p-2 rounded-lg bg-dark-800 hover:bg-dark-700 text-purple-400 hover:text-purple-300 text-xs inline-block">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.voices.destroy', $voice) }}" class="inline-block" onsubmit="return confirm('Delete this voice profile?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-dark-800 hover:bg-dark-700 text-rose-400 hover:text-rose-300 text-xs">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-dark-800">
            {{ $voices->links() }}
        </div>
    </div>

</div>
@endsection
