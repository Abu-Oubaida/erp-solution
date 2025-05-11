<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_wise_data_type_required_infos_delete_history extends Model
{
    use HasFactory;

    protected $fillable = ['old_id','company_id','pdri_id','data_type_id','deadline','status','old_created_by','old_updated_by','old_created_at','old_updated_at','created_by','updated_by'];
}
