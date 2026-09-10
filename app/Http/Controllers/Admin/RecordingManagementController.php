<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Recording::with(['user', 'voice'])->latest();

        if ($request->filled('voice_id')) {
            $query->where('voice_id', $request->voice_id);
        }

        $recordings = $query->paginate(15);
        return view('admin.recordings.index', compact('recordings'));
    }

    public function destroy(Recording $recording)
    {
        $recording->delete();
        return back()->with('success', 'Recording removed.');
    }
}
