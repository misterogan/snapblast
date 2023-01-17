<?php
namespace App\Helpers;

use App\Models\Campaign;
use App\Models\Postback;
use App\Models\PostbackLog;

class Utils {

    public static function get_ip() {

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
        return $ip;
    }
    public function postback_log($postback_id,$data_lead_id,$postback_trigger){

        $postback = Postback::where('id',$postback_id)->first();
        $now      = date('Y-m-d H:i:s');

        $data = PostbackLog::create([
            'postback_id'       => $postback_id,
            'data_lead_id'      => $data_lead_id,
            'param'             => $postback->postback_params,
            'created_at'        => $now,
            'updated_at'        => $now,
            'postback_status'   => $postback_trigger,
        ]);
        if($data){
            return $data;
        }
    }
}
