<?php

namespace App\Models;

use Database\Seeders\CompanyInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_requisition_info extends Model
{
    use HasFactory;

    protected $fillable = ['reference_number','status','sander_company_id','receiver_company_id','deadline','number_of_document','subject','description','created_by','updated_by'];

    public function receiverUser()
    {
        return $this->hasMany(Document_requisition_receiver_user::class, 'document_requisition_id','id');
    }

    public function sanderCompany()
    {
        return $this->belongsTo(company_info::class, 'sander_company_id','id');
    }

    public function receiverCompany()
    {
        return $this->belongsTo(company_info::class, 'receiver_company_id','id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function receivers()
    {
        return $this->belongsToMany(User::class, 'document_requisition_receiver_user', 'document_requisition_id', 'user_id');
    }

    public function attachmentInfos()
    {
        return $this->hasMany(Document_requisition_attested_document_info::class, 'document_requisition_id','id');
    }

}
