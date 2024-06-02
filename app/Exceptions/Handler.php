<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $this->logException($e);
        });
    }

    protected function logException(Throwable $exception): void
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(), // If you're using authentication
                'activity_type' => 'exception',
                'action' => 'exception',
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip_address' => request()->ip(),
                'input' => json_encode([
                    'message' => $exception->getMessage(),
                    'stack' => $exception->getTraceAsString(),
                ]),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log any errors encountered during logging
            \Log::error('Error logging exception: ' . $e->getMessage());
        }
    }
}
