<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TargetApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppManagementController extends Controller
{
    public function index()
    {
        $apps = TargetApp::orderBy('order')->paginate(15);
        return view('admin.apps.index', compact('apps'));
    }

    public function create()
    {
        return view('admin.apps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'badge' => 'nullable|string|max:10',
            'category' => 'required|string|max:30',
            'package_name' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'guide_info' => 'nullable|string',
            'icon_file' => 'nullable|file|mimes:svg,png,jpg,webp|max:2048',
            'icon_url' => 'nullable|string',
        ]);

        $iconPath = '/images/apps/imo_hd.svg';
        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('public/apps/icons');
            $iconPath = Storage::url($path);
        } elseif ($request->filled('icon_url')) {
            $iconPath = $request->icon_url;
        }

        TargetApp::create([
            'name' => $validated['name'],
            'badge' => $validated['badge'],
            'icon' => $iconPath,
            'category' => $validated['category'],
            'package_name' => $validated['package_name'],
            'order' => $validated['order'],
            'guide_info' => $validated['guide_info'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.apps.index')->with('success', 'App added successfully!');
    }

    public function edit(TargetApp $app)
    {
        return view('admin.apps.edit', compact('app'));
    }

    public function update(Request $request, TargetApp $app)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'badge' => 'nullable|string|max:10',
            'category' => 'required|string|max:30',
            'package_name' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'guide_info' => 'nullable|string',
            'icon_file' => 'nullable|file|mimes:svg,png,jpg,webp|max:2048',
            'icon_url' => 'nullable|string',
        ]);

        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('public/apps/icons');
            $app->icon = Storage::url($path);
        } elseif ($request->filled('icon_url')) {
            $app->icon = $request->icon_url;
        }

        $app->name = $validated['name'];
        $app->badge = $validated['badge'];
        $app->category = $validated['category'];
        $app->package_name = $validated['package_name'];
        $app->order = $validated['order'];
        $app->guide_info = $validated['guide_info'];
        $app->is_active = $request->boolean('is_active');
        $app->save();

        return redirect()->route('admin.apps.index')->with('success', 'App updated successfully!');
    }

    public function destroy(TargetApp $app)
    {
        $app->delete();
        return redirect()->route('admin.apps.index')->with('success', 'App removed.');
    }
}
