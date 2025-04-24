<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeadSourceInfo extends Model
{
    use HasFactory;
    protected $fillable = ['title','status','created_by','updated_by','company_id','parent_id','is_parent'];
}
