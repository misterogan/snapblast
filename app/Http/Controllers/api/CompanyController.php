<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        $this->user();
        $data = Company::withCount('campaign')
            ->withCount('user')
            ->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function list_item(Request $request)
    {
        $this->user();
        $data = Company::get()->where('row_status','active');
        $r = [];
        foreach ($data as $item){
            $c = new Company();
            $c->company_code = $item->company_code;
            $c->company_name = $item->company_name;
            array_push(
                $r,$c
            );
        }

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $r);
    }

    public function submit(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required',
            'category_code' => 'required',
            'pic_name' => 'required',
            'phone_number' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $data = [
            'row_status' => 'active',
            'company_code' => Str::upper(Str::random(8)),
            'company_name' => $request->company_name,
            'email' =>$request->email,
            'phone_number' => $request->phone_number,
            'pic_name' => $request->pic_name,
            'category_code' => $request->category_code,
            'api_token' => Str::upper(Str::random(32)),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->email
        ];

        Company::insert($data);

        return $this->response(self::SUCCEED);
    }

    public function get(Request $request)
    {
        $this->user();
        $company = Company::where('company_code', $request->company_code)->first();

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $company);
    }

    public function update(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'company_code' => 'required',
            'company_name' => 'required',
            'email' => 'required',
            'category_code' => 'required',
            'pic_name' => 'required',
            'phone_number' => 'required'
        ]);

        if($validation->fails()) {
            return $this->response(self::ERROR,self::ERROR_CODE_VALIDATION, $validation->errors());
        }

        $company = Company::where('company_code',$request->company_code)->first();
        if(!$company){
            $error = [
                'email' => [
                    'Company doesn\'t exists'
                ]
            ];
            return $this->response(self::ERROR, self::ERROR_CODE_VALIDATION,$error);
        }

        $company->row_status = 'active';
        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone_number = $request->phone_number;
        $company->pic_name = $request->pic_name;
        $company->category_code = $request->category_code;
        $company->updated_at = date('Y-m-d H:i:s');
        $company->updated_by = $user->email;

        $company->save();

        return $this->response(self::SUCCEED);
    }

    public function list_categories()
    {
        $categories = [
            ['code' => 'arts_entertainment', 'value' => 'Arts & Entertainment'],
            ['code' => 'autos_vehicles', 'value' => 'Autos & Vehicles'],
            ['code' => 'bank', 'value' => 'Bank'],
            ['code' =>'beauty_fitness','value' => 'Beauty & Fitness'],
            ['code' =>'books_literature','value' => 'Books & Literature'],
            ['code' =>'business_industrial','value' => 'Business & Industrial'],
            ['code' =>'computers_electronics','value' => 'Computers & Electronics'],
            ['code' =>'finance','value'=>'Finance'],
            ['code' =>'food_drink','value' => 'Food & Drink'],
            ['code' =>'games','value'=> 'Games'],
            ['code' =>'health','value'=> 'Health'],
            ['code' =>'home_garden','value' => 'Home & Garden'],
            ['code' =>'insurance','value' => 'Insurance'],
            ['code' =>'internet_telecom','value' => 'Internet & Telecom'],
            ['code' =>'jobs_education','value' => 'Jobs & Education'],
            ['code' =>'law_government','value' => 'Law & Government'],
            ['code' =>'lifestyle','value' => 'Lifestyle'],
            ['code' =>'news','value'=> 'News'],
            ['code' =>'online_communities','value' => 'Online Communities'],
            ['code' =>'people_society','value' => 'People & Society'],
            ['code' =>'pets_animal','value' => 'Pets & Animals'],
            ['code' =>'real_estate','value' => 'Real Estate'],
            ['code' =>'reference','value'=>'Reference'],
            ['code' =>'science','value' => 'Science'],
            ['code' =>'shopping','value' => 'Shopping'],
            ['code' =>'sports','value'=> 'Sports'],
            ['code' =>'travel','value' => 'Travel'],
            ['code' =>'other','value' => 'Other']
        ];

        return $this->response(self::SUCCEED, self::SUCCEED_CODE, $categories);
    }
}
