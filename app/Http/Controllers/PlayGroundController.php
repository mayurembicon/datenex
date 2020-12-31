<?php

namespace App\Http\Controllers;

use App\Jobs\SyncDeviceName;
use App\Jobs\SyncSiemensProfinetConfig;
use App\Jobs\SyncSlaveDevice;
use App\Jobs\SyncTagConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Salman\Mqtt\MqttClass\Mqtt;

class PlayGroundController extends Controller
{
    public function sendWhatsAppMessage()
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACd6d76ff542afc402e2890a5f649db84c';
        $token = '3baafdf631455e4b1a2732baec5ad907';
        $client = new Client($sid, $token);

        $message = $client->messages
            ->create("whatsapp:+917600807637", // to
                [
                    "from" => "whatsapp:+14155238886",
                    "body" => "Hello from Embicsdsdsdon Tech Hub!"
                ]
            );

        print($message->sid);
    }
}
