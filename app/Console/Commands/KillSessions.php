<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class KillSessions extends Command
{
    protected $signature = 'session:kill {sessionId?}';
    protected $description = 'Delete a specific session or all sessions';

    public function handle()
    {
        $sessionId = $this->argument('sessionId');

        if ($sessionId) {
            // Delete specific session
            $filePath = storage_path("framework/sessions/{$sessionId}");

            if (File::exists($filePath)) {
                File::delete($filePath);
                $this->info("Session {$sessionId} has been deleted.");
            } else {
                $this->error("Session {$sessionId} not found.");
            }
        } else {
            // Delete all sessions
            //File::cleanDirectory(storage_path('framework/sessions'));

            $directory = storage_path('framework/sessions');
            $excludeFile = '.gitignore';

            $files = File::files($directory);

            foreach ($files as $file) {
                if ($file->getFilename() !== $excludeFile) {
                    File::delete($file);
                }
            }
            $this->info("All sessions have been deleted.");
        }
    }
}
