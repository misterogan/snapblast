<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLeadHistory extends Model
{
    use HasFactory;
    protected $table = 'data_lead_histories';
    protected $fillable = [
        'data_lead_id',
        'title',
        'old_status',
        'new_status',
        'data_log',
        'note',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
