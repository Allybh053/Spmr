<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use Illuminate\Support\Facades\Http;
use App\Events\TerminalCommandEvent;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TerminalController extends Controller {

    protected $ssh;

    public function __construct()
    {
        // Open SSH connection when the controller is instantiated
        $this->openSSHConnection();
    }
    public function executeCommand(Request $request)
    {
        try {
            // Example command
            $command = $request->input('command');

            // Execute command and get real-time response
            $output = $this->executeCommandInternal($command);

            return response()->json(['output' => $output]);
        } catch (\Exception $e) {
            // Handle any exceptions here
            return response()->json(['output' => 'Error: ' . $e->getMessage()]);
        }
    }
    protected function openSSHConnection()
    {
        try {
            // Load private key
            $privateKey = file_get_contents(base_path('keys/postmaster_key.pem'));
            $key = \phpseclib3\Crypt\PublicKeyLoader::load($privateKey, '88__rGnuGlsmeWAmXdd7');

            // Create SSH connection
            $this->ssh = new SSH2('20.6.33.199');

            // Attempt login
            if (!$this->ssh->login('pentadbir', $key)) {
                throw new \Exception('Login Failed');
            }
        } catch (\Exception $e) {
            // Handle any exceptions here
            throw new \Exception('SSH Connection Error: ' . $e->getMessage());
        }
    }
    protected function executeCommandInternal($command)
    {
        try {
            // Open interactive shell
            $this->ssh->setTimeout(0); // Disable timeout for continuous interaction
            $this->ssh->read('[prompt]'); // Read the prompt

            // Send command
            $this->ssh->write($command . PHP_EOL);

            // Read command output
            $output = '';
            while ($line = $this->ssh->read()) {
                // Check if the prompt is found in the output
                if (strpos($line, '[prompt]') !== false) {
                    break; // Exit the loop if the prompt is found
                }
                $output .= $line;
            }

            return response()->json(['output' => $output]); // Return command output
        } catch (\Exception $e) {
            // Handle any exceptions here
            throw new \Exception('Command Execution Error: ' . $e->getMessage());
        }
    }

    public function index(){
        return view('terminal');
    }


    public function executeCommandtestaimi(Request $request)
    {
        try {
            $privateKey = file_get_contents(base_path('keys/postmaster_key.pem'));
            $key = \phpseclib3\Crypt\PublicKeyLoader::load($privateKey, '88__rGnuGlsmeWAmXdd7');

            $ssh = new \phpseclib3\Net\SSH2('20.6.33.199');
            if (!$ssh->login('pentadbir',  $key)) {
                return response()->json(['output' => 'Login Failed']);
            }

            $command = $request->input('command');

                // Get username
            $ssh->exec("whoami");
            $username = trim($ssh->read());

            // Get hostname
            $ssh->exec("hostname");
            $hostname = trim($ssh->read());

            // Get current directory
            $ssh->exec("pwd\n");
            $currentDirectory = trim($ssh->read());

                // Prepend username, hostname, and current directory to the prompt
                $terminalPrompt = "$username@$hostname [\\$currentDirectory]";

                // Display the terminal prompt
                $output = "$terminalPrompt\n> $command\n";

            // // Check if the command is to open a file for editing
            // if (strpos($command, 'vim') !== false) {

            //     $fileName = substr($command, 4); // Extract the filename from the command

            //     // Send Vim command to open the file
            //     $ssh->write("vim $fileName\n");

            //     // Wait for Vim to load
            //     sleep(2);

            //     // Send commands to Vim to read the file content
            //     $ssh->write(":set paste\n"); // Ensure paste mode is enabled
            //     $ssh->write(":i $fileName\n"); // Read file content into Vim buffer

            //     // Wait for Vim to read the file
            //     sleep(2);

            //     // Load content from $command into the file
            //     $contentPosition = strpos($command, '-c');
            //     if ($contentPosition !== false) {
            //         $content = substr($command, $contentPosition + 3); // Assuming content follows "-c "
            //         $ssh->write("$content\n");
            //     }

            //     // Check if $command is :wq!
            //     if (trim($command) === ':wq!') {
            //         // Send command to save changes and exit Vim
            //         $ssh->write(":wq!\n");
            //     }

            //     // // Send commands to Vim to insert text (write)
            //     // $textToWrite = "Text to append to the file"; // Modify as needed
            //     // $ssh->write("$textToWrite\n");

            //     // // Send commands to Vim to save changes and exit
            //     // $ssh->write(":wq\n");

            //     // // Wait for Vim to save changes and exit
            //     // sleep(2);

            //     // Read output from SSH session to get any errors or confirmation
            //     $output = $ssh->read();
            // }else if (strpos($command, 'cd') !== false) {
            //     $directory = substr($command, 3); // Extract the directory from the command
            //     $ssh->exec("cd $directory && pwd"); // Change directory and print current directory
            //     $output .= $ssh->read(); // Read the output to get the current directory
            //     $output .= $ssh->exec("ls -l");
            // }else if (strpos($command, 'touch') !== false) {
            //     $fileName = substr($command, 6); // Extract the filename from the command
            //     $ssh->write("touch $fileName\n");
            //     $ssh->exec("touch $fileName\n");
            //     $output .= "Created new file $fileName\n";
            // }  else {
            //     // Execute the command as usual
                $output .= $ssh->exec($command);
            // }

            $output = preg_replace('/\e\[?.*?[\\-]?[\dA-Za-z]/', '', $output);

            return response()->json(['output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['output' => $e->getMessage()]);
        }

    }
    // public function show()
    // {
    //     return view('internal.cli');
    // }

    public function terminal()
    {
        $client = new Client([
            'verify' => false, // Disable SSL certificate verification
        ]);

        try {
            // $response = Http::get('https://localhost:4200/');
            $response = $client->get('https://localhost:4200/');

            $statusCode = $response->getStatusCode();

            // Check if the response was successful
            // if ($response->successful()) {
                if ($statusCode >= 200 && $statusCode < 300) {
                // Return the response body
                // $responseBody = $response->body();
                $responseBody = $response->getBody()->getContents();

                // Load the private key
                $privateKeyContents = file_get_contents(base_path('keys/postmaster_key.pem'));
                $key = PublicKeyLoader::load($privateKeyContents, '88__rGnuGlsmeWAmXdd7');

                // Construct the SSH command
                $sshCommand = "ssh -i $key pentadbir@13.12.21.02";

                // Execute SSH command in the terminal
                Artisan::call('shell:exec', ['command' => $sshCommand]);

                Artisan::call('cd', ['/opt/cluster/postfix']);

                return $responseBody;

            } else {
                // Return an error message
                $Error = "Unable to fetch content from the terminal!";
                echo "<script>alert('$Error');</script>";
                return $statusCode;
            }
        } catch (RequestException $e) {

            // An error occurred during the HTTP request
            return "Error: " . $e->getMessage();
        }
    }
}

