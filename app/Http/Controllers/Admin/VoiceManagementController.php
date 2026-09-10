<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VoiceManagementController extends Controller
{
    public function index()
    {
        $voices = Voice::orderBy('order')->orderBy('id', 'desc')->paginate(15);
        return view('admin.voices.index', compact('voices'));
    }

    public function create()
    {
        return view('admin.voices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'category' => 'required|string|max:50',
            'pitch_shift' => 'required|numeric|between:-24,24',
            'speed' => 'required|numeric|between:0.5,2.0',
            'reverb' => 'required|numeric|between:0,1.0',
            'order' => 'required|integer',
            'is_vip' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'avatar_file' => 'nullable|image|max:2048',
            'avatar_url' => 'nullable|string',
            'preview_audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $avatarPath = '/images/voices/ai_male4.svg';
        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('public/voices/avatars');
            $avatarPath = Storage::url($path);
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->avatar_url;
        }

        $previewAudioPath = '/audio/previews/ai_male4.wav';
        if ($request->hasFile('preview_audio_file')) {
            $path = $request->file('preview_audio_file')->store('public/voices/audio');
            $previewAudioPath = Storage::url($path);
        }

        Voice::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'category' => $validated['category'],
            'avatar' => $avatarPath,
            'preview_audio' => $previewAudioPath,
            'pitch_shift' => $validated['pitch_shift'],
            'speed' => $validated['speed'],
            'reverb' => $validated['reverb'],
            'order' => $validated['order'],
            'is_vip' => $request->boolean('is_vip'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.voices.index')->with('success', 'AI Voice profile created successfully!');
    }

    public function edit(Voice $voice)
    {
        return view('admin.voices.edit', compact('voice'));
    }

    public function update(Request $request, Voice $voice)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'category' => 'required|string|max:50',
            'pitch_shift' => 'required|numeric|between:-24,24',
            'speed' => 'required|numeric|between:0.5,2.0',
            'reverb' => 'required|numeric|between:0,1.0',
            'order' => 'required|integer',
            'avatar_file' => 'nullable|image|max:2048',
            'avatar_url' => 'nullable|string',
            'preview_audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('public/voices/avatars');
            $voice->avatar = Storage::url($path);
        } elseif ($request->filled('avatar_url')) {
            $voice->avatar = $request->avatar_url;
        }

        if ($request->hasFile('preview_audio_file')) {
            $path = $request->file('preview_audio_file')->store('public/voices/audio');
            $voice->preview_audio = Storage::url($path);
        }

        $voice->name = $validated['name'];
        $voice->gender = $validated['gender'];
        $voice->category = $validated['category'];
        $voice->pitch_shift = $validated['pitch_shift'];
        $voice->speed = $validated['speed'];
        $voice->reverb = $validated['reverb'];
        $voice->order = $validated['order'];
        $voice->is_vip = $request->boolean('is_vip');
        $voice->is_active = $request->boolean('is_active');
        $voice->save();

        return redirect()->route('admin.voices.index')->with('success', 'AI Voice profile updated successfully!');
    }

    public function destroy(Voice $voice)
    {
        $voice->delete();
        return redirect()->route('admin.voices.index')->with('success', 'AI Voice profile removed.');
    }
}
