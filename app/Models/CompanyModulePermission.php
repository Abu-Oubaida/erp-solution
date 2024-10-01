<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModulePermission extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'module_parent_id', 'module_id','created_by','updated_by'];


}
