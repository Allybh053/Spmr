<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'request',
            'action' => $request->path(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode($request->except(['password', 'password_confirmation'])),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}

