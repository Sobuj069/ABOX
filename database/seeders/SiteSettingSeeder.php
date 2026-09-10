<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'ABox Voice Changer',
            'site_tagline' => 'Next-Gen AI Real-time Voice Changer for Calling & Gaming',
            'guide_step1_title' => '1. Select Your AI Voice Model',
            'guide_step1_desc' => 'Choose any free or VIP AI voice (like AI Male4, AI Girl2) from the voice preview grid and click Apply.',
            'guide_step2_title' => '2. Connect Your Calling App',
            'guide_step2_desc' => 'Tap "Select Voice-Changing APP" and enable imo HD.',
            'guide_step3_title' => '3. Start Speaking with AI Voice',
            'guide_step3_desc' => 'Make a call or send a voice message. ABox routes your microphone through the AI transformation engine seamlessly.',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
    }
}
