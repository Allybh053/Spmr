document.addEventListener('DOMContentLoaded', function () {
    var commandInput = document.getElementById('command-input');
    if (commandInput) {
        commandInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                let csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                let csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : 'undefined token';
                executeCommand(this.value, csrfToken);
                this.value = '';
            }
        });
    } else {
        console.error('Command input element not found!');
    }
});

function executeCommand(command, csrfToken) {
    fetch('/terminal/execute-ssh-command', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ command: command })
    })
    .then(response => response.json())
    .then(data => {
        const terminal = document.getElementById('terminal');
        terminal.value += `> ${command}\n${data.output}\n`;
        terminal.scrollTop = terminal.scrollHeight;
    });
}
