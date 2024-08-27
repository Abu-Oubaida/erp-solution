<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSalaryCertificateData extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','status','user_id','financial_yer_from','financial_yer_to','basic','house_rent','conveyance','medical_allowance','festival_bonus','others','remarks','created_by','updated_by'];

    public function userInfo()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updateddBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
