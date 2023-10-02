<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocument extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_info_id','document','filepath','created_by','updated_by'];

    public function accountVoucherInfo()
    {
        return $this->belongsTo(Account_voucher::class,'voucher_info_id');
    }
}
