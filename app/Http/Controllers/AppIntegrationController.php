<?php

namespace App\Http\Controllers;

use App\Models\TargetApp;
use App\Models\UserAppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppIntegrationController extends Controller
{
    public function index()
    {
        $apps = TargetApp::active()->get();
        return response()->json([
            'success' => true,
            'apps' => $apps
        ]);
    }

    public function toggle(Request $request, TargetApp $targetApp)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Please login'], 401);
        }

        $setting = UserAppSetting::firstOrNew([
            'user_id' => $user->id,
            'target_app_id' => $targetApp->id,
        ]);

        $setting->is_enabled = !$setting->is_enabled;
        $setting->save();

        return response()->json([
            'success' => true,
            'is_enabled' => $setting->is_enabled,
            'message' => $setting->is_enabled
                ? "Voice Changer activated for {$targetApp->name}!"
                : "Voice Changer paused for {$targetApp->name}."
        ]);
    }
}
