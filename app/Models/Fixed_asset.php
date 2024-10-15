<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset extends Model
{
    use HasFactory;
    protected $table = 'fixed_assets';
    protected $fillable = [ 'recourse_code', 'materials_name', 'rate', 'unit', 'depreciation', 'status', 'remarks', 'company_id', 'created_by', 'updated_by'];
    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id');
    }
    public function specifications()
    {
        return $this->hasMany(fixed_asset_specifications::class,'fixed_asset_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
