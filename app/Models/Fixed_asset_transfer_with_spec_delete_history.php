<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer_with_spec_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id', 'date', 'transfer_id', 'reference', 'asset_id', 'spec_id', 'rate', 'qty', 'purpose', 'remarks', 'created_by', 'updated_by', 'old_created_at', 'old_updated_at', 'deleted_by'];

    public function asset()
    {
        return $this->belongsTo(Fixed_asset::class, 'asset_id');
    }
    public function fixed_asset_transfer()
    {
        return $this->belongsTo(Fixed_asset_transfer::class, 'transfer_id');
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
