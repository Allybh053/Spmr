<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportController implements ToModel
{

    /**
     * @inheritDoc
     */
    public function model(array $row)
    {
        // Convert the Excel serial number to a Unix timestamp
        $unixTimestamp = ($row[14] - 25569) * 86400;
        $unixTimestamp2 = ($row[17] - 25569) * 86400;
        $unixTimestamp3 = ($row[19] - 25569) * 86400;

        // Convert the Unix timestamp to a Carbon instance
        $date_application = Carbon::createFromTimestamp($unixTimestamp)->format('Y-m-d H:i:s');
        $date_verification_2 = Carbon::createFromTimestamp($unixTimestamp2)->format('Y-m-d H:i:s');
        $date_verification_3 = Carbon::createFromTimestamp($unixTimestamp3)->format('Y-m-d H:i:s');

        $randomId = Str::random(9); // variable for form_id

        return new SmtpRelayReqForm([
            'form_id' => $randomId,
            'ticket_id' => $row[1],
            'types_req' => $row[2],
            'project_name' => $row[3],
            'ministry' => $row[4],
            'system_owner_name' => $row[5],
            'owner_email_addr' => $row[6],
            'owner_phone_number' => $row[7],
            'agencies' => $row[8],
            'dept_div_unit' => $row[9],
            'smtp_relay_auth_method' => $row[10],
            'mailfrom_email_addr' => $row[11],
            'mailbox_created' => $row[12],
            'applicant_name' => $row[13],
            'date_application' => $date_application,
            'verifier_1_name' => $row[15],
            'verifier_2_name' => $row[16],
            'date_verification_2' => $date_verification_2,
            'verifier_3_name' => $row[18],
            'date_verification_3' => $date_verification_3,
            'smtp_relay_server'=> $row[21],
            'smtp_username'=> $row[21],
            'smtp_password'=> $row[22],
            'smtp_port'=> $row[23],
            'status' => 0,
        ]);
    }
}
