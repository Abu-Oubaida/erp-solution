<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationChangeHistory extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','transfer_user_id','new_designation_id','old_designation_id','transfer_by'];
}
