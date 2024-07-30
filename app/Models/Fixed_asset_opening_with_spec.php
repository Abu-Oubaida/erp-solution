<?php

namespace App\Models;

use Faker\Provider\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_opening_with_spec extends Model
{
    use HasFactory;
    protected $table = ' fixed_asset_opening_with_specs';
    protected $fillable = ['date', 'opening_asset_id', 'references', 'company_id', 'asset_id', 'spec_id', 'rate', 'qty', 'purpose', 'remarks', 'created_by', 'updated_by'];

    public function asset()
    {
        return $this->belongsTo(Fixed_asset::class, 'asset_id');
    }
    public function opening_asset()
    {
        return $this->belongsTo(Fixed_asset::class, 'opening_asset_id');
    }
    public function specification()
    {
        return $this->belongsTo(fixed_asset_specifications::class,'spec_id');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class, 'company_id');
    }
}
