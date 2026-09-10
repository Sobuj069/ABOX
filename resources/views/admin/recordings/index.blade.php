@extends('admin.layout')

@section('title', 'Audio Recordings Auditor')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-white">Recordings & Transformed Audio</h2>
            <p class="text-xs text-slate-400">Auditor for user audio files, DSP transposition outputs, and file storage</p>
        </div>
    </div>

    <!-- Recordings Table -->
    <div class="bg-dark-900 border border-dark-700/70 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-dark-800 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-dark-700">
                    <tr>
                        <th class="p-4">Title & Voice Model</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Duration & Size</th>
                        <th class="p-4">Playback Transformed Audio</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @forelse($recordings as $rec)
                        <tr class="hover:bg-dark-850/60 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/30 text-purple-400 flex items-center justify-center">
                                        <i class="fa-solid fa-waveform-lines text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $rec->title }}</div>
                                        <span class="text-[10px] text-purple-400">Model: {{ $rec->voice ? $rec->voice->name : 'Direct Modulation' }} ({{ $rec->pitch_shift }} st)</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-medium text-slate-200">{{ $rec->user ? $rec->user->name : 'Guest Visitor' }}</span>
                                <span class="block text-[10px] text-slate-500">{{ $rec->user ? $rec->user->email : 'N/A' }}</span>
                            </td>
                            <td class="p-4 font-mono text-slate-300">
                                <div>{{ number_format($rec->duration, 1) }}s</div>
                                <span class="text-[10px] text-slate-500">{{ number_format($rec->file_size / 1024, 1) }} KB</span>
                            </td>
                            <td class="p-4">
                                <audio src="{{ asset($rec->processed_file) }}" controls class="h-8 max-w-[200px] rounded"></audio>
                            </td>
                            <td class="p-4 text-slate-400 text-[11px]">
                                {{ $rec->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ asset($rec->processed_file) }}" download class="p-2 rounded-lg bg-dark-800 hover:bg-dark-700 text-purple-400 text-xs inline-block">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.recordings.destroy', $rec) }}" class="inline-block" onsubmit="return confirm('Delete this recording permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-dark-800 hover:bg-dark-700 text-rose-400 text-xs">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 text-xs">
                                No audio recordings found in database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-dark-800">
            {{ $recordings->links() }}
        </div>
    </div>

</div>
@endsection
