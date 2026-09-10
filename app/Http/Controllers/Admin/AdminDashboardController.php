<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voice;
use App\Models\TargetApp;
use App\Models\Recording;
use App\Models\VipPlan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'vip_users' => User::where('is_vip', true)->count(),
            'total_voices' => Voice::count(),
            'vip_voices' => Voice::where('is_vip', true)->count(),
            'free_voices' => Voice::where('is_vip', false)->count(),
            'total_recordings' => Recording::count(),
            'total_apps' => TargetApp::count(),
            'active_apps' => TargetApp::where('is_active', true)->count(),
        ];

        $recentRecordings = Recording::with(['user', 'voice'])->latest()->take(8)->get();
        $recentUsers = User::latest()->take(6)->get();
        $topVoices = Voice::withCount('recordings')->orderByDesc('recordings_count')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentRecordings', 'recentUsers', 'topVoices'));
    }
}
