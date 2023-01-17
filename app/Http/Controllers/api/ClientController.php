<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function submit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'campaign_code' => 'required',
            'token' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        //todo
        //insert to DB data_leads
        //insert into db data_lead_histories
        //call postback & log if any

        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }
}
