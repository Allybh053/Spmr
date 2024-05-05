<?php

namespace App\Listeners;

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;
use App\Events\TerminalCommandEvent;

class ExecuteCommandListener
{
    public function handle(TerminalCommandEvent $event)
    {
        try {
            $privateKey = file_get_contents(base_path('keys/postmaster_key.pem'));
            $key = PublicKeyLoader::load($privateKey, '88__rGnuGlsmeWAmXdd7');

            $ssh = new SSH2('20.6.33.199');
            if (!$ssh->login('pentadbir', $key)) {
                throw new \RuntimeException('SSH Login Failed');
            }

            $command = $event->command;
            $output = $ssh->exec($command);

            event(new TerminalOutputEvent($output));
        } catch (\Exception $e) {
            event(new TerminalOutputEvent($e->getMessage()));
        }
    }
}
