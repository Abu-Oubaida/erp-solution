<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer_edit_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id','status','date','reference','old_reference','from_company_id','from_project_id','to_company_id','to_project_id','narration','created_by','updated_by','old_created_at','old_updated_at','modified_by'];
}
