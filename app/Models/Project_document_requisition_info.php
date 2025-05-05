<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_document_requisition_info extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','project_id','message_subject','message_body','created_by','updated_by'];
}
