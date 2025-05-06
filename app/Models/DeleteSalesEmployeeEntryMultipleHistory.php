<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteSalesEmployeeEntryMultipleHistory extends Model
{
    use HasFactory;
    protected $fillable=['old_id','old_created_by','old_updated_by','old_created_at','old_updated_at','created_by','updated_by','company_id','leader_id','employee_id'];
}
