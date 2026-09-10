<?php

namespace App\Http\Controllers;

use App\Models\VipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VipController extends Controller
{
    public function plans()
    {
        $plans = VipPlan::active()->get();
        return response()->json([
            'success' => true,
            'plans' => $plans
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:vip_plans,id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Please login'], 401);
        }

        $plan = VipPlan::findOrFail($request->plan_id);

        $user->is_vip = true;
        if ($plan->duration_days > 0) {
            $currentExpiry = ($user->vip_expires_at && $user->vip_expires_at->isFuture())
                ? $user->vip_expires_at
                : now();
            $user->vip_expires_at = $currentExpiry->addDays($plan->duration_days);
        } else {
            // Lifetime
            $user->vip_expires_at = now()->addYears(50);
        }

        $user->credits += $plan->credits_bonus;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Congratulations! You have upgraded to {$plan->name}!",
            'user' => [
                'name' => $user->name,
                'is_vip' => $user->hasVip(),
                'vip_expires_at' => $user->vip_expires_at ? $user->vip_expires_at->format('M d, Y') : 'Never',
                'credits' => $user->credits,
            ]
        ]);
    }

    public function addCredits(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $amount = (int)$request->input('credits', 100);
        $user->increment('credits', $amount);

        return response()->json([
            'success' => true,
            'credits' => $user->credits,
            'message' => "+{$amount} credits added to your account!"
        ]);
    }
}
