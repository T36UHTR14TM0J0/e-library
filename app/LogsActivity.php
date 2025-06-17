<?php

namespace App;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    
    public static function logActivity(string $description): ActivityLog
    {
        return ActivityLog::create([
            'user_id'       => Auth::id(),
            'deskripsi'     => $description,
            'ip_address'    => Request::ip()
        ]);
    }
}
