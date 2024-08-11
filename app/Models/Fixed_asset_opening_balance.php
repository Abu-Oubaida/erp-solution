<?php

namespace App\Models;

use Faker\Provider\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_opening_balance extends Model
{
    use HasFactory;
    protected $table = 'fixed_asset_opening_balances';
    protected $fillable = ['date', 'references','ref_type_id', 'branch_id', 'company_id', 'status', 'created_by', 'updated_by', 'narration'];

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
}
