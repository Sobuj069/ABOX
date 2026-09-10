@extends('admin.layout')

@section('title', 'Manage Supported Target Apps')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-white">Target Apps Integration</h2>
            <p class="text-xs text-slate-400">Manage communication and gaming apps hooked into ABox Voice Engine</p>
        </div>
        <a href="{{ route('admin.apps.create') }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-purple-600/30 transition self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Add New App
        </a>
    </div>

    <div class="bg-dark-900 border border-dark-700/70 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-dark-800 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-dark-700">
                    <tr>
                        <th class="p-4">App Icon & Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Badge</th>
                        <th class="p-4">Package Identifier</th>
                        <th class="p-4">Active</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @foreach($apps as $app)
                        <tr class="hover:bg-dark-850/60 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl p-1 bg-dark-800 border border-dark-700 flex items-center justify-center">
                                        <img src="{{ asset($app->icon) }}" class="w-full h-full object-contain rounded-lg">
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $app->name }}</div>
                                        <span class="text-[10px] text-slate-400">Order: #{{ $app->order }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 capitalize font-semibold text-slate-200">
                                {{ $app->category }}
                            </td>
                            <td class="p-4">
                                @if($app->badge)
                                    <span class="px-2 py-0.5 rounded bg-emerald-950 border border-emerald-500/40 text-emerald-400 font-black text-[10px]">
                                        {{ $app->badge }}
                                    </span>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-slate-400 text-[11px]">
                                {{ $app->package_name ?: 'system.default' }}
                            </td>
                            <td class="p-4">
                                @if($app->is_active)
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.apps.edit', $app) }}" class="p-2 rounded-lg bg-dark-800 hover:bg-dark-700 text-purple-400 hover:text-purple-300 text-xs inline-block">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.apps.destroy', $app) }}" class="inline-block" onsubmit="return confirm('Delete this app?')">
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
            {{ $apps->links() }}
        </div>
    </div>

</div>
@endsection
