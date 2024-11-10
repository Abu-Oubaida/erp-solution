<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_asset_transfer_document extends Model
{
    use HasFactory;
    protected $fillable = ['from_company_id','to_company_id','transfer_id','document_name','document_url','created_by','updated_by'];

    public function companyTo()
    {
        return $this->belongsTo(company_info::class,'to_company_id','id');
    }
    public function companyFrom()
    {
        return $this->belongsTo(company_info::class,'from_company_id','id');
    }
    public function opening_asset()
    {
        return $this->belongsTo(Fixed_asset_transfer::class,'transfer_id','id');
    }
}
