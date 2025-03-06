<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher_share_email_link extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','share_id','share_voucher_id','status','shared_by'];

    public function shareVoucher()
    {
        return $this->belongsTo(Account_voucher::class , 'share_voucher_id','id');
    }

    public function ShareEmails()
    {
        return $this->hasMany(Voucher_share_email_list::class,'share_link_id','id');
    }
}
