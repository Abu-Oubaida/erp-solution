<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentShareEmailList extends Model
{
    use HasFactory;
    protected $fillable = ['share_id','email'];

    public function ShareLink()
    {
        return $this->belongsTo(VoucherDocumentShareEmailLink::class,'id');
    }
}
