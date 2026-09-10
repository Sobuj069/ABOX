<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => SiteSetting::get('site_name', 'ABox Voice Changer'),
            'site_tagline' => SiteSetting::get('site_tagline', 'Next-Gen AI Real-time Voice Changer'),
            'guide_step1_title' => SiteSetting::get('guide_step1_title', '1. Select Your AI Voice Model'),
            'guide_step1_desc' => SiteSetting::get('guide_step1_desc', 'Choose any free or VIP AI voice from the grid.'),
            'guide_step2_title' => SiteSetting::get('guide_step2_title', '2. Connect Your Calling App'),
            'guide_step2_desc' => SiteSetting::get('guide_step2_desc', 'Enable imo HD, WhatsApp, or Telegram.'),
            'guide_step3_title' => SiteSetting::get('guide_step3_title', '3. Start Speaking with AI Voice'),
            'guide_step3_desc' => SiteSetting::get('guide_step3_desc', 'Make calls with the AI voice active.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            SiteSetting::set($key, (string)$value);
        }

        return back()->with('success', 'Site settings updated successfully!');
    }
}
