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

          // Identify the user
          $user = Auth::user();

          // Define the activity type, this is a simple example
          $activityType = $this->determineActivityType($request);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => $activityType ?? 'request',
            'action' => $request->path(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode($request->except(['password', 'password_confirmation'])),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }

    protected function determineActivityType($request)
    {
        // Example of basic activity type determination
        // This can be expanded to more detailed checks
        if ($request->is('login')) {
            return 'login';
        }

        if ($request->is('profile/*')) {
            return 'view_profile';
        }

        if ($request->isMethod('post') || $request->isMethod('put')) {
            return 'update';
        }

        if ($request->isMethod('delete')) {
            return 'delete';
        }

        // Default activity type
        return 'request';
    }
    
}

