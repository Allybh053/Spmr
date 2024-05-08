<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;
use App\Events\TerminalCommandEvent;

class TerminalController extends Controller {

    public function index(){
        return view('terminal');
    }
        public function executeCommand(Request $request)
        {

            try {
                // Simulate SSH command execution

                $command = $request->input('command');
                $output = shell_exec($command); // Execute command (replace with your SSH logic)

                // Broadcast output to WebSocket server
                broadcast(new TerminalCommandEvent($output));

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
}

