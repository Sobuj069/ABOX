<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABox Admin Control Panel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            950: '#07060f',
                            900: '#0e0d1b',
                            850: '#141226',
                            800: '#1b1933',
                            700: '#262447',
                            600: '#383464',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-dark-950 text-slate-100 font-sans min-h-screen flex selection:bg-purple-600 selection:text-white">

    <!-- Sidebar -->
    <aside class="w-64 bg-dark-900 border-r border-dark-700/70 p-5 flex flex-col justify-between hidden md:flex">
        <div class="space-y-6">
            <!-- Brand -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-pink-500 via-purple-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-pink-500/25">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M2 10v4M6 6v12M10 3v18M14 8v8M18 5v14M22 10v4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-white">ABox</span>
                    <span class="text-[10px] font-bold text-purple-400 bg-purple-950 border border-purple-500/30 px-1.5 py-0.5 rounded ml-1.5 uppercase">Admin</span>
                </div>
            </a>

            <!-- Navigation -->
            <nav class="space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-gauge-high w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.voices.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.voices.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-microphone-lines w-5"></i>
                    <span>AI Voices (CRUD)</span>
                </a>

                <a href="{{ route('admin.apps.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.apps.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-mobile-screen-button w-5"></i>
                    <span>Target Apps</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Users & VIP</span>
                </a>

                <a href="{{ route('admin.recordings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.recordings.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-waveform-lines w-5"></i>
                    <span>Recordings Auditor</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-dark-800' }}">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Site Settings</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Return to App -->
        <div class="pt-4 border-t border-dark-700/70 space-y-2">
            <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-dark-800 hover:bg-dark-700 text-slate-300 hover:text-white text-xs font-semibold transition border border-dark-600">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to ABox App</span>
            </a>
            <div class="text-[11px] text-slate-500 text-center">
                Logged in as <strong>{{ auth()->user()->name }}</strong>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        <!-- Top Admin Header -->
        <header class="h-16 bg-dark-900 border-b border-dark-700/70 px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="md:hidden w-8 h-8 rounded-lg bg-dark-800 flex items-center justify-center text-purple-400">
                    <i class="fa-solid fa-mobile-screen"></i>
                </a>
                <h1 class="text-base font-bold text-white">@yield('title', 'Admin Panel')</h1>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-eye"></i>
                    <span>Live Frontend</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="p-6 pb-0">
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Dynamic Body -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
