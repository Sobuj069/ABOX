<?php

namespace App\Http\Controllers;

use App\Models\Voice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoiceController extends Controller
{
    public function index()
    {
        $voices = Voice::active()->get();
        return response()->json([
            'success' => true,
            'voices' => $voices
        ]);
    }

    public function show(Voice $voice)
    {
        return response()->json([
            'success' => true,
            'voice' => $voice
        ]);
    }

    public function apply(Request $request, Voice $voice)
    {
        $user = Auth::user();

        // VIP Check: if voice is VIP and user does not have VIP
        if ($voice->is_vip && (!$user || !$user->hasVip())) {
            return response()->json([
                'success' => false,
                'is_vip_required' => true,
                'message' => 'This AI voice requires a VIP Subscription. Please upgrade to unlock.'
            ], 403);
        }

        // Store selected voice in session
        session(['selected_voice_id' => $voice->id]);

        return response()->json([
            'success' => true,
            'message' => "Voice '{$voice->name}' applied successfully!",
            'voice' => $voice
        ]);
    }
}
