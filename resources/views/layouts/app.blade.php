<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABox - Real-time AI Voice Changer</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
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
                            850: '#131224',
                            800: '#1b1933',
                            700: '#262447',
                            600: '#383464',
                        },
                        abox: {
                            purple: '#9333ea',
                            pink: '#ec4899',
                            cyan: '#06b6d4',
                            gold: '#f59e0b',
                            accent: '#a855f7',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0e0d1b;
        }
        ::-webkit-scrollbar-thumb {
            background: #262447;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9333ea;
        }

        /* Glassmorphism */
        .glass-card {
            background: rgba(27, 25, 51, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-btn {
            background: linear-gradient(90deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.35);
            transition: all 0.25s ease;
        }
        .gradient-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(236, 72, 153, 0.45);
        }

        .gradient-border-active {
            border: 2px solid #a855f7;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.5);
        }

        /* Wave animation */
        @keyframes soundWave {
            0%, 100% { height: 4px; }
            50% { height: 16px; }
        }
        .sound-wave-bar {
            animation: soundWave 0.6s ease-in-out infinite alternate;
        }
    </style>
</head>
<body class="bg-dark-950 text-slate-100 font-sans min-h-screen flex flex-col selection:bg-purple-600 selection:text-white">

    <!-- Top floating quick switch / controls bar (visible on large screen) -->
    <div class="bg-dark-900 border-b border-dark-700 py-2 px-4 text-xs flex justify-between items-center text-slate-400 z-50">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1 text-purple-400 font-semibold">
                <i class="fa-solid fa-bolt text-xs"></i> ABox AI Engine v2.4
            </span>
            <span class="hidden sm:inline-block text-slate-500">|</span>
            <span class="hidden sm:inline text-slate-400">Laravel 11 Backend & Web Audio DSP</span>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- View Mode Switcher -->
            <button id="viewModeToggle" onclick="toggleViewMode()" class="px-2.5 py-1 rounded bg-dark-800 hover:bg-dark-700 text-slate-300 flex items-center gap-1.5 transition">
                <i class="fa-solid fa-mobile-screen-button text-purple-400" id="viewModeIcon"></i>
                <span id="viewModeText">Phone View</span>
            </button>

            <!-- Quick Account Switcher for Review -->
            <div class="relative group">
                <button class="px-2.5 py-1 rounded bg-purple-900/40 border border-purple-500/30 text-purple-200 flex items-center gap-1.5">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>{{ Auth::check() ? Auth::user()->name : 'Guest' }} ({{ Auth::check() ? ucfirst(Auth::user()->role) : 'None' }})</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <div class="absolute right-0 mt-1 w-48 bg-dark-800 border border-dark-600 rounded-lg shadow-2xl p-2 hidden group-hover:block z-50">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1 px-2 font-bold">Switch Account</p>
                    <button onclick="switchRole('user')" class="w-full text-left px-2 py-1.5 rounded text-xs hover:bg-dark-700 flex items-center justify-between {{ Auth::check() && Auth::user()->role === 'user' ? 'text-purple-400 font-bold' : 'text-slate-300' }}">
                        <span>Demo User</span>
                        @if(Auth::check() && Auth::user()->role === 'user')<i class="fa-solid fa-check text-xs"></i>@endif
                    </button>
                    <button onclick="switchRole('admin')" class="w-full text-left px-2 py-1.5 rounded text-xs hover:bg-dark-700 flex items-center justify-between {{ Auth::check() && Auth::user()->role === 'admin' ? 'text-purple-400 font-bold' : 'text-slate-300' }}">
                        <span>Admin Account</span>
                        @if(Auth::check() && Auth::user()->role === 'admin')<i class="fa-solid fa-check text-xs"></i>@endif
                    </button>
                    <hr class="border-dark-700 my-1">
                    @if(Auth::check() && Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="w-full text-left px-2 py-1.5 rounded text-xs text-amber-400 hover:bg-dark-700 flex items-center gap-2">
                            <i class="fa-solid fa-gauge"></i> Admin Dashboard
                        </a>
                    @endif
                    @if(Auth::check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-2 py-1.5 rounded text-xs text-rose-400 hover:bg-dark-700 flex items-center gap-2">
                                <i class="fa-solid fa-right-from-bracket"></i> Log Out
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container: Supports Phone Simulator or Full Screen -->
    <div class="flex-1 flex justify-center items-center p-0 sm:p-4 md:p-6 bg-radial from-dark-900 to-dark-950">
        
        <!-- App Frame Wrapper -->
        <div id="appContainer" class="w-full max-w-[430px] min-h-[900px] h-[92vh] max-h-[960px] bg-dark-900 border border-dark-700/60 rounded-none sm:rounded-[42px] shadow-2xl shadow-purple-950/40 flex flex-col relative overflow-hidden transition-all duration-300">
            
            @yield('content')

        </div>

    </div>

    <!-- Global Toast Notification Container -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 px-4 py-2.5 rounded-full bg-dark-800/90 border border-purple-500/40 text-purple-100 text-sm shadow-2xl backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300 z-50 flex items-center gap-2.5">
        <i class="fa-solid fa-circle-check text-purple-400" id="toastIcon"></i>
        <span id="toastMessage">Action completed</span>
    </div>

    <!-- Common JavaScript Functions -->
    <script>
        // Setup CSRF header
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show Toast
        function showToast(msg, isError = false) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toastMsg.innerText = msg;
            if (isError) {
                toastIcon.className = 'fa-solid fa-circle-exclamation text-rose-400';
                toast.classList.replace('border-purple-500/40', 'border-rose-500/50');
            } else {
                toastIcon.className = 'fa-solid fa-circle-check text-purple-400';
                toast.classList.replace('border-rose-500/50', 'border-purple-500/40');
            }

            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
                toast.classList.remove('opacity-100');
            }, 3000);
        }

        // Switch View Mode (Phone Frame vs Wide Web)
        let isPhoneMode = true;
        function toggleViewMode() {
            const container = document.getElementById('appContainer');
            const icon = document.getElementById('viewModeIcon');
            const text = document.getElementById('viewModeText');

            if (isPhoneMode) {
                // Switch to Full Web App Mode
                container.classList.remove('max-w-[430px]', 'h-[92vh]', 'max-h-[960px]', 'rounded-none', 'sm:rounded-[42px]');
                container.classList.add('max-w-4xl', 'min-h-[85vh]', 'rounded-2xl');
                icon.className = 'fa-solid fa-desktop text-purple-400';
                text.innerText = 'Desktop View';
                isPhoneMode = false;
            } else {
                // Switch back to Phone Frame Mode
                container.classList.remove('max-w-4xl', 'min-h-[85vh]', 'rounded-2xl');
                container.classList.add('max-w-[430px]', 'h-[92vh]', 'max-h-[960px]', 'rounded-none', 'sm:rounded-[42px]');
                icon.className = 'fa-solid fa-mobile-screen-button text-purple-400';
                text.innerText = 'Phone View';
                isPhoneMode = true;
            }
        }

        // Quick Role Switcher
        async function switchRole(role) {
            try {
                const res = await fetch('{{ route("auth.switch") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ role: role })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message);
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    showToast(data.message, true);
                }
            } catch (err) {
                showToast('Failed to switch role', true);
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
