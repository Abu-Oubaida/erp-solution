<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer extends Model
{
    use HasFactory;
    protected $fillable = ['status','date','reference','from_company_id','from_project_id','to_company_id','to_project_id','narration','created_by','updated_by'];
    public function branchFrom()
    {
        return $this->belongsTo(Branch::class, 'from_project_id');
    }
    public function branchTo()
    {
        return $this->belongsTo(Branch::class, 'to_project_id');
    }
    public function companyFrom()
    {
        return $this->belongsTo(company_info::class, 'from_company_id');
    }
    public function companyTo()
    {
        return $this->belongsTo(company_info::class, 'to_company_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function specifications()
    {
        return $this->hasMany(Fixed_asset_transfer_with_spec::class, 'transfer_id','id');
    }
    public function documents()
    {
        return $this->hasMany(Fixed_asset_transfer_document::class, 'transfer_id','id');
    }
    public function specificationsByReference()
    {
        return $this->hasMany(Fixed_asset_transfer_with_spec::class,'reference','reference');
    }
}
