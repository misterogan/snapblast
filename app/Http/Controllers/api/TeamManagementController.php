<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TeamManagementController extends Controller
{
    public function list(Request $request)
    {
        $user = $this->user();
        $data = User::with('company')->where('company_id',$user->company_id)->orderBy('id','DESC')->get();

        return DataTables::of($data)
            ->addColumn('role', function($row){
                return count($row->getRoleNames()) > 0 ? $row->getRoleNames()[0] : '';
            })
            ->addIndexColumn()->make(true);
    }

    public function send_invitation(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required',
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $cache_invitation_token = Str::random(16);
        $user_data = [
            'row_status' => 'pending',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
            'company_id' => $user->company_id,
            'invitation_token' => $cache_invitation_token,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->email
        ];

        $user_created = User::create($user_data);
        $user_created->assignRole($request->role_id);

        Cache::put($cache_invitation_token, $request->email,now()->addDay(3));

        //todo send email

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $user_created);
    }

    public function validate_invitation(Request $request){
        $validation = Validator::make($request->all(), [
            'invitation_token' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_NOT_FOUND, 'Link Not Valid');
        }

        $user = User::where('invitation_token', $request->invitation_token)->first();
        if(!$user){
            return $this->response(self::ERROR,self::ERROR_NOT_FOUND, 'Link Not Valid');
        }

        $email = Cache::get($request->invitation_token);
        if(!$email){
            return $this->response(self::ERROR,self::ERROR_EXPIRED, 'Link Expired');
        }

        if($email != $user->email || $user->row_status != 'pending'){
            return $this->response(self::ERROR,self::ERROR_NOT_FOUND, 'Link Not Valid');
        }

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, (new UserResource($user)));
    }

    public function confirm_invitation(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'invitation_token'=>'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION, $validation->messages() );
        }

        $user= User::where('invitation_token','=',$request->invitation_token)->first();
        if (!$user){
            $error = [
                'name' => [
                    'User not found'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION, $error);
        }
        $user->row_status = 'active';
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->updated_at = date('Y-m-d h:m:s');
        $user->updated_by = $user->email;

        $user->save();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }
}
