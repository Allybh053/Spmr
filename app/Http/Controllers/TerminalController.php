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
            try {
                $ssh = new SSH2('20.6.33.199');
                if (!$ssh->login('user', 'pass')) {
                    exit('Login Failed');
                }
                $output = $ssh->exec($request->input('command'));
                return response()->json(['output' => $output]);
            } catch (\Exception $e) {
                return response()->json(['output' => $e->getMessage()]);
            }
        }
}

