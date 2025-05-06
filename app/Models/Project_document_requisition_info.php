<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Project_document_requisition_info extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','project_id','message_subject','message_body','created_by','updated_by'];

    public function project(){
        return $this->belongsTo(branch::class,'project_id','id');
    }
    public function dataTypeRequired()
    {
        return $this->hasMany(Project_wise_data_type_required_info::class,'pdri_id','id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id','id');
    }
}
