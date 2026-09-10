<?php

namespace Database\Seeders;

use App\Models\VipPlan;
use Illuminate\Database\Seeder;

class VipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Weekly VIP',
                'price' => 3.99,
                'duration_days' => 7,
                'credits_bonus' => 200,
                'badge' => null,
                'features' => [
                    'Unlock All VIP AI Voices',
                    'HD Audio Quality 320kbps',
                    'Real-time Call Voice Changer',
                    '200 Bonus Credits',
                    'No Watermark on Audio',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Monthly VIP',
                'price' => 9.99,
                'duration_days' => 30,
                'credits_bonus' => 1000,
                'badge' => 'POPULAR',
                'features' => [
                    'Unlock All 12+ VIP AI Voices',
                    'Unlimited App Hook Routing',
                    '1000 Bonus Credits',
                    'Ultra-Low Latency Mode',
                    'Priority Support & Early Access',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Lifetime VIP',
                'price' => 29.99,
                'duration_days' => 0, // lifetime
                'credits_bonus' => 5000,
                'badge' => 'BEST VALUE',
                'features' => [
                    'Permanent Unlimited Access',
                    'All Future AI Voices Included',
                    '5000 Bonus Credits',
                    'VIP Discord Role & Badge',
                    'One-time Payment Forever',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            VipPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
