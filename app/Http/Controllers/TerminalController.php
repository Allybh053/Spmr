<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib\Net\SSH2;

class TerminalController extends Controller {

    public function index(){
        return view('terminal');
    }
        public function executeCommand(Request $request)
        {
            $host = 'your_host';
            $username = 'your_username';
            $privateKey = 'your_private_key';

            // Retrieve the command from the request
            $command = $request->input('command');

            $ssh = new SSH2($host);
            if (!$ssh->login($username, $privateKey)) {
                return response()->json(['error' => 'Login Failed']);
            }

            // Execute the command
            $output = $ssh->exec($command);

            // Return the output
            return response()->json(['output' => $output]);
        }
}

