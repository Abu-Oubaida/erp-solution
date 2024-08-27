<?php

namespace App\Models;

use Database\Seeders\CompanyInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','branch_name','branch_type', 'status','address', 'remarks','created_by','updated_by'];

    public function getUsers()
    {
        return $this->hasMany(User::class,'branch_id');
    }
    public function branchType()
    {
        return $this->belongsTo(BranchType::class,'branch_type');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id');
    }
}
