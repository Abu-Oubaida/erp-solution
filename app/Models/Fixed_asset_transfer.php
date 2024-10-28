<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer extends Model
{
    use HasFactory;
    protected $fillable = ['status','date','reference','from_company_id','from_project_id','to_company_id','to_project_id','narration','created_by','updated_by'];
}
