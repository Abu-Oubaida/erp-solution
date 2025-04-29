<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeadSourceInfo extends Model
{
    use HasFactory;
    protected $fillable = ['title','status','created_by','updated_by','company_id','parent_id','is_parent'];
    public function parentTitle()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function createdByUser(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function getCompanyName(){
        return $this->belongsTo(company_info::class,'company_id');
    }
}
