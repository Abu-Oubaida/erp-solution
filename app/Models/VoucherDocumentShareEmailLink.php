<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentShareEmailLink extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','share_id','share_document_id','status','shared_by'];

    public function ShareEmails()
    {
        return $this->hasMany(VoucherDocumentShareEmailList::class,'share_id');
    }
    public function voucherDocument()
    {
        return $this->belongsTo(VoucherDocument::class,'share_document_id');
    }

    public function sharedBy(){
        return $this->belongsTo(User::class,'shared_by');
    }
}
