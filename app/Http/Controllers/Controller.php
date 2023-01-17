<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const SUCCEED = "success";
    protected const ERROR = "error";
    protected const FAILED = "failed";

    //validation
    protected const SUCCEED_CODE = 200;
    protected const ERROR_CODE_VALIDATION = 201;
    protected const ERROR_EXPIRED = 202;
    protected const ERROR_AUTHENTICATION = 401;
    protected const ERROR_NOT_FOUND = 404;

    //lead data status
    protected const DATA_STATUS_PENDING = 'pending';
    protected const DATA_STATUS_OPEN = 'open';
    protected const DATA_STATUS_SUCCEED = 'succeed';
    protected const DATA_STATUS_FAILED = 'failed';
    protected const DATA_STATUS_INVALID = 'invalid';
    protected const DATA_STATUS_REJECTED = 'rejected';
    protected const DATA_STATUS_BUSY = 'busy';
    protected const DATA_STATUS_EXPIRED = 'expired';
    protected const DATA_STATUS_MISSED = 'missed';

    protected function response($status= "success", $code = 200, $data = null, $message = "")
    {
        return response()->json([
            'status'=>$status,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], 200);
    }

    protected function user (){
        if(auth('sanctum')->check()){
            return auth('sanctum')->user();
        }

        abort(401);
    }
}
