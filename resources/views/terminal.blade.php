<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal</title>
    <style>
        #terminal {
            width: 80%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div id="terminal" contenteditable="true"></div>
    <input type="text" id="file-path" placeholder="Enter file path">
    <button onclick="editFile()">Edit File</button>
    <script>
       function editFile() {
            var terminal = document.getElementById('terminal');
            var filePath = document.getElementById('file-path').value.trim();

            // Construct the command
            var command = 'vim ' + filePath;

            // Display the command in the terminal interface
            terminal.innerHTML += '> ' + command + '<br>';

            // Send the command to the server via AJAX
            sendCommand(command);
        }

        function sendCommand(command) {
            // Send the command to the server via AJAX
            // You can use fetch or jQuery.ajax here
            // Example using fetch:
            fetch('/execute-command', {
                method: 'POST',
                body: JSON.stringify({ command: command }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.text())
            .then(result => {
                // Display the result in the terminal interface
                var terminal = document.getElementById('terminal');
                terminal.innerHTML += result + '<br>';
            })
            .catch(error => console.error('Error:', error));
        }

    </script>
</body>
</html>
