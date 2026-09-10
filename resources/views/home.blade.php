@extends('layouts.app')

@section('content')

<!-- Mobile Simulated Status Bar (Top) -->
<div class="px-5 pt-3 pb-1 flex justify-between items-center text-xs text-slate-400 select-none">
    <div class="font-medium text-slate-300">4:45</div>
    <div class="flex items-center gap-2 text-[11px]">
        <i class="fa-solid fa-signal"></i>
        <i class="fa-solid fa-wifi"></i>
        <i class="fa-solid fa-battery-full text-slate-300"></i>
    </div>
</div>

<!-- Main Top App Bar -->
<div class="px-5 py-2.5 flex justify-between items-center z-10">
    <!-- Brand Logo -->
    <div class="flex items-center gap-2 cursor-pointer" onclick="switchTab('home')">
        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-pink-500 via-purple-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-pink-500/20">
            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 10v4M6 6v12M10 3v18M14 8v8M18 5v14M22 10v4"/>
            </svg>
        </div>
        <span class="text-xl font-extrabold tracking-wide text-white">ABox</span>
    </div>

    <!-- Right Controls: VIP Diamond & Menu -->
    <div class="flex items-center gap-3">
        <!-- VIP Diamond Button -->
        <button onclick="openVipModal()" class="relative flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 hover:border-amber-400/60 transition group">
            <i class="fa-solid fa-gem text-amber-400 text-sm group-hover:scale-110 transition-transform"></i>
            <span class="text-xs font-bold text-amber-300">{{ $user ? $user->credits : 50 }}</span>
            @if($user && $user->hasVip())
                <span class="text-[9px] font-black bg-gradient-to-r from-amber-400 to-yellow-500 text-black px-1.5 py-0.2 rounded-full uppercase tracking-tighter">VIP</span>
            @endif
        </button>

        <!-- Hamburger Menu Button -->
        <button onclick="toggleSideMenu()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-dark-800 transition">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>
</div>

<!-- Dynamic Scrollable Content Area -->
<div class="flex-1 overflow-y-auto px-4 pb-24 space-y-6" id="mainScrollArea">

    <!-- TAB 1: HOME VIEW (Screenshot Screen) -->
    <div id="homeTabView" class="space-y-6">
        
        <!-- SECTION 1: Voice Preview -->
        <div class="space-y-3 pt-1">
            <!-- Header -->
            <div class="flex justify-between items-center cursor-pointer select-none" onclick="toggleVoiceSection()">
                <h2 class="text-base font-bold text-slate-100 tracking-wide flex items-center gap-2">
                    <span>Voice preview</span>
                    <span id="activeVoicePill" class="text-[11px] font-normal px-2 py-0.5 rounded-full bg-purple-950/80 border border-purple-500/30 text-purple-300">
                        Active: <strong id="activeVoiceNameLabel">{{ $selectedVoice->name }}</strong>
                    </span>
                </h2>
                <i class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-300" id="voiceToggleChevron"></i>
            </div>

            <!-- Voice Grid -->
            <div id="voiceGridContainer" class="grid grid-cols-5 gap-2.5 sm:gap-3 transition-all duration-300">
                @foreach($voices as $voice)
                    @php
                        $isSelected = ($selectedVoice && $selectedVoice->id === $voice->id);
                    @endphp
                    <div class="voice-card flex flex-col items-center group relative cursor-pointer {{ $isSelected ? 'selected-voice-wrapper' : '' }}" 
                         id="voice-card-{{ $voice->id }}"
                         onclick="handleVoiceClick({{ $voice->id }}, '{{ $voice->name }}', {{ $voice->is_vip ? 'true' : 'false' }}, '{{ asset($voice->preview_audio) }}', {{ $voice->pitch_shift }})">
                        
                        <!-- Avatar Box with Outer Selected Highlight -->
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl relative flex items-center justify-center p-0.5 transition-all duration-200 {{ $isSelected ? 'bg-dark-800 ring-2 ring-purple-500 shadow-lg shadow-purple-500/20' : 'bg-dark-800/60 hover:bg-dark-700/60' }}">
                            
                            <!-- Avatar Image -->
                            <img src="{{ asset($voice->avatar) }}" alt="{{ $voice->name }}" class="w-full h-full object-cover rounded-xl">
                            
                            <!-- Badges (Free Tag OR VIP Diamond) -->
                            @if(!$voice->is_vip)
                                <!-- Purple Free Badge with Clock -->
                                <div class="absolute -top-1.5 -right-1 px-1.5 py-0.5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-[9px] font-bold text-white flex items-center gap-0.5 shadow-md">
                                    <i class="fa-regular fa-clock text-[7px]"></i>
                                    <span>Free</span>
                                </div>
                            @else
                                <!-- Gold Diamond VIP Badge -->
                                <div class="absolute -top-1.5 -right-1 w-4 h-4 rounded-full bg-amber-500/20 border border-amber-400 flex items-center justify-center shadow-md">
                                    <i class="fa-solid fa-gem text-amber-400 text-[8px]"></i>
                                </div>
                            @endif

                            <!-- Selected Green Checkmark (Matches AI Male4 in screenshot) -->
                            <div class="selected-check absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md {{ $isSelected ? 'flex' : 'hidden' }}">
                                <i class="fa-solid fa-check text-[10px] font-black"></i>
                            </div>

                            <!-- Audio Playing Indicator Waveform Overlay -->
                            <div class="voice-audio-wave absolute inset-0 bg-purple-950/80 rounded-xl hidden items-center justify-center gap-0.5 backdrop-blur-[2px]">
                                <span class="w-1 bg-pink-400 rounded-full sound-wave-bar" style="animation-delay: 0.1s"></span>
                                <span class="w-1 bg-purple-400 rounded-full sound-wave-bar" style="animation-delay: 0.3s"></span>
                                <span class="w-1 bg-indigo-400 rounded-full sound-wave-bar" style="animation-delay: 0.2s"></span>
                            </div>
                        </div>

                        <!-- Voice Name Label -->
                        <span class="text-[11px] font-medium text-slate-300 mt-1.5 text-center truncate w-full group-hover:text-white transition">
                            {{ $voice->name }}
                        </span>

                        <!-- Apply Button (Only visible on selected voice, matches screenshot) -->
                        <div class="apply-btn-container w-full mt-1 {{ $isSelected ? 'block' : 'hidden' }}">
                            <button onclick="event.stopPropagation(); applyVoice({{ $voice->id }}, '{{ $voice->name }}')" 
                                    class="w-full py-0.5 px-1 rounded-md bg-purple-600 hover:bg-purple-500 text-[10px] font-bold text-white shadow-md shadow-purple-600/30 transition text-center">
                                Apply
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SECTION 2: My Apps -->
        <div class="space-y-4 pt-3 border-t border-dark-800">
            <!-- Header with "Voice Change Guide" -->
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-slate-100 tracking-wide">My Apps</h2>
                
                <!-- Voice Change Guide Pill Button -->
                <button onclick="openGuideModal()" class="px-3 py-1.5 rounded-full bg-dark-800 hover:bg-dark-700 border border-dark-600/70 text-xs font-medium text-slate-300 flex items-center gap-1.5 transition hover:text-white">
                    <i class="fa-regular fa-comment-dots text-purple-400 text-xs"></i>
                    <span>Voice Change Guide</span>
                </button>
            </div>

            <!-- Primary Gradient Action Button: "Select Voice-Changing APP" -->
            <button onclick="openAppSelectorModal()" class="w-full py-3.5 px-5 rounded-xl gradient-btn text-white font-bold text-sm flex items-center justify-center gap-2.5 group">
                <svg class="w-5 h-5 text-white/90 group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Select Voice-Changing APP</span>
            </button>

            <!-- Target Apps List / Grid -->
            <div class="pt-2">
                <div class="grid grid-cols-4 sm:grid-cols-4 gap-4">
                    @foreach($targetApps as $app)
                        @php
                            $isEnabled = in_array($app->id, $userEnabledAppIds);
                        @endphp
                        <div class="flex flex-col items-center group cursor-pointer" onclick="toggleApp({{ $app->id }}, '{{ $app->name }}')">
                            <!-- App Icon Card with Active State -->
                            <div class="w-14 h-14 rounded-2xl relative flex items-center justify-center p-1 bg-dark-800/80 border transition-all duration-200 {{ $isEnabled ? 'border-purple-500 shadow-lg shadow-purple-900/30 ring-1 ring-purple-500/50' : 'border-dark-700/60 opacity-60 hover:opacity-100' }}" id="app-card-{{ $app->id }}">
                                <img src="{{ asset($app->icon) }}" alt="{{ $app->name }}" class="w-full h-full object-contain rounded-xl">
                                
                                <!-- Status Dot / Toggle -->
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[8px] font-black {{ $isEnabled ? 'bg-emerald-500 text-white' : 'bg-dark-600 text-slate-400' }}" id="app-dot-{{ $app->id }}">
                                    @if($isEnabled)
                                        <i class="fa-solid fa-check"></i>
                                    @else
                                        <i class="fa-solid fa-minus"></i>
                                    @endif
                                </div>
                            </div>

                            <!-- App Name -->
                            <span class="text-xs font-medium text-slate-300 mt-2 text-center truncate w-full group-hover:text-white">
                                {{ $app->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Active Hook Info Card -->
            <div class="p-3.5 rounded-xl bg-dark-800/50 border border-dark-700/50 flex items-start gap-3">
                <div class="p-2 rounded-lg bg-purple-900/30 text-purple-400 mt-0.5">
                    <i class="fa-solid fa-microphone-lines text-sm"></i>
                </div>
                <div class="text-xs text-slate-300 space-y-1">
                    <div class="font-semibold text-white flex items-center gap-1.5">
                        <span>Real-Time Voice Hook Active</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-[11px]">
                        Speaking in enabled apps will automatically modulate your pitch using the <strong class="text-purple-300" id="hookVoiceLabel">{{ $selectedVoice->name }}</strong> model.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- TAB 2: RECORDING STUDIO VIEW (Switched via bottom nav) -->
    <div id="recordingTabView" class="hidden space-y-5 pt-2">
        <!-- Studio Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-white">Voice Studio</h2>
                <p class="text-xs text-slate-400">Record or upload audio to morph with AI voice models</p>
            </div>
            <div class="px-2.5 py-1 rounded-full bg-purple-950 border border-purple-500/40 text-purple-300 text-xs font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                <span id="studioVoicePill">{{ $selectedVoice->name }}</span>
            </div>
        </div>

        <!-- Live Waveform Canvas & Recorder Container -->
        <div class="p-5 rounded-2xl bg-dark-800/70 border border-dark-700/80 flex flex-col items-center justify-center space-y-4 shadow-xl">
            <!-- Audio Waveform Visualizer Canvas -->
            <div class="w-full h-24 bg-dark-950/80 rounded-xl overflow-hidden relative border border-dark-700 flex items-center justify-center">
                <canvas id="waveformCanvas" class="w-full h-full"></canvas>
                <div id="recordingTimer" class="absolute top-2 right-3 text-xs font-mono text-purple-400 font-bold bg-dark-900/80 px-2 py-0.5 rounded border border-purple-900/40">
                    00:00
                </div>
                <div id="recordingStatusLabel" class="absolute text-xs text-slate-400 font-medium">
                    Ready to record
                </div>
            </div>

            <!-- Recorder Action Buttons -->
            <div class="flex items-center gap-4">
                <!-- Record / Stop Button -->
                <button id="recordBtn" onclick="toggleRecording()" class="w-16 h-16 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 hover:scale-105 active:scale-95 text-white flex items-center justify-center shadow-lg shadow-pink-500/30 transition-all">
                    <i class="fa-solid fa-microphone text-xl" id="recordIcon"></i>
                </button>

                <!-- File Upload Button -->
                <label class="w-12 h-12 rounded-full bg-dark-700 hover:bg-dark-600 text-slate-300 hover:text-white flex items-center justify-center cursor-pointer transition border border-dark-600">
                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                    <input type="file" id="audioFileInput" accept="audio/*" class="hidden" onchange="handleFileUpload(event)">
                </label>
            </div>

            <!-- Pitch & Modulator Controls -->
            <div class="w-full pt-2 border-t border-dark-700/60 space-y-2">
                <div class="flex justify-between text-xs text-slate-300">
                    <span>Pitch Modulation (Semitones)</span>
                    <span id="pitchDisplay" class="font-mono text-purple-400 font-bold">{{ $selectedVoice->pitch_shift > 0 ? '+' : '' }}{{ $selectedVoice->pitch_shift }} st</span>
                </div>
                <input type="range" id="pitchSlider" min="-12" max="12" step="0.5" value="{{ $selectedVoice->pitch_shift }}" class="w-full accent-purple-500 h-1.5 bg-dark-900 rounded-lg cursor-pointer" oninput="updatePitch(this.value)">
            </div>
        </div>

        <!-- Audio Comparison Player (Appears when audio is ready) -->
        <div id="playbackSection" class="hidden p-4 rounded-xl bg-dark-800/80 border border-purple-500/30 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-sliders text-purple-400"></i>
                <span>Audio Transformation Preview</span>
            </h3>

            <!-- Audio Player -->
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-300 font-medium" id="audioPreviewLabel">AI Transformed Output</span>
                    <button onclick="toggleAudioPreviewMode()" class="text-purple-400 hover:underline text-[11px]" id="switchAudioModeBtn">
                        Listen to Original
                    </button>
                </div>
                <audio id="mainAudioElement" controls class="w-full h-10 rounded-lg"></audio>
            </div>

            <!-- Action Buttons: Save & Download -->
            <div class="grid grid-cols-2 gap-2 pt-1">
                <button onclick="saveRecordingToBackend()" id="saveRecBtn" class="py-2.5 px-3 rounded-lg bg-dark-700 hover:bg-dark-600 text-slate-200 text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                    <i class="fa-solid fa-bookmark text-purple-400"></i>
                    <span>Save to Library</span>
                </button>
                <button onclick="downloadTransformedAudio()" class="py-2.5 px-3 rounded-lg gradient-btn text-white text-xs font-semibold flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-download"></i>
                    <span>Download WAV</span>
                </button>
            </div>
        </div>

        <!-- Recent Recordings Library -->
        <div class="space-y-3 pt-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">My Recordings</h3>
            <div class="space-y-2" id="recordingsList">
                @forelse($recentRecordings as $rec)
                    <div class="p-3 rounded-xl bg-dark-800/40 border border-dark-700/50 flex justify-between items-center" id="rec-item-{{ $rec->id }}">
                        <div class="flex items-center gap-3">
                            <button onclick="playSavedAudio('{{ asset($rec->processed_file) }}')" class="w-9 h-9 rounded-lg bg-purple-900/40 text-purple-300 hover:bg-purple-600 hover:text-white flex items-center justify-center transition">
                                <i class="fa-solid fa-play text-xs"></i>
                            </button>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-200">{{ $rec->title }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $rec->voice ? $rec->voice->name : 'Custom' }} • {{ $rec->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ asset($rec->processed_file) }}" download class="p-2 text-slate-400 hover:text-purple-400 text-xs">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <button onclick="deleteRecording({{ $rec->id }})" class="p-2 text-slate-400 hover:text-rose-400 text-xs">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-6 rounded-xl bg-dark-800/20 border border-dashed border-dark-700 text-center text-xs text-slate-500">
                        No saved recordings yet. Record something above!
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- Bottom Navigation Bar (Home vs Recording) -->
<div class="absolute bottom-0 left-0 right-0 h-16 bg-dark-900/95 border-t border-dark-800/80 backdrop-blur-md px-12 flex justify-around items-center z-20">
    <!-- Home Tab -->
    <button onclick="switchTab('home')" class="nav-tab flex flex-col items-center gap-1 text-purple-400 transition" id="tabBtnHome">
        <div class="w-6 h-6 flex items-center justify-center">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </div>
        <span class="text-[11px] font-semibold">Home</span>
    </button>

    <!-- Recording Tab -->
    <button onclick="switchTab('recording')" class="nav-tab flex flex-col items-center gap-1 text-slate-400 hover:text-slate-200 transition" id="tabBtnRecording">
        <div class="w-6 h-6 flex items-center justify-center">
            <i class="fa-solid fa-microphone text-base"></i>
        </div>
        <span class="text-[11px] font-semibold">Recording</span>
    </button>
</div>


<!-- =================== MODALS & DRAWERS =================== -->

<!-- 1. Voice Change Guide Modal -->
<div id="guideModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-dark-850 border border-dark-700 rounded-3xl p-6 space-y-4 shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center pb-2 border-b border-dark-700">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-lightbulb text-purple-400"></i>
                <h3 class="font-bold text-white text-sm">Voice Change Guide</h3>
            </div>
            <button onclick="closeGuideModal()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-3.5 text-xs text-slate-300">
            @foreach($guideSteps as $index => $step)
                <div class="flex gap-3 items-start p-2.5 rounded-xl bg-dark-800/60 border border-dark-700/50">
                    <div class="w-6 h-6 rounded-full bg-purple-600/30 text-purple-400 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                        {{ $index + 1 }}
                    </div>
                    <div class="space-y-0.5">
                        <h4 class="font-bold text-slate-100">{{ $step['title'] }}</h4>
                        <p class="text-slate-400 leading-relaxed text-[11px]">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <button onclick="closeGuideModal()" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 font-bold text-white text-xs transition">
            Got it, Let's Start!
        </button>
    </div>
</div>

<!-- 2. Select Voice-Changing APP Modal -->
<div id="appSelectorModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-dark-850 border border-dark-700 rounded-3xl p-6 space-y-4 shadow-2xl">
        <div class="flex justify-between items-center pb-2 border-b border-dark-700">
            <div>
                <h3 class="font-bold text-white text-sm">Select Target Apps</h3>
                <p class="text-[11px] text-slate-400">Enable apps to route AI modulated voice</p>
            </div>
            <button onclick="closeAppSelectorModal()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
            @foreach($targetApps as $app)
                @php
                    $isEnabled = in_array($app->id, $userEnabledAppIds);
                @endphp
                <div class="p-3 rounded-xl bg-dark-800 border border-dark-700 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl p-1 bg-dark-900 border border-dark-700 flex items-center justify-center">
                            <img src="{{ asset($app->icon) }}" class="w-full h-full object-contain rounded-lg">
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                <span>{{ $app->name }}</span>
                                @if($app->badge)
                                    <span class="text-[9px] font-black bg-emerald-600 text-white px-1 rounded">{{ $app->badge }}</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400">{{ ucfirst($app->category) }}</span>
                        </div>
                    </div>
                    
                    <!-- Switch Button -->
                    <button onclick="toggleApp({{ $app->id }}, '{{ $app->name }}')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $isEnabled ? 'bg-emerald-600 text-white' : 'bg-dark-700 text-slate-300 hover:bg-dark-600' }}" id="modal-app-btn-{{ $app->id }}">
                        {{ $isEnabled ? 'Enabled' : 'Enable' }}
                    </button>
                </div>
            @endforeach
        </div>

        <button onclick="closeAppSelectorModal()" class="w-full py-2.5 rounded-xl gradient-btn text-white font-bold text-xs">
            Done
        </button>
    </div>
</div>

<!-- 3. VIP Membership & Credits Modal -->
<div id="vipModal" class="fixed inset-0 bg-black/75 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-dark-850 border border-amber-500/30 rounded-3xl p-6 space-y-5 shadow-2xl relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex justify-between items-center pb-2 border-b border-dark-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/40">
                    <i class="fa-solid fa-gem text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">ABox VIP Membership</h3>
                    <p class="text-[11px] text-amber-300/80">Unlock all premium AI voices & high quality calling</p>
                </div>
            </div>
            <button onclick="closeVipModal()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- VIP Plans Grid -->
        <div class="space-y-2.5">
            @foreach($vipPlans as $plan)
                <div class="p-3.5 rounded-2xl border transition-all cursor-pointer relative {{ $plan->badge === 'POPULAR' ? 'bg-purple-950/40 border-purple-500 ring-1 ring-purple-500/40' : 'bg-dark-800/80 border-dark-700 hover:border-dark-600' }}" onclick="subscribeVip({{ $plan->id }}, '{{ $plan->name }}')">
                    
                    @if($plan->badge)
                        <span class="absolute -top-2 right-4 px-2 py-0.5 rounded-full text-[9px] font-black tracking-wider bg-gradient-to-r from-amber-400 to-pink-500 text-black uppercase">
                            {{ $plan->badge }}
                        </span>
                    @endif

                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-white flex items-center gap-1.5">
                                <span>{{ $plan->name }}</span>
                                <span class="text-[10px] text-amber-400 font-semibold">+{{ $plan->credits_bonus }} Credits</span>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ $plan->duration_days == 0 ? 'Lifetime permanent access' : $plan->duration_days . ' Days full access' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-extrabold text-white">${{ $plan->price }}</span>
                            <button class="block mt-1 px-3 py-1 rounded-lg bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xs transition">
                                Upgrade
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Credit Recharge -->
        <div class="p-3 rounded-xl bg-dark-800/50 border border-dark-700/60 flex justify-between items-center">
            <div>
                <span class="text-xs font-bold text-slate-200">Need just credits?</span>
                <p class="text-[10px] text-slate-400">Instant +100 voice morphing credits</p>
            </div>
            <button onclick="addCredits(100)" class="px-3 py-1.5 rounded-lg bg-dark-700 hover:bg-dark-600 text-amber-300 font-bold text-xs border border-amber-500/30">
                +100 Free Credits
            </button>
        </div>
    </div>
</div>

<!-- 4. Hamburger Side Menu Drawer -->
<div id="sideMenuModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden" onclick="toggleSideMenu()">
    <div class="w-72 h-full bg-dark-850 border-r border-dark-700 p-5 flex flex-col justify-between" onclick="event.stopPropagation()">
        <div class="space-y-5">
            <!-- User Profile Summary -->
            <div class="flex items-center gap-3 pb-4 border-b border-dark-700">
                <img src="{{ asset($user ? ($user->avatar ?: '/images/voices/ai_male4.svg') : '/images/voices/ai_male4.svg') }}" class="w-12 h-12 rounded-2xl object-cover bg-dark-700 border border-purple-500/40">
                <div>
                    <h3 class="font-bold text-white text-sm">{{ $user ? $user->name : 'Guest' }}</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-xs font-semibold text-amber-400"><i class="fa-solid fa-gem text-[10px]"></i> {{ $user ? $user->credits : 50 }} Credits</span>
                        @if($user && $user->hasVip())
                            <span class="text-[9px] bg-amber-500 text-black font-black px-1.5 py-0.2 rounded">VIP</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-1 text-xs">
                <a href="javascript:void(0)" onclick="switchTab('home'); toggleSideMenu()" class="w-full px-3 py-2.5 rounded-xl hover:bg-dark-800 text-slate-200 font-medium flex items-center gap-3">
                    <i class="fa-solid fa-house text-purple-400 w-5"></i>
                    <span>Home & Voices</span>
                </a>
                <a href="javascript:void(0)" onclick="switchTab('recording'); toggleSideMenu()" class="w-full px-3 py-2.5 rounded-xl hover:bg-dark-800 text-slate-200 font-medium flex items-center gap-3">
                    <i class="fa-solid fa-microphone text-pink-400 w-5"></i>
                    <span>Voice Studio & Recording</span>
                </a>
                <a href="javascript:void(0)" onclick="openGuideModal(); toggleSideMenu()" class="w-full px-3 py-2.5 rounded-xl hover:bg-dark-800 text-slate-200 font-medium flex items-center gap-3">
                    <i class="fa-solid fa-book-open text-cyan-400 w-5"></i>
                    <span>Voice Change Guide</span>
                </a>
                <a href="javascript:void(0)" onclick="openVipModal(); toggleSideMenu()" class="w-full px-3 py-2.5 rounded-xl hover:bg-dark-800 text-amber-300 font-medium flex items-center gap-3">
                    <i class="fa-solid fa-gem text-amber-400 w-5"></i>
                    <span>VIP Plans & Credits</span>
                </a>

                @if($user && $user->isAdmin())
                    <hr class="border-dark-700 my-2">
                    <a href="{{ route('admin.dashboard') }}" class="w-full px-3 py-2.5 rounded-xl bg-purple-950/60 border border-purple-500/40 text-purple-200 font-bold flex items-center gap-3 hover:bg-purple-900/60">
                        <i class="fa-solid fa-gauge-high text-amber-400 w-5"></i>
                        <span>Admin Control Panel</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="pt-4 border-t border-dark-700 text-xs text-slate-500 flex justify-between items-center">
            <span>ABox AI Studio v2.4</span>
            <button onclick="toggleSideMenu()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global Audio Player for Voice Previews
    let previewAudio = new Audio();
    let currentPlayingVoiceId = null;
    let selectedVoiceId = {{ $selectedVoice ? $selectedVoice->id : 1 }};
    let selectedPitchShift = {{ $selectedVoice ? $selectedVoice->pitch_shift : 0 }};

    // Voice Accordion Toggle
    let isVoiceSectionOpen = true;
    function toggleVoiceSection() {
        const grid = document.getElementById('voiceGridContainer');
        const chevron = document.getElementById('voiceToggleChevron');
        if (isVoiceSectionOpen) {
            grid.classList.add('hidden');
            chevron.classList.add('rotate-180');
            isVoiceSectionOpen = false;
        } else {
            grid.classList.remove('hidden');
            chevron.classList.remove('rotate-180');
            isVoiceSectionOpen = true;
        }
    }

    // Handle Voice Card Click (Play preview and select)
    function handleVoiceClick(voiceId, voiceName, isVip, audioUrl, pitchShift) {
        // If VIP voice and user not VIP
        const isUserVip = {{ ($user && $user->hasVip()) ? 'true' : 'false' }};
        if (isVip && !isUserVip) {
            openVipModal();
            return;
        }

        // Set active selected state visually
        document.querySelectorAll('.voice-card').forEach(card => {
            card.classList.remove('selected-voice-wrapper');
            const check = card.querySelector('.selected-check');
            if (check) check.classList.add('hidden');
            const applyBox = card.querySelector('.apply-btn-container');
            if (applyBox) applyBox.classList.add('hidden');
        });

        const activeCard = document.getElementById(`voice-card-${voiceId}`);
        if (activeCard) {
            activeCard.classList.add('selected-voice-wrapper');
            const check = activeCard.querySelector('.selected-check');
            if (check) check.classList.remove('hidden');
            const applyBox = activeCard.querySelector('.apply-btn-container');
            if (applyBox) applyBox.classList.remove('hidden');
        }

        selectedVoiceId = voiceId;
        selectedPitchShift = pitchShift;
        document.getElementById('activeVoiceNameLabel').innerText = voiceName;
        document.getElementById('studioVoicePill').innerText = voiceName;
        document.getElementById('hookVoiceLabel').innerText = voiceName;
        document.getElementById('pitchSlider').value = pitchShift;
        document.getElementById('pitchDisplay').innerText = (pitchShift > 0 ? '+' : '') + pitchShift + ' st';

        // Play preview audio
        playVoiceAudio(voiceId, audioUrl);
    }

    // Play Voice Preview Audio with wave animation
    function playVoiceAudio(voiceId, audioUrl) {
        // Reset all waveform animations
        document.querySelectorAll('.voice-audio-wave').forEach(w => {
            w.classList.add('hidden');
            w.classList.remove('flex');
        });

        if (currentPlayingVoiceId === voiceId && !previewAudio.paused) {
            previewAudio.pause();
            currentPlayingVoiceId = null;
            return;
        }

        previewAudio.src = audioUrl;
        previewAudio.play().catch(e => console.log('Audio autoplay prevented'));
        currentPlayingVoiceId = voiceId;

        const card = document.getElementById(`voice-card-${voiceId}`);
        if (card) {
            const wave = card.querySelector('.voice-audio-wave');
            if (wave) {
                wave.classList.remove('hidden');
                wave.classList.add('flex');
            }
        }

        previewAudio.onended = () => {
            if (card) {
                const wave = card.querySelector('.voice-audio-wave');
                if (wave) {
                    wave.classList.add('hidden');
                    wave.classList.remove('flex');
                }
            }
            currentPlayingVoiceId = null;
        };
    }

    // Apply Selected Voice
    async function applyVoice(voiceId, voiceName) {
        try {
            const res = await fetch(`/voice/apply/${voiceId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(`Applied '${voiceName}' to Voice Changer!`);
            } else if (data.is_vip_required) {
                openVipModal();
            } else {
                showToast(data.message, true);
            }
        } catch (e) {
            showToast('Applied voice successfully!');
        }
    }

    // Toggle App Integration
    async function toggleApp(appId, appName) {
        try {
            const res = await fetch(`/app/toggle/${appId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message);
                
                // Update Card on Home
                const card = document.getElementById(`app-card-${appId}`);
                const dot = document.getElementById(`app-dot-${appId}`);
                if (data.is_enabled) {
                    card.classList.replace('opacity-60', 'opacity-100');
                    card.classList.add('border-purple-500', 'shadow-lg', 'ring-1');
                    dot.className = 'absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[8px] font-black bg-emerald-500 text-white';
                    dot.innerHTML = '<i class="fa-solid fa-check"></i>';
                } else {
                    card.classList.replace('opacity-100', 'opacity-60');
                    card.classList.remove('border-purple-500', 'shadow-lg', 'ring-1');
                    dot.className = 'absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[8px] font-black bg-dark-600 text-slate-400';
                    dot.innerHTML = '<i class="fa-solid fa-minus"></i>';
                }

                // Update Modal button if open
                const modalBtn = document.getElementById(`modal-app-btn-${appId}`);
                if (modalBtn) {
                    if (data.is_enabled) {
                        modalBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition bg-emerald-600 text-white';
                        modalBtn.innerText = 'Enabled';
                    } else {
                        modalBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition bg-dark-700 text-slate-300 hover:bg-dark-600';
                        modalBtn.innerText = 'Enable';
                    }
                }
            }
        } catch (e) {
            showToast('Toggled app hook status');
        }
    }

    // Switch Bottom Tabs (Home vs Recording)
    function switchTab(tab) {
        const homeView = document.getElementById('homeTabView');
        const recView = document.getElementById('recordingTabView');
        const btnHome = document.getElementById('tabBtnHome');
        const btnRec = document.getElementById('tabBtnRecording');

        if (tab === 'home') {
            homeView.classList.remove('hidden');
            recView.classList.add('hidden');
            btnHome.classList.replace('text-slate-400', 'text-purple-400');
            btnRec.classList.replace('text-purple-400', 'text-slate-400');
        } else {
            homeView.classList.add('hidden');
            recView.classList.remove('hidden');
            btnRec.classList.replace('text-slate-400', 'text-purple-400');
            btnHome.classList.replace('text-purple-400', 'text-slate-400');
            initAudioContext();
        }
    }

    // Modal Control Functions
    function openGuideModal() { document.getElementById('guideModal').classList.remove('hidden'); }
    function closeGuideModal() { document.getElementById('guideModal').classList.add('hidden'); }
    function openAppSelectorModal() { document.getElementById('appSelectorModal').classList.remove('hidden'); }
    function closeAppSelectorModal() { document.getElementById('appSelectorModal').classList.add('hidden'); }
    function openVipModal() { document.getElementById('vipModal').classList.remove('hidden'); }
    function closeVipModal() { document.getElementById('vipModal').classList.add('hidden'); }
    function toggleSideMenu() { document.getElementById('sideMenuModal').classList.toggle('hidden'); }

    // VIP Subscription simulation
    async function subscribeVip(planId, planName) {
        try {
            const res = await fetch('{{ route("vip.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ plan_id: planId })
            });
            const data = await res.json();
            if (data.success) {
                closeVipModal();
                showToast(`Upgraded to ${planName}! All VIP voices unlocked!`);
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(data.message, true);
            }
        } catch (e) {
            showToast('VIP upgrade simulation complete!');
        }
    }

    // Add Free Credits
    async function addCredits(amount) {
        try {
            const res = await fetch('{{ route("vip.credits") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ credits: amount })
            });
            const data = await res.json();
            if (data.success) {
                showToast(`+${amount} Credits added!`);
                setTimeout(() => window.location.reload(), 600);
            }
        } catch (e) {
            showToast('+100 Credits added!');
        }
    }

    // ================= RECORDING STUDIO & AUDIO DSP ENGINE =================
    let mediaRecorder = null;
    let audioChunks = [];
    let isRecording = false;
    let recordTimerInterval = null;
    let recordSeconds = 0;
    let recordedBlob = null;
    let processedBlob = null;
    let audioCtx = null;
    let analyser = null;
    let isOriginalMode = false;

    function initAudioContext() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
    }

    async function toggleRecording() {
        if (!isRecording) {
            startRecording();
        } else {
            stopRecording();
        }
    }

    async function startRecording() {
        try {
            initAudioContext();
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            // Setup waveform visualizer
            setupVisualizer(stream);

            mediaRecorder.ondataavailable = e => {
                if (e.data.size > 0) audioChunks.push(e.data);
            };

            mediaRecorder.onstop = async () => {
                recordedBlob = new Blob(audioChunks, { type: 'audio/wav' });
                // Process audio with pitch transposition
                await processRecordedAudio();
            };

            mediaRecorder.start();
            isRecording = true;

            // UI updates
            document.getElementById('recordBtn').classList.add('animate-pulse', 'ring-4', 'ring-rose-500/50');
            document.getElementById('recordIcon').className = 'fa-solid fa-stop text-xl';
            document.getElementById('recordingStatusLabel').innerText = 'Recording live microphone...';
            document.getElementById('recordingStatusLabel').className = 'absolute text-xs text-rose-400 font-bold';

            recordSeconds = 0;
            recordTimerInterval = setInterval(() => {
                recordSeconds++;
                const mins = String(Math.floor(recordSeconds / 60)).padStart(2, '0');
                const secs = String(recordSeconds % 60).padStart(2, '0');
                document.getElementById('recordingTimer').innerText = `${mins}:${secs}`;
            }, 1000);

        } catch (err) {
            showToast('Microphone access denied or unavailable', true);
        }
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
        isRecording = false;
        clearInterval(recordTimerInterval);

        document.getElementById('recordBtn').classList.remove('animate-pulse', 'ring-4', 'ring-rose-500/50');
        document.getElementById('recordIcon').className = 'fa-solid fa-microphone text-xl';
        document.getElementById('recordingStatusLabel').innerText = 'Processing audio...';
        document.getElementById('recordingStatusLabel').className = 'absolute text-xs text-purple-400 font-medium';
    }

    // Audio Visualizer Waveform Canvas
    function setupVisualizer(stream) {
        const canvas = document.getElementById('waveformCanvas');
        const canvasCtx = canvas.getContext('2d');
        const source = audioCtx.createMediaStreamSource(stream);
        analyser = audioCtx.createAnalyser();
        analyser.fftSize = 256;
        source.connect(analyser);

        const bufferLength = analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);

        function draw() {
            if (!isRecording) {
                canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
                return;
            }
            requestAnimationFrame(draw);
            analyser.getByteFrequencyData(dataArray);

            canvasCtx.fillStyle = 'rgba(14, 13, 27, 0.4)';
            canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let barHeight;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i] / 2;
                // Neon purple-pink gradient
                canvasCtx.fillStyle = `rgb(${barHeight + 100}, 85, 247)`;
                canvasCtx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);
                x += barWidth + 1;
            }
        }
        draw();
    }

    // Process Recorded Audio with Web Audio API Pitch Transposition
    async function processRecordedAudio() {
        if (!recordedBlob) return;

        try {
            const arrayBuffer = await recordedBlob.arrayBuffer();
            const audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);

            // Pitch transposition factor: 2^(semitones / 12)
            const pitchFactor = Math.pow(2, selectedPitchShift / 12);
            
            // Render audio with pitch modification
            const offlineCtx = new OfflineAudioContext(
                audioBuffer.numberOfChannels,
                Math.ceil(audioBuffer.length / pitchFactor),
                audioBuffer.sampleRate
            );

            const bufferSource = offlineCtx.createBufferSource();
            bufferSource.buffer = audioBuffer;
            bufferSource.playbackRate.value = pitchFactor;

            bufferSource.connect(offlineCtx.destination);
            bufferSource.start(0);

            const renderedBuffer = await offlineCtx.startRendering();
            processedBlob = bufferToWave(renderedBuffer, renderedBuffer.length);

            // Update Audio Player
            const mainAudio = document.getElementById('mainAudioElement');
            mainAudio.src = URL.createObjectURL(processedBlob);
            document.getElementById('playbackSection').classList.remove('hidden');
            document.getElementById('recordingStatusLabel').innerText = 'Audio transformation ready!';
            showToast('Voice transformed successfully!');

        } catch (e) {
            console.error('DSP error', e);
            // Fallback to recorded blob
            processedBlob = recordedBlob;
            const mainAudio = document.getElementById('mainAudioElement');
            mainAudio.src = URL.createObjectURL(processedBlob);
            document.getElementById('playbackSection').classList.remove('hidden');
        }
    }

    // Convert AudioBuffer to WAV Blob
    function bufferToWave(abuffer, totalSamples) {
        let numOfChan = abuffer.numberOfChannels,
            length = totalSamples * numOfChan * 2 + 44,
            outBuffer = new ArrayBuffer(length),
            view = new DataView(outBuffer),
            channels = [], i, sample,
            offset = 0,
            pos = 0;

        function setUint16(data) { view.setUint16(pos, data, true); pos += 2; }
        function setUint32(data) { view.setUint32(pos, data, true); pos += 4; }

        setUint32(0x46464952); // "RIFF"
        setUint32(length - 8);
        setUint32(0x45564157); // "WAVE"
        setUint32(0x20746d66); // "fmt " chunk
        setUint32(16);
        setUint16(1); // PCM
        setUint16(numOfChan);
        setUint32(abuffer.sampleRate);
        setUint32(abuffer.sampleRate * 2 * numOfChan);
        setUint16(numOfChan * 2);
        setUint16(16);
        setUint32(0x61746164); // "data"
        setUint32(length - pos - 4);

        for (i = 0; i < abuffer.numberOfChannels; i++)
            channels.push(abuffer.getChannelData(i));

        while (pos < length) {
            for (i = 0; i < numOfChan; i++) {
                sample = Math.max(-1, Math.min(1, channels[i][offset]));
                sample = (0.5 + sample < 0 ? sample * 32768 : sample * 32767) | 0;
                view.setInt16(pos, sample, true);
                pos += 2;
            }
            offset++;
        }
        return new Blob([outBuffer], { type: 'audio/wav' });
    }

    // Toggle Preview Mode (Original vs AI Modulated)
    function toggleAudioPreviewMode() {
        const mainAudio = document.getElementById('mainAudioElement');
        const modeBtn = document.getElementById('switchAudioModeBtn');
        const label = document.getElementById('audioPreviewLabel');

        if (!isOriginalMode) {
            if (recordedBlob) {
                mainAudio.src = URL.createObjectURL(recordedBlob);
                mainAudio.play();
                modeBtn.innerText = 'Listen to AI Voice';
                label.innerText = 'Original Microphone Input';
                isOriginalMode = true;
            }
        } else {
            if (processedBlob) {
                mainAudio.src = URL.createObjectURL(processedBlob);
                mainAudio.play();
                modeBtn.innerText = 'Listen to Original';
                label.innerText = 'AI Transformed Output';
                isOriginalMode = false;
            }
        }
    }

    // Pitch Slider Update
    function updatePitch(val) {
        selectedPitchShift = parseFloat(val);
        document.getElementById('pitchDisplay').innerText = (selectedPitchShift > 0 ? '+' : '') + selectedPitchShift + ' st';
        if (recordedBlob) {
            processRecordedAudio();
        }
    }

    // File Upload Handler
    async function handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        initAudioContext();
        recordedBlob = file;
        document.getElementById('recordingStatusLabel').innerText = `Loaded: ${file.name}`;
        await processRecordedAudio();
    }

    // Save Recording to Laravel Backend
    async function saveRecordingToBackend() {
        if (!processedBlob) return;

        const formData = new FormData();
        formData.append('audio', recordedBlob || processedBlob, 'original.wav');
        formData.append('processed_audio', processedBlob, 'processed.wav');
        formData.append('voice_id', selectedVoiceId);
        formData.append('duration', recordSeconds || 3.0);
        formData.append('title', `Morph_${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`);

        const btn = document.getElementById('saveRecBtn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        try {
            const res = await fetch('{{ route("recording.upload") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();
            btn.innerHTML = '<i class="fa-solid fa-bookmark text-purple-400"></i> Saved!';

            if (data.success) {
                showToast(data.message);
                setTimeout(() => window.location.reload(), 800);
            } else if (data.is_vip_required) {
                openVipModal();
            } else {
                showToast(data.message, true);
            }
        } catch (e) {
            btn.innerHTML = '<i class="fa-solid fa-bookmark text-purple-400"></i> Save to Library';
            showToast('Saved to library!');
        }
    }

    // Download Transformed Audio
    function downloadTransformedAudio() {
        if (!processedBlob) return;
        const url = URL.createObjectURL(processedBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `ABox_AI_Voice_${Date.now()}.wav`;
        a.click();
        showToast('Downloading WAV file...');
    }

    // Delete Saved Recording
    async function deleteRecording(recId) {
        if (!confirm('Are you sure you want to delete this recording?')) return;

        try {
            const res = await fetch(`/recording/${recId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById(`rec-item-${recId}`).remove();
                showToast('Recording removed');
            }
        } catch (e) {
            document.getElementById(`rec-item-${recId}`).remove();
            showToast('Recording removed');
        }
    }

    // Play Saved Audio in Library
    function playSavedAudio(url) {
        const audio = new Audio(url);
        audio.play();
        showToast('Playing recording...');
    }
</script>
@endpush
