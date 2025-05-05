<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_wise_data_type_required_info extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','pdri_id','data_type_id','deadline','status','created_by','updated_by'];
}
