<?php

namespace App\Http\Controllers;

use App\Models\Mailrouting;
use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use phpseclib3\Net\SSH2;
use phpseclib\Net\SSH2;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailApproved;
use App\Mail\mailReject;
use App\Models\User;
use App\Models\Server;
use App\Models\DomainServer;
use App\Models\RoleServer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\HistoryCopyAttachment;


class MainRelayController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        $smtpMailServers = DB::table('smtp_relay_req_form')
        ->orderBy('status', 'desc')
        ->get();

        return view('mailrelay.index', compact('user','smtpMailServers'));
    }
    public function checkIpAddress(Request $request)
    {

        // // Get the IP address from the request
        // $ipAddress = $request->input('tableSpmr.0.ipaddress');
        // $selectedValuetype_req = $request->input('selectedValuetype_req');
        // $selectedValue = $request->input('selectedValue');
        // //think how to add identify
        // // if ($selectedValue == $selectedValue){
        // $filePath = storage_path($selectedValue.'/postfix/access_list'); //root : root permission denied
        // // }else{
        // //     //
        // // }

        // try {
        //     // Check the IP address in the database
        //     $count = smtp_relay_server::where('ip_addr_server', $ipAddress)
        //                                ->where('types_req', $selectedValuetype_req)
        //                                ->count();

        //     if ($count > 0) {
        //         if ($selectedValuetype_req == 1) {
        //             return "<span style='color: red'>The IP address $ipAddress already exists in the system.</span>";
        //         } elseif ($selectedValuetype_req == 2) {
        //             return "<span style='color: red'>The IP address $ipAddress already terminated in the system.</span>";
        //         }
        //     }

        //     // Read the file content and check the IP address in the file
        //     $fileContent = file_get_contents($filePath);
        //     $lines = explode("\n", $fileContent);

        //     foreach ($lines as $line) {
        //         $trimmed_line = trim($line);

        //         if ($selectedValuetype_req == 1) {
        //             // Check if the line matches the IP address exactly
        //             $file_ip = trim(explode('#', $trimmed_line)[0]);
        //             if ($file_ip === $ipAddress) {
        //                 return "<span style='color: red'>The IP address $ipAddress already existed in the Server Access List.</span>";
        //             }
        //         } elseif ($selectedValuetype_req == 2) {
        //             // Check if the line starts with '#' followed by the IP address
        //             if (strpos($trimmed_line, "#$ipAddress") === 0) {
        //                 return "<span style='color: red'>The IP address $ipAddress already terminated in the Server Access List.</span>";
        //             }
        //         }
        //     }
        // } catch (\Exception $e) {
        //     return "Error 404! :No such file or directory!" . $e->getMessage();
        // }
    }


    public function show()
    {
        // Register mail relay that is pending
        $user = Auth::user();
        $smtpMailServers = DB::table('smtp_relay_req_form')->where('status',0)
        ->groupBy('form_id','id')
        ->get();

        return view('mailrelay.register', compact('user','smtpMailServers'));
    }

    public function view(string $id, $type)
    {
        $user = Auth::user();

        $smtpreq = SmtpRelayReqForm::find($id);

        $smtpMailServers = smtp_relay_server::where('form_id',$smtpreq->form_id)
                ->where('types_req',$smtpreq->types_req)
                ->get();

        return view('mailrelay.edit', compact('user','smtpMailServers','smtpreq'));
    }

    public function papar($id, $type){
        $datasmtp = SmtpRelayReqForm::where('id', $id)->where('types_req', $type)->first();
        $details = smtp_relay_server::where('form_id', $datasmtp->form_id)
                    ->where('types_req', $datasmtp->types_req)
                    ->get();
        $attachment = HistoryCopyAttachment::select('sm.upload_copy_attachment','history_copy_attachment.attachment_path');
        return view('smtp_relay.view',compact('datasmtp','details','attachment'));
    }

    public function submitrejForm(Request $request){

        $comment = $request->input("comment");
        $smtp_username = $request->input("smtp_username");
        $smtp_password = $request->input("smtp_password");
        $user = Auth::user();

        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>2,'implementation_name'=>$user->username,'smtp_password'=>$smtp_password,'smtp_username'=>$smtp_username]);
        return response()->json(['message' => $update]);
        if ($update) {
        $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
        Mail::to($adminEmails)
        ->cc($request->input('owner_email_addr'))
        ->send(new mailReject($request));

        $successMessage = 'Form updated!';
        return response()->json(['message' => $successMessage]);
        }else{
        $Message = 'Error!';
        return response()->json(['message' => $Message]);
        }

    }

    public function submitappForm(Request $request)
    {   
        $formData = $request->all();
        $smtp_relay_server = $request->input("smtp_relay_server");
        $access_list = $request->input("access_list");
        $types_req = $request->input("types_req");
        $form_id = $request->input("form_id");

        if ($types_req == 1 ){
            // $access_list = preg_replace('/^.*$/m', '$0;', $access_list);
            // $lines = explode("\n", $access_list);

            // foreach ($lines as &$line) {
            //     $line = trim($line) . $request->get('formId');
            // }
            // $access_list = implode("\n", $lines);

            // $filePath = storage_path($smtp_relay_server.'/postfix/access_list');

            // $file = fopen($filePath, 'a');

            // fwrite($file, $access_list . PHP_EOL);

            // fclose($file);
        } else if ($types_req == 2){
            // $filePath = storage_path($smtp_relay_server.'/postfix/access_list');

            // $fileContent = file_get_contents($filePath);
            // $lines = explode("\n", $fileContent);
            // $ipFound = false;

            // foreach (explode("\n", $access_list) as $access_line) {

            //     $access_ip = explode('#', $access_line)[0];
            //     $access_ip = trim($access_ip);

            //     if (!empty($access_ip)) {
            //         foreach ($lines as &$line) {
            //             // Trim leading and trailing whitespace from the file content line
            //             $trimmed_line = trim($line);

            //             // Split the file content line by '#' and take the first part
            //             $file_ip = explode('#', $trimmed_line)[0];

            //             // Trim leading and trailing whitespace from the file IP
            //             $file_ip = trim($file_ip);

            //             // Check if the access IP matches the file IP
            //             if ($file_ip === $access_ip) {
            //                 // If the access IP is found, prepend '#' to the line
            //                 $line = '#' . $line;
            //                 $ipFound = true;
            //                 break;
            //             }

            //         }
            //         // If the IP was not found in the existing lines, add it as a new line
            //         if (!$ipFound) {
            //             $lines[] = "#$access_ip #";
            //         }

            //         $record = smtp_relay_server::where('ip_addr_server', $access_ip)
            //         ->where('form_id', $form_id)
            //         ->first();
            //         if ($record) {
            //             // If the record exists, update the deactivated field
            //             $record->update(['deactivated' => 1]);
            //         }
            //     }

            // }

            // // Join the lines back together
            // $newContent = implode("\n", $lines);

            // // Write the modified content back to the file
            // file_put_contents($filePath, $newContent);

        }else{
            //set outbound type of request here
            $Message = 'Contact Administrator';
            return response()->json(['message' => $Message]);
        }

        $smtp_username = $request->input("smtp_username");
        $smtp_password = $request->input("smtp_password");
        $comment = $request->input("comment");
        $user = Auth::user();
        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>1,'implementation_name'=>$user->username,'smtp_password'=>$smtp_password,'smtp_username'=>$smtp_username]);

        // $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>1,'implementation_name'=>$user->username]);

        if ($update) {

            $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
            Mail::to($adminEmails)
            ->cc($request->input('owner_email_addr'))
            ->send(new mailApproved($request));

        $successMessage = 'Form updated';

        return response()->json(['message' => $successMessage, 'serverhost'=> $smtp_relay_server]);
        }else{
        $Message = 'Error 404!';
        return response()->json(['message' => $Message]);
        }
    }

    public function mailupstream(Request $request)
    {
        $ff = $request->serverconfig;

        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        //$serverconfig = Server::where('status', 1)->get();

        //$serverdetail = DomainServer::where('server_id', $ff)->get();

        $defaultServerId = $serverIds->min();

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');



        return view('mailrelay.mailupstream', compact('user','serverconfig', 'defaultServerId'));
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }

    public function search(Request $request)
    {
        // Retrieve other inputs from the request
        $serverIds = $request->input('server');

        // You can adjust the search logic based on the selected calendar type and agency here
        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;

        $queryMailRoute = Mailrouting::where('server', $serverName);

        // Execute the query
        $valDataMailRoute = $queryMailRoute->get();
        $totalDomActive = Mailrouting::where('server', $serverName)->where('status', true)->count();
        $totalDomDeactive = Mailrouting::where('server', $serverName)->where('status', false)->count();
        $totalDomAll = Mailrouting::where('server', $serverName)->count();


        return response()->json([
            'totalDomainActivated' => $totalDomActive,
            'totalDomainDeactivated' => $totalDomDeactive,
            'totalDomainRegistered' => $totalDomAll,
            'dataMailRoute' => $valDataMailRoute,
        ]);
    }

}
