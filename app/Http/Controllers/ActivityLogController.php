<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with(['user'])
            ->when($request->has('search'), function($query) use ($request) {
                $query->where('deskripsi', 'like', '%'.$request->search.'%');
            })
            ->latest()
            ->paginate(15);
            
        return view('pengaturan.logs.index', compact('logs'));
    }

    public function show(ActivityLog $log)
    {
        return view('pengaturan.logs.detail', compact('log'));
    }

    public static function addToLog($deskripsi)
    {
        $log = [
            'user_id' => auth()->id(),
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip()
        ];

        ActivityLog::create($log);
    }
}