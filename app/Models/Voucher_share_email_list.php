<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher_share_email_list extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','share_link_id','email'];

    public function shareLink()
    {
        return $this->belongsTo(Voucher_share_email_link::class,'share_link_id','id');
    }
}
