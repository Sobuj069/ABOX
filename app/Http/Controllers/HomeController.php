<?php

namespace App\Http\Controllers;

use App\Models\Voice;
use App\Models\TargetApp;
use App\Models\VipPlan;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Auto-login as demo user if not authenticated for seamless preview
        if (!Auth::check()) {
            $demoUser = User::where('email', 'user@abox.com')->first();
            if ($demoUser) {
                Auth::login($demoUser);
            }
        }

        $user = Auth::user();

        // Get active voices sorted by order
        $voices = Voice::active()->get();

        // Get target apps
        $targetApps = TargetApp::active()->get();

        // Get user's enabled apps
        $userEnabledAppIds = [];
        if ($user) {
            $userEnabledAppIds = $user->appSettings()
                ->where('is_enabled', true)
                ->pluck('target_app_id')
                ->toArray();
        }

        // If user has no custom settings yet, default imo HD (id 1) to enabled
        if (empty($userEnabledAppIds) && $targetApps->isNotEmpty()) {
            $userEnabledAppIds = [$targetApps->first()->id];
        }

        // Selected active voice (Default to 'AI Male4' or first voice)
        $selectedVoice = $voices->where('name', 'AI Male4')->first() ?: $voices->first();

        // VIP plans
        $vipPlans = VipPlan::active()->get();

        // Guide steps
        $guideSteps = [
            [
                'title' => SiteSetting::get('guide_step1_title', '1. Select Your AI Voice Model'),
                'desc' => SiteSetting::get('guide_step1_desc', 'Choose any free or VIP AI voice from the grid and click Apply.'),
                'icon' => 'sparkles'
            ],
            [
                'title' => SiteSetting::get('guide_step2_title', '2. Connect Your Calling App'),
                'desc' => SiteSetting::get('guide_step2_desc', 'Tap "Select Voice-Changing APP" and enable imo HD.'),
                'icon' => 'mobile'
            ],
            [
                'title' => SiteSetting::get('guide_step3_title', '3. Start Speaking with AI Voice'),
                'desc' => SiteSetting::get('guide_step3_desc', 'Make a call or send audio. ABox converts your microphone voice automatically.'),
                'icon' => 'microphone'
            ],
        ];

        // Recent recordings
        $recentRecordings = $user ? $user->recordings()->with('voice')->latest()->take(10)->get() : collect();

        return view('home', compact(
            'voices',
            'targetApps',
            'userEnabledAppIds',
            'selectedVoice',
            'vipPlans',
            'guideSteps',
            'recentRecordings',
            'user'
        ));
    }
}
