<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Postback;
use App\Models\PostbackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PostbackController extends Controller
{
    public function list(Request $request)
    {
        $user = $this->user();
        $data = Postback::with('company')
            ->withCount('campaign')
            ->where('company_id',$user->company_id)
            ->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function get_item(Request $request)
    {
        $user = $this->user();
        $data = Postback::with('company')
            ->withCount('campaign')
            ->where('company_id',$user->company_id)
            ->select('postback_code','postback_name')
            ->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function submit(Request $request)
    {

        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'postback_name' => 'required',
            'postback_url' => 'required',
            'param' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $parameters = $request->param;
        $data_field = array();
        foreach ($parameters as $item){
//            if($item->has_error){
//                return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION);
//            }
            array_push($data_field,$item);
        }


        DB::beginTransaction();
        try{
            $data_insert = array(
                'row_status'        =>'active',
                'postback_name'     => $request->postback_name,
                'postback_code'     => Str::random(8),
                'company_id'        => $user->company_id,
                'postback_url'      => $request->postback_url,
                'postback_params'   => json_encode($data_field),
                'created_at'        => date('Y-m-d h:m:s'),
                'created_by'        => $user->email
            );
            $postback = Postback::insert($data_insert);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }


        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }

    public function edit(Request $request){
        $postback = Postback::where('postback_code', $request->postback_code)->first();
        $postback->param = json_decode($postback->postback_params);

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $postback);
    }

    public function update(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'postback_code' => 'required',
            'postback_name' => 'required',
            'postback_url'  => 'required',
            'param'         => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $postback = Postback::where('postback_code',$request->postback_code)->first();
        if(!$postback){
            return $this->response(self::ERROR,self::ERROR_NOT_FOUND, '', "Data not found!");
        }

        $parameters = $request->param;
        $data_field = array();
        foreach ($parameters as $item){
            array_push($data_field,$item);
        }

        $postback->postback_name = $request->postback_name;
        $postback->postback_url = $request->postback_url;
        $postback->postback_params = json_encode($data_field);
        $postback->updated_at = date('Y-m-d h:m:s');
        $postback->updated_by = $user->email;

        $postback->save();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }




}
