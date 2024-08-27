<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentShareEmailList extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','share_id','email'];

    public function ShareLink()
    {
        return $this->belongsTo(VoucherDocumentShareEmailLink::class,'id');
    }
}
