<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account_voucher extends Model
{
    protected $table = 'account_voucher_infos';
    use HasFactory;
    protected $fillable = ['id','company_id', 'voucher_type_id', 'voucher_number', 'voucher_date', 'file_count','remarks', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function VoucherType()
    {
        return $this->belongsTo(VoucherType::class,'voucher_type_id');
    }

    public function VoucherDocument()
    {
        return $this->hasMany(VoucherDocument::class,'voucher_info_id');
    }

    public function createdBY()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBY()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
