<?php

namespace Database\Seeders;

use App\Models\TargetApp;
use Illuminate\Database\Seeder;

class TargetAppSeeder extends Seeder
{
    public function run(): void
    {
        $apps = [
            [
                'name' => 'imo HD',
                'badge' => 'HD',
                'icon' => '/images/apps/imo_hd.svg',
                'category' => 'calling',
                'package_name' => 'com.imo.android.imohd',
                'is_active' => true,
                'order' => 1,
                'guide_info' => 'Hook into imo HD voice & video calls. Enable virtual microphone in ABox settings to route real-time modified voice.'
            ],
        ];

        // Delete all apps not in the list
        TargetApp::whereNotIn('name', ['imo HD'])->delete();

        foreach ($apps as $app) {
            TargetApp::updateOrCreate(
                ['name' => $app['name']],
                $app
            );
        }
    }
}
