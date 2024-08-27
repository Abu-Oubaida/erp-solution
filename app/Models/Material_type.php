<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material_type extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','name','code','status','description','created_by','updated_by'];
}
