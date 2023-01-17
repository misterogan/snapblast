<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostbackLog extends Model
{
    use HasFactory;
    protected $table = 'postback_logs';
    protected $fillable = [
        'postback_status',
        'postback_id',
        'data_lead_id',
        'param',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
