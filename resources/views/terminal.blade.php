@extends('layouts.app')

@section('content')
{{-- <div class="terminal-window">
    <textarea id="terminal" readonly></textarea>
    <input type="text" id="command-input" placeholder="Enter command..." autofocus />
</div> --}}

<div id="terminal"></div>
@endsection
@push('script')
<script src="https://unpkg.com/xterm-addon-fit"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const term = new Terminal();
        const fitAddon = new FitAddon.FitAddon();
        term.loadAddon(fitAddon);
        term.open(document.getElementById('terminal'));
        fitAddon.fit();

        const socket = new WebSocket('ws://20.6.33.199:8000/');

        let commandBuffer = '';

        term.onData(data => {
            const code = data.charCodeAt(0);

            if (code === 13) { // Enter key
                socket.send(commandBuffer + '\n'); // Send command with newline to server
                commandBuffer = ''; // Clear command buffer
            } else if (code === 8 || code === 127) { // Backspace key
                if (commandBuffer.length > 0) {
                    commandBuffer = commandBuffer.slice(0, -1);
                    term.write('\b \b'); // Clear the last character from the terminal
                }
            } else {
                commandBuffer += data;
                term.write(data); // Echo back to the terminal
            }
        });

        socket.onmessage = event => {
            term.write(event.data); // Display server output in the terminal
        };

        socket.onclose = () => {
            term.write('\r\nConnection closed.');
        };

        document.addEventListener('keydown', event => {
            if (event.ctrlKey && event.key === 'c') {
                // Handle Ctrl+C (copy)
                event.preventDefault();
                // Implement copy logic here if needed
            } else if (event.ctrlKey && event.key === 'v') {
                // Handle Ctrl+V (paste)
                event.preventDefault();
                navigator.clipboard.readText()
                    .then(text => {
                        commandBuffer += text;
                        term.write(text); // Echo back to the terminal
                    })
                    .catch(err => {
                        console.error('Failed to read clipboard contents: ', err);
                    });
            }
        });
    });
</script>
@endpush
