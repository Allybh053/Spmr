<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification SPMR Email</title>
    <style>
        /* CSS to align content within th tags to the left */
        th {
          text-align: left;
        }
      </style>
</head>
<body>
    <p>SPMR Notification Email,</p>

    <p>A new mail relay request has been approved by officer {{ $user->username }}. Below are the details:</p>

    <table>
        <tr>
            <th>Ticket ID</th>
            <td>: {{ $data->ticketno }}</td>
        </tr>
        <tr>
            <th>Type of Request</th>
            <td>
                @if ($data->types_req == 1)
                    : New Application
                @elseif ($data->types_req == 2)
                    : Termination
                @endif
            </td>
        </tr>

        <tr>
            <th>Serial Number</th>
            <td>: {{ $data->form_id }}</td>
        </tr>
        <tr>
            <th>Project Name</th>
            <td>: {{ $data->project_name }}</td>
        </tr>
        @if($data->smtp_username)
        <tr>
            <th>Username</th>
            <td>: {{ $data->smtp_username }}</td>
        </tr>
        <tr>
            <th>Password</th>
            <td>: {{ $data->smtp_password }}</td>
        </tr>
        <tr>
            <th>Port</th>
            <td>:
                @if ($data->smtp_port == 0)
                    Not Answered
                @elseif ($data->smtp_port == 1)
                    25/tcp
                @elseif ($data->smtp_port == 2)
                    465/tcp
                @elseif ($data->smtp_port == 3)
                    587/tcp
                @elseif ($data->smtp_port == 4)
                    25/tcp & 465/tcp
                @elseif ($data->smtp_port == 5)
                    25/tcp & 587/tcp
                @elseif ($data->smtp_port == 6)
                    465/tcp & 587/tcp
                @elseif ($data->smtp_port == 7)
                    All
                @else
                    Not Answered
                @endif
            </td>
        </tr>
        @endif

        <tr>
            <th>Mail Relay server</th>
            <td>: {{ $data->smtp_relay_server }}</td>
        </tr>
        <tr>
            <th>Requestor By</th>
            <td>: {{ $data->verifier_2_name }}</td>
        </tr>
        <tr>
            <th>Request Status</th>
            <td>: Approved</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>: {{ $data->comment}}</td>
        </tr>
        <!-- Add more rows for other details -->
    </table>

    <p>Login into SPMR System for more detail information on this request.</p><br>
    <i style="color:red;">*** Please don't reply to this email address.***</i>
</body>
</html>
