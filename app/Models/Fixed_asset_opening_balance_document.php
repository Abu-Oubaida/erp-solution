<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_opening_balance_document extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','opening_asset_id','document_name','document_url','created_by','updated_by'];

    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id','id');
    }
    public function opening_asset()
    {
        return $this->belongsTo(Fixed_asset_opening_balance::class,'opening_asset_id','id');
    }
}
