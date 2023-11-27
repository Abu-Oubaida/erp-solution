<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentShareLink extends Model
{
    use HasFactory;
    protected $fillable = ['share_id','share_type','share_document_id','status','shared_by'];

    public function sharedBy()
    {
        return $this->belongsTo(User::class,'shared_by');
    }
    public function voucherDocument()
    {
        return $this->belongsTo(VoucherDocument::class,'share_document_id');
    }
}
