<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account_voucher extends Model
{
    protected $table = 'account_voucher_infos';
    use HasFactory;

    public function VoucherType()
    {
        return $this->hasMany(VoucherType::class,'voucher_type_id');
    }
}
