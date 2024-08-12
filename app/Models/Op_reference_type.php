<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Op_reference_type extends Model
{
    use HasFactory;
    protected $fillable = [ 'company_id','name', 'code', 'description', 'status', 'created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function fixedAssetOpening()
    {
        return $this->hasMany(Fixed_asset_opening_balance::class,'ref_type_id','id');
    }
}
