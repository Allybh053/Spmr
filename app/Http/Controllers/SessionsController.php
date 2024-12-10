<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class SessionsController extends Controller
{

    public function index(Request $request)
    {
        // Delete all the data before retrieve the new data and display to front-end
        Sessions::query()->delete();

        // Get all session files from the framework/sessions directory
        $sessionFiles = File::files(storage_path('framework/sessions'));

        // Store session IDs of existing files for reference
        //$existingSessionIds = array_map(fn($file) => $file->getFilename(), $sessionFiles);

        foreach ($sessionFiles as $sessionFile) {
            $sessionData = File::get($sessionFile->getPathname());

            try {
                $data = @unserialize($sessionData);

                if ($data === false) {
                    $data = unserialize(Crypt::decrypt($sessionData));
                }

                $username = $data['user'] ?? 'Anonymous';
                $clientIp = $data['client_ip'] ?? 'Unknown IP';
                $_token = $data['_token'] ?? '--';
                $url = $data['url'] ?? '--';
                $_previous_url = $data['_previous']['url'] ?? '-';
                $_flash_old = isset($data['_flash']['old']) ? implode(', ', $data['_flash']['old']) : '--';
                $_flash_new = isset($data['_flash']['new']) ? implode(', ', $data['_flash']['new']) : '--';
                $accessTime = $data['access_time'] ?? null;

                if ($accessTime && !strtotime($accessTime)) {
                    $accessTime = null;
                }

                Sessions::updateOrCreate(
                    ['session_id' => $sessionFile->getFilename()],
                    [
                        'username' => $username,
                        'client_ip' => $clientIp,
                        'token' => $_token,
                        'url' => $url,
                        'previous_url' => $_previous_url,
                        'flash_old' => $_flash_old,
                        'flash_new' => $_flash_new,
                        'created_at' => $accessTime,
                        'updated_at' => now(),
                    ]
                );

            } catch (\Exception $e) {
                Log::error("Failed to process session file {$sessionFile->getFilename()}", [
                    'error' => $e->getMessage(),
                    'file_content' => $sessionData,
                ]);
            }
        }

        // Retrieve all sessions for display
        $sessions = Sessions::all();
        //$sessions = Sessions::get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Sessions',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'sessions' => 'Retrieve data for viewing sessions page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('sessions.index', compact('sessions'));
    }

    public function sessionsdelete(Request $request,$id)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Delete Sessions',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'sessions' => 'Delete data for viewing sessions page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        $sessions = Sessions::findOrFail($id);

        if ($sessions) {
            // Delete specific session
            $filePath = storage_path("framework/sessions/{$sessions->session_id}");

            if (File::exists($filePath)) {
                File::delete($filePath); // delete file sessions in server
                $sessions->delete(); // delete data on database
                $sessions->get();
            } else {
                return redirect()->route('sessions.index')->with('error', 'Session ID not found');
            }
        }

        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully.');
    }

}
