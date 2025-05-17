<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequisitionFollowupDataUserInfo extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','followup_id','user_id','created_by','updated_by'];
}
