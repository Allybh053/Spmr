<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use App\Models\Sessions;

class ListActiveSessions extends Command
{
    protected $signature = 'session:list';
    protected $description = 'List all active file-based Laravel sessions with username, validity, and client IP address';

    public function handle()
    {
        $sessionFiles = File::files(storage_path('framework/sessions'));

        if (empty($sessionFiles)) {
            $this->info("No active sessions found.");
            return;
        }

        foreach ($sessionFiles as $sessionFile) {
            $sessionData = File::get($sessionFile->getPathname());
            $this->line("Session ID: " . $sessionFile->getFilename());

            try {
                $data = @unserialize($sessionData);

                if ($data === false) {
                    $data = unserialize(Crypt::decrypt($sessionData));
                }

                //$this->line("Full Session Data: " . print_r($data, true));

                /*foreach ($data as $key => $value) {
                    if (str_starts_with($key, 'login_web_')) {
                        $id = intval($value);
                        $user = User::find($id);

                        if ($user) {
                            $this->line("Username: " . $user->username);
                            $clientIp = $data['client_ip'] ?? 'Unknown IP';
                            $this->line("Client IP: $clientIp");
                        } else {
                            $this->line("No user found for ID: $id");
                        }
                    }
                }*/

                $username = $data['user'] ?? 'Anonymous';
                $clientIp = $data['client_ip'] ?? 'Unknown IP';
                $_token = $data['_token'] ?? '--';
                $url = isset($data['url']) ? implode(', ', $data['url']) : '--';
                $_previous_url = $data['_previous']['url'] ?? '-'; // Nested array access
                $_flash_old = isset($data['_flash']['old']) ? implode(', ', $data['_flash']['old']) : '--';
                $_flash_new = isset($data['_flash']['new']) ? implode(', ', $data['_flash']['new']) : '--';
                $accessTime = $data['access_time'] ?? '--';

                //$lastActivity = $data['last_activity'] ?? null;

                /*$validity = $lastActivity
                    ? Carbon::createFromTimestamp($lastActivity)->diffForHumans()
                    : 'Unknown';*/

                $this->line("Token: $_token");
                foreach ($data as $key => $value) {
                    if (str_starts_with($key, 'login_web_')) {
                        $loginWeb = $key ?? '--';
                        $this->line("Login Web: $loginWeb");
                    }
                }
                $this->line("URL: $url");
                $this->line("Previous URL: $_previous_url");
                $this->line("Flash (Old): $_flash_old");
                $this->line("Flash (New): $_flash_new");
                $this->line("Username: $username");
                $this->line("Client IP: $clientIp");
                $this->line("Access Date: $accessTime");
                //$this->line("Validity: $validity");

            } catch (\Exception $e) {
                $this->error("Failed to read session file: " . $sessionFile->getFilename());
                $this->line("Error: " . $e->getMessage());
                $this->line("Raw Content: " . $sessionData);
            }

            $this->line('--------------------------------------');
        }

        $this->info("Total Active Sessions: " . count($sessionFiles));
    }
}
