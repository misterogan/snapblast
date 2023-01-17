<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $table = 'campaigns';

    protected $fillable = [
        'row_status',
        'ad_id',
        'campaign_name',
        'campaign_url',
        'target_lead',
        'company_id',
        'postback_id',
        'postback_trigger',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function leads(){
        return $this->hasMany(DataLead::class,'campaign_id');
    }

    public function open()
    {
        return $this->hasMany(DataLead::class,'campaign_id')->where('data_status','open');
    }

    public function pending()
    {
        return $this->hasMany(DataLead::class,'campaign_id')->where('data_status','pending');
    }

    public function followup()
    {
        return $this->hasMany(DataLead::class,'campaign_id')
            ->where('data_status','!=','open')
            ->where('data_status','!=','pending');
    }

    public function postback(){
        return $this->belongsTo(Postback::class,'postback_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'postback_id');
    }
}
