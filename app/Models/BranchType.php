<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchType extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','status','title','code','remarks','created_by','updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function getBranch()
    {
        return $this->hasMany(branch::class,'branch_type');
    }
}
