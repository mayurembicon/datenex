<?php

namespace App\Console\Commands;

use App\OnlineInquiry;
use App\TaskScheduling;
use App\Tenant;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenant = DB::table('tenants')->select('data')->get();


        foreach ($tenant as $value) {

            $tenancy_db_name = json_decode(json_encode($value),true);

            $databaseName =json_decode( $tenancy_db_name['data']);
            $datbaseFinalName=$databaseName->tenancy_db_name;

            $this->syncInquiry($datbaseFinalName);


        }

    }

    public function syncInquiry($tenantID)
    {

        $response = '';
        $currentDate = date('Y-m-d');
        $currentTimeStamp = date('Y-m-d H:i:s');
        $recordCounter = 0;
        $settingInfo = DB::table($tenantID . '.setting')->first();
        if (isset($settingInfo->trade_user_id) or isset($settingInfo->trade_profile_id) or isset($settingInfo->trade_key) or isset($settingInfo->india_mobile_no) or isset($settingInfo->india_key)) {
            $inquiryFrom = 'TRADEINDIA';
            $last_tradeindia_sync = date('Y-m-d', strtotime($settingInfo->last_tradeindia_sync));
//            $response             = file_get_contents( "https://www.tradeindia.com/utils/my_inquiry.html?userid=3810466&profile_id=5164844&key=f8b778dc1fa04e148be1b004a416c6ad&from_date=$last_tradeindia_sync&to_date=$currentDate" );
            $response = file_get_contents("https://www.tradeindia.com/utils/my_inquiry.html?userid=" . $settingInfo->trade_user_id . "&profile_id=" . $settingInfo->trade_profile_id . "&key=" . $settingInfo->trade_key . "&from_date=$last_tradeindia_sync&to_date=$currentDate");
            $responseJSONToArray = json_decode($response);
            foreach ($responseJSONToArray as $item) {
                $inquiryExists = DB::table($tenantID . '.online_inquiry')->where('inq_uuid', '=', $item->rfi_id)->where('inquiry_from', $inquiryFrom)->first();
                if ($inquiryExists === null) {
                    $onlineInquiry = array();
                    $onlineInquiry['inquiry_from'] = $inquiryFrom;
                    $onlineInquiry['inq_uuid'] = isset($item->rfi_id) ? $item->rfi_id : 0;
                    $onlineInquiry['inq_date'] = date('Y-m-d', strtotime($item->generated_date));
                    $onlineInquiry['sender_company'] = isset($item->sender_co) ? $item->sender_co : '';
                    $onlineInquiry['sender_name'] = isset($item->sender_name) ? $item->sender_name : '';
                    $onlineInquiry['sender_email'] = isset($item->sender_email) ? $item->sender_email : '';
//                    $onlineInquiry->sender_other_email  = $item->sender_other_emails;
                    $onlineInquiry['sender_mobile'] = isset($item->sender_mobile) ? $item->sender_mobile : '';
//                    $onlineInquiry->sender_other_mobile = $item->sender_other_mobiles;
                    $onlineInquiry['sender_city'] = isset($item->sender_city) ? $item->sender_city : '';
                    $onlineInquiry['sender_state'] = isset($item->sender_state) ? $item->sender_state : '';
                    $onlineInquiry['sender_country'] = isset($item->sender_country) ? $item->sender_country : '';
                    $onlineInquiry['subject'] = isset($item->subject) ? $item->subject : '';
                    $onlineInquiry['notes'] = isset($item->message) ? $item->message : '';
                    $onlineInquiry['product_name'] = isset($item->product_name) ? $item->product_name : '';
                    $onlineInquiry['product_qty'] = isset($item->quantity) ? intval($item->quantity) : 1;
                    $onlineInquiry['financial_id'] = 1;
                    $onlineInquiry['inq_full_info'] = json_encode($item);
                    DB::table($tenantID . '.online_inquiry')->insert($onlineInquiry);
                    $recordCounter++;
                }
            }
            DB::table($tenantID . '.setting')->where('id', $settingInfo->id)->update(['last_tradeindia_sync' => date('Y-m-d H:i:s')]);
            /** Update Last Sync Tradeindia Time */
            /** Sync time limit in Minute */
            $recordCounter = 0;
            $inquiryFrom = 'INDIAMART';
            $syncTimeLimit = $settingInfo->indiamart_sync_time_limit;
            $lastSyncTimeStamp = $settingInfo->last_indiamart_sync;

            /** check if time difference more then time limit then sync from Indiamart & Update time in Database */
            $datetime1 = strtotime($lastSyncTimeStamp);
            $datetime2 = strtotime(date('Y-m-d H:i:s'));
            $interval = abs($datetime2 - $datetime1);
            $differenceMinutes = round($interval / 60);
            if ($differenceMinutes < $syncTimeLimit) {
            } else {
//                $response = file_get_contents( "http://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/9510687577/GLUSR_MOBILE_KEY/MTU1ODE3NjQ0Ny4xNzg3IzMwNjk2NTg=/Start_Time/" . str_replace( " ", "%20", $lastSyncTimeStamp ) . "/End_Time/" . str_replace( " ", "%20", $currentTimeStamp ) . "/" );
                $response = file_get_contents("http://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/" . $settingInfo->india_mobile_no . "/GLUSR_MOBILE_KEY/" . $settingInfo->india_key . "/Start_Time/" . str_replace(" ", "%20", $lastSyncTimeStamp) . "/End_Time/" . str_replace(" ", "%20", $currentTimeStamp) . "/");

                $responseJSONToArray = json_decode($response);
                foreach ($responseJSONToArray as $item) {
                    $inquiryExists = DB::table($tenantID . '.online_inquiry')->where('inq_uuid', '=', $item->QUERY_ID)->where('inquiry_from', $inquiryFrom)->first();
                    if ($inquiryExists === null) {
                        $onlineInquiry = array();
                        $onlineInquiry['inquiry_from'] = $inquiryFrom;
                        $onlineInquiry['inq_uuid'] = $item->QUERY_ID;
                        $onlineInquiry['inq_date'] = date('Y-m-d', strtotime($item->DATE_RE));
                        $onlineInquiry['sender_company'] = $item->GLUSR_USR_COMPANYNAME;
                        $onlineInquiry['sender_name'] = $item->SENDERNAME;
                        $onlineInquiry['sender_email'] = $item->SENDEREMAIL;
                        $onlineInquiry['sender_other_email'] = $item->EMAIL_ALT;
                        $onlineInquiry['sender_mobile'] = $item->MOB;
                        $onlineInquiry['sender_other_mobile'] = $item->MOBILE_ALT;
                        $onlineInquiry['sender_city'] = $item->ENQ_CITY;
                        $onlineInquiry['sender_state'] = $item->ENQ_STATE;
                        $onlineInquiry['sender_country'] = $item->COUNTRY_ISO;
                        $onlineInquiry['subject'] = $item->SUBJECT;
                        $onlineInquiry['notes'] = $item->ENQ_MESSAGE;
                        $onlineInquiry['product_name'] = $item->PRODUCT_NAME;
                        $onlineInquiry['product_qty'] = 1;
                        $onlineInquiry['financial_id'] = 1;
                        $onlineInquiry['inq_full_info'] = json_encode($item);
                        DB::table($tenantID . '.online_inquiry')->insert($onlineInquiry);
                        $recordCounter++;
                    }

                }
                /** Update Last Sync Indiamart Time */
                DB::table($tenantID . '.setting')->where('id', $settingInfo->id)->update(['last_indiamart_sync' => date('Y-m-d H:i:s')]);
            }
        }


    }

}
