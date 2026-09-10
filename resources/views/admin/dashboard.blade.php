@extends('admin.layout')

@section('title', 'Admin Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Users Card -->
        <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 relative overflow-hidden shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Users</span>
                    <h3 class="text-2xl font-black text-white mt-1">{{ $stats['total_users'] }}</h3>
                    <span class="text-[11px] text-amber-400 font-semibold mt-1 inline-block">{{ $stats['vip_users'] }} VIP Members</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-950 border border-purple-500/30 text-purple-400 flex items-center justify-center">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Voices Card -->
        <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 relative overflow-hidden shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">AI Voice Models</span>
                    <h3 class="text-2xl font-black text-white mt-1">{{ $stats['total_voices'] }}</h3>
                    <span class="text-[11px] text-purple-400 font-semibold mt-1 inline-block">{{ $stats['free_voices'] }} Free / {{ $stats['vip_voices'] }} VIP</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-pink-950 border border-pink-500/30 text-pink-400 flex items-center justify-center">
                    <i class="fa-solid fa-microphone-lines text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Target Apps Card -->
        <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 relative overflow-hidden shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Integrated Apps</span>
                    <h3 class="text-2xl font-black text-white mt-1">{{ $stats['total_apps'] }}</h3>
                    <span class="text-[11px] text-emerald-400 font-semibold mt-1 inline-block">{{ $stats['active_apps'] }} Hook Active</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-950 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                    <i class="fa-solid fa-mobile-screen text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Recordings Card -->
        <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 relative overflow-hidden shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Recordings Created</span>
                    <h3 class="text-2xl font-black text-white mt-1">{{ $stats['total_recordings'] }}</h3>
                    <span class="text-[11px] text-slate-400 font-medium mt-1 inline-block">Processed Audio DSP</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-950 border border-blue-500/30 text-blue-400 flex items-center justify-center">
                    <i class="fa-solid fa-file-audio text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Management Action Shortcuts -->
    <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-white text-sm">Quick Actions</h3>
            <p class="text-xs text-slate-400">Common administrative operations</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.voices.create') }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-purple-600/30 transition">
                <i class="fa-solid fa-plus"></i> Add New Voice
            </a>
            <a href="{{ route('admin.apps.create') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 border border-dark-600 text-slate-200 font-bold text-xs flex items-center gap-1.5 transition">
                <i class="fa-solid fa-mobile-retro"></i> Add Target App
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-dark-800 hover:bg-dark-700 border border-dark-600 text-amber-300 font-bold text-xs flex items-center gap-1.5 transition">
                <i class="fa-solid fa-gem"></i> Manage VIP & Credits
            </a>
        </div>
    </div>

    <!-- 2 Column Layout: Recent Audio Recordings & Popular AI Voices -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Recent Audio Recordings (2 cols) -->
        <div class="lg:col-span-2 p-5 rounded-2xl bg-dark-900 border border-dark-700/70 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-white text-sm">Recent Transformed Recordings</h3>
                    <p class="text-xs text-slate-400">Audio uploaded or processed by users</p>
                </div>
                <a href="{{ route('admin.recordings.index') }}" class="text-xs text-purple-400 hover:underline">View All</a>
            </div>

            <div class="space-y-2.5">
                @forelse($recentRecordings as $rec)
                    <div class="p-3 rounded-xl bg-dark-800/60 border border-dark-700/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-950 text-purple-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-music"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-white">{{ $rec->title }}</h4>
                                <span class="text-[10px] text-slate-400">User: {{ $rec->user ? $rec->user->name : 'Guest' }} • Voice: {{ $rec->voice ? $rec->voice->name : 'Custom' }} • {{ $rec->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Audio playback -->
                        <div class="flex items-center gap-2">
                            <audio src="{{ asset($rec->processed_file) }}" controls class="h-8 max-w-[180px] rounded"></audio>
                            <form method="POST" action="{{ route('admin.recordings.destroy', $rec) }}" onsubmit="return confirm('Delete recording?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-500 border border-dashed border-dark-700 rounded-xl">
                        No audio recordings in database yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Popular AI Voices & Recent Users (1 col) -->
        <div class="space-y-6">
            <!-- AI Voices -->
            <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 space-y-3">
                <h3 class="font-bold text-white text-sm">Top Voice Models</h3>
                <div class="space-y-2">
                    @foreach($topVoices as $v)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-dark-800/50">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset($v->avatar) }}" class="w-8 h-8 rounded-lg object-cover">
                                <div>
                                    <h4 class="text-xs font-bold text-white">{{ $v->name }}</h4>
                                    <span class="text-[10px] text-slate-400">{{ ucfirst($v->category) }} • {{ $v->is_vip ? 'VIP' : 'Free' }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-purple-400">{{ $v->recordings_count }} uses</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Users -->
            <div class="p-5 rounded-2xl bg-dark-900 border border-dark-700/70 space-y-3">
                <h3 class="font-bold text-white text-sm">Latest Users</h3>
                <div class="space-y-2">
                    @foreach($recentUsers as $u)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-dark-800/50 text-xs">
                            <div>
                                <h4 class="font-bold text-white">{{ $u->name }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $u->email }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-amber-400 font-bold">{{ $u->credits }} cr</span>
                                @if($u->hasVip())
                                    <span class="block text-[9px] text-emerald-400 font-bold">VIP ACTIVE</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
