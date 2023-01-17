<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postback extends Model
{
    use HasFactory;
    protected $table = 'postbacks';
    protected $fillable = [
        'row_status',
        'postback_code',
        'postback_name',
        'postback_url',
        'postback_params',
        'company_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function campaign(){
        return $this->hasOne(Campaign::class,'postback_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
}
