<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pest_history extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','status','message','type','disk_name','to','from','document_type','created_by'];
}
