@extends('layouts.app')

@section('content')
{{-- <div class="terminal-window">
    <textarea id="terminal" readonly></textarea>
    <input type="text" id="command-input" placeholder="Enter command..." autofocus />
</div> --}}

<div id="terminal"></div>
@endsection
@push('script')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const term = new Terminal();
    const socket = new WebSocket('ws://20.6.33.199:8000/'); // WebSocket endpoint

    term.open(document.getElementById('terminal'));

    let commandBuffer = ''; // Buffer to accumulate user input

    term.onData(data => {
        // Handle special keys and regular characters
        if (data.charCodeAt(0) === 13) { // Check if Enter key is pressed
            // Send the accumulated command to the server
            socket.send(commandBuffer);
            commandBuffer = ''; // Clear the command buffer
        } else {
            // Accumulate characters to form the command
            commandBuffer += data;
            term.write(data); // Echo back to the terminal
        }
    });

    socket.onmessage = event => {
        // Display server output in the terminal
        term.write(event.data);
    };

    socket.onclose = () => {
        // Handle WebSocket connection close
        term.write('\r\nConnection closed.');
    };


});

</script>
@endpush
