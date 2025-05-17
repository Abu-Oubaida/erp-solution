<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequisitionFollowupDataTypeInfo extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','followup_id','data_type_id','created_by','updated_by'];

    public function requiredDataType()
    {
        return $this->belongsTo(Project_wise_data_type_required_info::class,'data_type_id','id');
    }
    public function followupMessage(){
        return $this->belongsTo(DocumentRequisitionFollowup::class,'followup_id','id');
    }

    public function responsibleUsers()
    {
        return $this->belongsToMany(User::class, 'document_requisition_followup_data_user_infos', 'followup_id', 'user_id');
    }
}
