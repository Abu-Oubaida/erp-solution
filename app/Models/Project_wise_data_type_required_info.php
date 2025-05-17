<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_wise_data_type_required_info extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','pdri_id','data_type_id','deadline','status','created_by','updated_by'];

    public function projectDocumentReq()
    {
        return $this->belongsTo(Project_document_requisition_info::class,'pdri_id','id');
    }
    public function archiveDataType()
    {
        return $this->belongsTo(VoucherType::class,'data_type_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function responsibleBy()
    {
        return $this->hasMany(Required_data_type_upload_responsible_user_info::class,'pwdtr_id','id');
    }

    public function followups()
    {
        return $this->hasMany(DocumentRequisitionFollowupDataTypeInfo::class,'data_type_id','id');
    }

    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id','id');
    }
}
