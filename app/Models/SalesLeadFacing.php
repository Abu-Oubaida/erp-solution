<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeadFacing extends Model
{
    use HasFactory;
    protected $fillable = ['title','status','created_by','updated_by','company_id'];
}
