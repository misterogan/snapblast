<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Postback;
use App\Models\PostbackLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CampaignController extends Controller
{
    public function list(Request $request)
    {
        $user=$this->user();
        if(!$user){
            return $this->response(self::ERROR, self::ERROR_AUTHENTICATION);
        }
        $query =  Campaign::withCount('leads')->where('company_id',$user->company_id)->orderBy('id','DESC');
        if($request->param1){
            $query->where('campaign_name','ilike','%'.$request->param1.'%');
        }
        $data = $query->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function submit(Request $request){
        $user = $this->user();
        if(!$user){
            return $this->response(self::ERROR, self::ERROR_AUTHENTICATION);
        }

        $validation = Validator::make($request->all(), [
            'campaign_name' => 'required',
            'campaign_url' => 'required',
            'target_lead' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }
        $postback = Postback::where('postback_code',$request->postback_id)->first();
        $postback_id = null;
        if($postback){
            $postback_id = $postback->id;
        }

        DB::beginTransaction();
        try{
            $data = [
                'row_status' => 'active',
                'ad_id' => Str::upper(Str::random(8)),
                'campaign_name' => $request->campaign_name,
                'campaign_url' => $request->campaign_url,
                'target_lead' =>$request->target_lead,
                'company_id' => $user->company_id,
                'postback_id' => $postback_id,
                'postback_trigger' => $request->postback_trigger,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $user->email,
            ];
            $campaign = Campaign::insert($data);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }

        return $this->response(self::SUCCEED);
    }

    public function update(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'ad_id' => 'required',
            'campaign_name' => 'required',
            'campaign_url' => 'required',
            'target_lead' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $campaign = Campaign::where('ad_id',$request->ad_id)->first();
        $postback = Postback::where('postback_code',$request->postback_id)->first();
        if($postback){
            $campaign->postback_id = $postback->id;
        }
        $campaign->campaign_name = $request->campaign_name;
        $campaign->campaign_url = $request->campaign_url;
        $campaign->target_lead = $request->target_lead;
        $campaign->postback_trigger = $request->postback_trigger;
        $campaign->updated_at = date('Y-m-d H:i:s');
        $campaign->updated_by = $user->email;
        $campaign->save();

        return $this->response(self::SUCCEED);
    }

    public function get(Request $request)
    {
        $this->user();
        $data = Campaign::withCount('leads')
            ->withCount('open')
            ->withCount('pending')
            ->withCount('followup')
            ->with('postback')
            ->where('ad_id',$request->ad_id)->first();

        $leads_rate =0;
        $open_rate = 0;
        $pending_rate = 0;
        $followup_rate = 0;
        $leads_count = $data->leads_count;

        if($leads_count > 0){
            $leads_rate = number_format($leads_count / $data->target_lead * 100,0);
            $open_rate = number_format($data->open_count / $leads_count * 100, 0);
            $pending_rate = number_format($data->pending_count / $leads_count * 100,0);
            $followup_rate = number_format($data->followup_count / $leads_count * 100,0);
        }

        $data->leads_rate = $leads_rate;
        $data->open_rate = $open_rate;
        $data->pending_rate = $pending_rate;
        $data->followup_rate = $followup_rate;

        $data->last_update = date('l, d-m-Y H:m', strtotime($data->updated_at));

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $data);
    }

    public function get_item(Request $request)
    {
        $user = $this->user();
        $data = Campaign::select('campaign_name','ad_id')->where('row_status','active')
            ->where('company_id',$user->company_id)
            ->get();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $data);
    }
}
