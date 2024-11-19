<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id', 'date', 'reference', 'from_company_id','to_company_id', 'from_branch_id','to_branch_id', 'status', 'created_by', 'updated_by', 'narration', 'old_created_at', 'old_updated_at', 'deleted_by'];

    public function branchFrom()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }
    public function branchTo()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
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
    public function gpSpecifications()
    {
        return $this->hasMany(Fixed_asset_transfer_with_spec::class, 'transfer_id','old_id');
    }
    public function attestedDocuments()
    {
        return $this->hasMany(Fixed_asset_transfer_document::class, 'transfer_id','old_id');
    }
}
