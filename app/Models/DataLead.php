<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLead extends Model
{
    use HasFactory;
    protected $table = 'data_leads';
    protected $fillable = [
        'data_code',
        'row_status',
        'data_status',
        'campaign_id',
        'full_name',
        'first_name',
        'last_name',
        'dob',
        'phone_number',
        'email',
        'company_name',
        'message',
        'utm_source',
        'utm_medium',
        'utm_banner',
        'utm_term',
        'utm_campaign',
        'device',
        'device_name',
        'os',
        'browser',
        'lat',
        'long',
        'referer',
        'ip',
        'sub1',
        'sub2',
        'sub3',
        'sub4',
        'sub5',
        'assignee',
        'assigned_to',
        'due_date',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class,'campaign_id');
    }

    public function pic(){
        return $this->belongsTo(User::class,'assigned_to');
    }
}
