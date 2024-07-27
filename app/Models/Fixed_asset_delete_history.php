<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_asset_id','recourse_code', 'materials_name', 'rate', 'unit', 'depreciation', 'status', 'remarks', 'company_id', 'created_by', 'updated_by','old_created_time','old_updated_time','old_created_by','old_updated_by'];
}
