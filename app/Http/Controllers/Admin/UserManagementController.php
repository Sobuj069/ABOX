<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_vip')) {
            $query->where('is_vip', $request->filter_vip === '1');
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|integer|min:0',
            'role' => 'required|in:admin,user',
            'is_vip' => 'nullable|boolean',
            'vip_days' => 'nullable|integer|min:0',
        ]);

        $user->credits = $request->credits;
        $user->role = $request->role;
        $user->is_vip = $request->boolean('is_vip');

        if ($user->is_vip) {
            $days = (int)$request->input('vip_days', 30);
            if ($days > 0) {
                $user->vip_expires_at = now()->addDays($days);
            } else {
                $user->vip_expires_at = null; // lifetime
            }
        } else {
            $user->vip_expires_at = null;
        }

        $user->save();

        return back()->with('success', "User '{$user->name}' updated successfully!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself!']);
        }

        $user->delete();
        return back()->with('success', 'User account deleted.');
    }
}
