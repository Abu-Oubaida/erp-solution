<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_requisition_receiver_user extends Model
{
    use HasFactory;
    protected $table = 'document_requisition_receiver_user';
    protected $fillable = ['document_requisition_id','user_id','reply_status','created_by','updated_by'];

    public function documentRequisitionInfo()
    {
        return $this->belongsTo(Document_requisition_info::class, 'document_requisition_id','id');
    }
}
