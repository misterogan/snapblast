<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    protected $fillable = [
        'row_status',
        'company_code',
        'company_name',
        'email',
        'pic_name',
        'phone_number',
        'category_code',
        'api_token',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function user(){
        return $this->hasOne(User::class, 'company_id');
    }

    public function campaign(){
        return $this->hasMany(Campaign::class, 'company_id');
    }
}
