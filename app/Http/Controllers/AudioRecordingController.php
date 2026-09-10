<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use App\Models\Voice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioRecordingController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|max:20480', // 20MB max
            'voice_id' => 'required|exists:voices,id',
            'title' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $voice = Voice::findOrFail($request->voice_id);

        // Check VIP or Credits
        if ($voice->is_vip && (!$user || !$user->hasVip())) {
            return response()->json([
                'success' => false,
                'is_vip_required' => true,
                'message' => 'This voice requires a VIP Subscription to record.'
            ], 403);
        }

        if ($user && !$user->hasVip()) {
            if ($user->credits < 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient credits. You need at least 5 credits to transform audio.'
                ], 400);
            }
            $user->decrement('credits', 5);
        }

        $file = $request->file('audio');
        $extension = $file->getClientOriginalExtension() ?: 'wav';
        $filename = 'rec_' . time() . '_' . Str::random(8) . '.' . $extension;

        // Store original
        $path = $file->storeAs('public/recordings', $filename);
        $originalUrl = Storage::url($path);

        // In this implementation, the Web Audio API on the frontend performs high-fidelity real-time pitch & formant transposition,
        // and sends both or the processed blob. If separate processed audio is passed, we store it:
        $processedUrl = $originalUrl;
        if ($request->hasFile('processed_audio')) {
            $procFile = $request->file('processed_audio');
            $procFilename = 'proc_' . time() . '_' . Str::random(8) . '.' . ($procFile->getClientOriginalExtension() ?: 'wav');
            $procPath = $procFile->storeAs('public/recordings', $procFilename);
            $processedUrl = Storage::url($procPath);
        }

        $recording = Recording::create([
            'user_id' => $user ? $user->id : null,
            'voice_id' => $voice->id,
            'title' => $request->title ?: ('Recording with ' . $voice->name),
            'original_file' => $originalUrl,
            'processed_file' => $processedUrl,
            'duration' => (float)$request->input('duration', 0.0),
            'file_size' => $file->getSize(),
            'pitch_shift' => $voice->pitch_shift,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recording saved and transformed successfully!',
            'recording' => $recording->load('voice'),
            'remaining_credits' => $user ? $user->credits : 0,
        ]);
    }

    public function destroy(Recording $recording)
    {
        $user = Auth::user();
        if ($user && ($recording->user_id === $user->id || $user->isAdmin())) {
            $recording->delete();
            return response()->json(['success' => true, 'message' => 'Recording deleted']);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}
