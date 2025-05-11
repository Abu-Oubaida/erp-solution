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

    public function fixedAssets()
    {
        $withRef = Fixed_asset_opening_with_spec::with(['fixed_asset_opening_balance'])->whereHas('fixed_asset_opening_balance',function ($query){
            $query->where('branch_id',$this->id);
        })->get();
    }

    public function documentRequiredInfo()
    {
        return $this->hasOne(Project_document_requisition_info::class,'project_id','id');
    }
}
