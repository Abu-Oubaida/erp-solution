<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer_with_spec extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'transfer_id', 'reference', 'asset_id', 'spec_id', 'rate', 'qty', 'purpose', 'remarks', 'created_by', 'updated_by'];
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
}
