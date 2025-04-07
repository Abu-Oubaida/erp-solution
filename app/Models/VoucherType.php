<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','status','voucher_type_title', 'code', 'remarks','created_by','updated_by'];

    public function createdBY()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBY()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function accountVoucher()
    {
        return $this->hasOne(Account_voucher::class,'voucher_type_id');
    }
    public function voucherWithUsers(){
        // return $this->hasMany(Voucher_type_permission_user::class,'voucher_type_id');
        return $this->belongsToMany(User::class,'voucher_type_permission_user','voucher_type_id','user_id');
    }
    public function archiveDocumentInfos(){
        return $this->hasMany(Account_voucher::class,'voucher_type_id');
    }
    public function archiveDocuments()
    {
        return $this->hasManyThrough(VoucherDocument::class,Account_voucher::class,'voucher_type_id','voucher_info_id','id','id');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id');
    }
}
