<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_opening_balance_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id', 'date', 'references', 'ref_type_id', 'branch_id', 'company_id', 'status', 'created_by', 'updated_by', 'narration', 'old_created_at', 'old_updated_at', 'deleted_by'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class, 'company_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function withSpecifications()
    {
        return $this->hasMany(Fixed_asset_opening_with_spec::class, 'opening_asset_id');
    }
    public function refType()
    {
        return $this->belongsTo(Op_reference_type::class,'ref_type_id');
    }
    public function attestedDocuments()
    {
        return $this->hasMany(Fixed_asset_opening_balance_document::class, 'opening_asset_id');
    }
}
