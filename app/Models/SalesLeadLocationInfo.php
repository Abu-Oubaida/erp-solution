<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeadLocationInfo extends Model
{
    use HasFactory;
    protected $fillable = ['location_name','status','created_by','updated_by','company_id'];
    public function createdByUser(){
        return $this->belongsTo(User::class,'created_by');
    }
    
}
