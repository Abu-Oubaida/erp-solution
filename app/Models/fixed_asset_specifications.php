<?php

namespace App\Models;

use Faker\Provider\ar_EG\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fixed_asset_specifications extends Model
{
    use HasFactory;
    protected $table = 'fixed_asset_specifications';
    protected $fillable = [ 'fixed_asset_id', 'status', 'specification', 'company_id', 'created_by', 'updated_by'];

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
    public function fixed_asset()
    {
        return $this->belongsTo(Fixed_asset::class, 'fixed_asset_id');
    }
}
