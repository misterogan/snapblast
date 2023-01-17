<?php

namespace App\Http\Controllers\api;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\DataLead;
use App\Models\DataLeadHistory;
use App\Models\Postback;
use App\Models\PostbackLog;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Jenssegers\Agent\Agent;
use GuzzleHttp\Client;

class DataLeadController extends Controller
{
    public function list(Request $request)
    {
        $user = $this->user();
        $query =  DataLead::with('pic')
            ->join('campaigns','campaigns.id','data_leads.campaign_id')
            ->where('campaigns.company_id',$user->company_id)
            ->orderBy('data_leads.id','DESC');
        if($request->data_status){
            $query->where('data_status',$request->data_status);
        }
        if($request->ad_id){
            $query->where('campaigns.ad_id',$request->ad_id);
        }
        if($request->assign_to){
            $assign_to = User::where('email','=',$request->assign_to)->first();
            $query->where('data_leads.assigned_to',$assign_to->id);
        }
        if($user->roles->first()->name == 'member'){
            $query->where('data_leads.assigned_to',$user->id);
        }
        $data = $query->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function detail(Request $request)
    {
        $this->user();

        $data = DataLead::with('pic')->with('campaign')->where('data_code',$request->data_code)->first();
        $data->last_update = date('l, d-m-Y H:m', strtotime($data->updated_at));
        $data->submitted_date = date('l, d-m-Y H:m', strtotime($data->created_at));

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $data);
    }

    public function histories(Request $request){
        $this->user();

        $data_lead = DataLead::where('data_code',$request->data_code)->first();
        $histories = DataLeadHistory::where('data_lead_id',$data_lead->id)->orderBy('id','DESC')->get();

        foreach ($histories as &$item){
            $item->created_date = date('l, d-m-Y H:m', strtotime($item->created_at));
        }
        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $histories);
    }

    public function assign(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'assign_to' => 'required|email',
            'data_code' => 'required',
            'due_date' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $to_user = User::where('email','=',$request->assign_to)->first();
        if(!$to_user) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, 'User Not Found');
        }

        $lead = DataLead::where('data_code',$request->data_code)->first();
        if(!$lead) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, 'Data Not Found');
        }

        $log = [
            'data_lead_id' => $lead->id,
            'title' => 'DATA ASSIGMENT',
            'old_status' => $lead->data_status ,
            'new_status' => self::DATA_STATUS_OPEN,
            'data_log' => $user->name . ' assigned to ' . $to_user->name,
            'note' => $user->name . ' assigned to ' . $to_user->name .'<br>Due date : ' .$request->due_date,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->name
        ];

        $lead->data_status = self::DATA_STATUS_OPEN;
        $lead->assignee = $user->id;
        $lead->assigned_to = $to_user->id;
        $lead->due_date = $request->due_date;
        $lead->updated_at = date('Y-m-d H:i:s');
        $lead->updated_by = $user->name;
        $lead->save();

        DataLeadHistory::create($log);

        return $this->response(self::SUCCEED);
    }

    public function update_status(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'status' => 'required',
            'data_code' => 'required',
            'note' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, '','Validation errors, please check again.');
        }

        $data = DataLead::where('data_code',$request->data_code)->first();
        if(!$data){
            if($validation->fails()) {
                return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, '','Data Not Found');
            }
        }

        $log = [
            'data_lead_id' => $data->id,
            'title' => 'STATUS CHANGED',
            'old_status' => $data->data_status ,
            'new_status' => $request->status,
            'data_log' => $user->name . ' updated status from ' . $data->data_status . ' to ' . $request->status,
            'note' => $request->note,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->name
        ];

        $data->data_status = $request->status;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->updated_by = $user->email;

        $data->save();

        DataLeadHistory::create($log);

        return $this->response(self::SUCCEED);
    }

    public function submit(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'campaign_code' => 'required',
            'first_name'    => 'required',
            'phone_number'  => 'required',
            'email'         => 'required',
        ]);

       /* print_r($request);
        die();*/
        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, '','Validation errors, please check again.');
        }

        $campaign = Campaign::where('ad_id',$request->campaign_code)->first();
        $data_code = strtoupper(Str::random(8));
        $data_lead = DataLead::select('data_code')->where('data_code',$data_code)->first();

        if(!$data_lead){

            $agent = new Agent();
            $device_name = '';
            if ($agent->isDesktop()) {
                $device_name = 'desktop';
            } elseif ($agent->isTablet()) {
                $device_name = 'tablet';
            } elseif ($agent->isMobile()) {
                $device_name = 'mobile';
            }

            $ip         = getHostByName(getHostName());
            $data       = \Location::get($ip);

            if($request->last_name != ""){
                $fullname   = $request->first_name." ".$request->last_name;
            }else{
                $fullname   =  $request->first_name." ".$request->first_name;
            }

            $company = Company::where('id',$request->company_id)->first();
            $data = DataLead::create([
                'data_code'         => $data_code,
                'campaign_id'       => $campaign->id,
                'full_name'         => $fullname,
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name,
                'dob'               => $request->dob,
                'phone_number'      => $request->phone_number,
                'email'             => $request->email,
                'company_name'      => $company->company_name,
                'message'           => $request->message,
                'utm_source'        => $request->utm_source,
                'utm_medium'        => $request->utm_medium,
                'utm_banner'        => $request->utm_banner,
                'utm_term'          => $request->utm_term,
                'utm_campaign'      => $request->utm_campaign,
                'device'            => $agent->device(),
                'device_name'       => $device_name,
                'os'                => $agent->platform(),
                'browser'           => $agent->browser(),
                'lat'               => $data->latitude ?? '',
                'long'              => $data->longitude ?? '',
                'referer'           => $request->referer,
                'ip'                => $ip,
                'sub1'              => $request->sub1,
                'sub2'              => $request->sub2,
                'sub3'              => $request->sub3,
                'sub4'              => $request->sub4,
                'sub5'              => $request->sub5,
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => $user->email?? '',
            ]);

            //postback log
            Utils::postback_log($campaign->postback_id,$data->id,$campaign->postback_trigger);

            $client = new Client(['base_uri' => 'https://admin2.ctree.id']);

            $response = $client->request('POST', '/api/website/contact/add', ['form_params' => [
                'name'              => $fullname,
                'email'             => $request->email,
                'phone_number'      => $request->phone_number,
                'message'           => $request->message,
                'utm_source'        => $request->utm_source,
                'utm_medium'        => $request->utm_medium,
                'utm_campaign'      => $request->utm_campaign,
                'url_referer'       => $request->getHttpHost(),
                'device'            => $agent->device(),
                'device_name'       => $device_name,
                'os'                => $agent->platform(),
                'ip'                => $ip,
                'browser'           => $agent->browser(),
                'company_name'      => $company->company_name,
            ]]);

            $response = (string) $response->getBody();
            $response = json_decode($response);
            $status = $response->status;

            if($status == 'success'){
                return $this->response(self::SUCCEED);
            }

        }

    }

}
