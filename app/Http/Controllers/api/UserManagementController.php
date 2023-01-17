<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserManagementController extends Controller
{
    public function list(Request $request)
    {
        $this->user();

        $data = User::with('company')->orderBy('id','DESC')->get();

        return DataTables::of($data)
            ->addColumn('role', function($row){
                return count($row->getRoleNames()) > 0 ? $row->getRoleNames()[0] : '';
            })
            ->addIndexColumn()->make(true);
    }

    public function submit(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'company_code' => 'required',
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $company = Company::where('company_code', $request->company_code)->first();
        if(!$company){
            $error = [
                'company_code' => [
                    'Company doesn\'t exists'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION,$error);
        }

        $data = [
            'row_status' => 'active',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->email
        ];

        $user = User::create($data);
        $user->assignRole($request->role_id);

        return $this->response(self::SUCCEED);
    }

    public function get(Request $request)
    {
        $user_login = $this->user();
        $user = User::with('company')
            ->where('email',$request->email)
            ->first();

        $user->role_id = $user->roles->first()->name;

        $data = [
            'user' => $user,
            'user_role' => $user_login->roles[0]->name
        ];

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $data);
    }

    public function update(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'company_code' => 'required',
            'role_id' => 'required',
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $company = Company::where('company_code', $request->company_code)->first();
        if(!$company){
            $error = [
                'company_code' => [
                    'Company doesn\'t exists'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION,$error);
        }

        $user_update = User::where('email', $request->email)->first();
        if(!$user_update){
            $error = [
                'email' => [
                    'User doesn\'t exists'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION,$error);
        }

        if(count($user_update->roles) > 0){
            if($user_update->roles->first()->name != $request->role_id){
                $user_update->syncRoles([]);
                $user_update->assignRole($request->role_id);
            }
        }else{
            $user_update->assignRole($request->role_id);
        }

        $user_update->name = $request->name;
        $user_update->company_id = $company->id;
        $company->updated_at = date('Y-m-d H:i:s');
        $company->updated_by = $user->email;
        $user_update->save();

        return $this->response(self::SUCCEED);
    }

    public function get_item(Request $request){
        $user = $this->user();
        $list_users = User::select('name','email')->where('row_status','active')->where('company_id',$user->company_id)->get();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $list_users);
    }

    public function get_info(){
        $user = $this->user();
        $data = User::where('id',$user->id)->with('company')->first();
        $data->role_id = $data->roles->first()->name;
        $data->join_date = date('l, d-m-Y H:m', strtotime($data->created_at));

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $data);
    }

    public function change_password(Request $request)
    {
        $user = $this->user();
        $validation = Validator::make($request->all(),[
            'old_password'=>'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION, $validation->messages() );
        }

        $obj_user= User::where('id','=',$user->id)->first();

        if (!$obj_user){
            $error = [
                'old_password' => [
                    'User not found'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION, $error);
        }

        if(Hash::check($request->old_password, $obj_user->password))
        {
            $obj_user->password = Hash::make($request->password);
            $obj_user->updated_at = date('Y-m-d h:m:s');
            $obj_user->updated_by = $user->email;

            $obj_user->save();
        }
        else
        {
            $error = [
                'old_password' => [
                    'Old password doesn\'t match'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION, $error);
        }

        return $this->response(self::SUCCEED, self::SUCCEED_CODE);
    }

    public function set_password(Request $request)
    {

    }
}
