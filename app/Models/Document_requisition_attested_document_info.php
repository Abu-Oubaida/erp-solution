<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_requisition_attested_document_info extends Model
{
    use HasFactory;

    protected $fillable = ['document_requisition_id','document_title','document_upload_status','created_by'];

    public function attestedDocument()
    {
        return $this->hasMany(Document_requisition_attested_document::class, 'attested_document_info_id','id');
    }
}
