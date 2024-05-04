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

    <p>A new mail relay request has been submitted. Below are the details:</p>

    <table>
        <tr>
            <th>Ticket ID</th>
            <td>: {{ $data->ticketno }}</td>
        </tr>
        <tr>
            <th>Type of Request</th>
            <td>
                @if ($data->type_req == 1)
                    : New Application
                @elseif ($data->type_req == 2)
                    : Termination
                @else
                    : Ongoing
                @endif
            </td>
        </tr>

        <tr>
            <th>Serial Number</th>
            <td>: {{ $data->form_id }}</td>
        </tr>
        <tr>
            <th>Project Name</th>
            <td>: {{ $data->projectname }}</td>
        </tr>

        <tr>
            <th>Mail Relay server</th>
            <td>: {{ $data->mailrelay }}</td>
        </tr>
        <tr>
            <th>Request Status</th>
            <td>: In Progress</td>
        </tr>
        <!-- Add more rows for other details -->
    </table>

    <p>Administrator please review the request and take appropriate action. Login into SPMR System for more detail information on this request.</p>
    <p>Best Regards,<br>{{ $data->officername }}<br>Helpdesk RedTone</p>
    <i style="color:red;">*** Please don't reply to this email address.***</i>
</body>
</html>
