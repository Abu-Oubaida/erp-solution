<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequisitionFollowup extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','subject','description','notification','email','created_by','updated_by'];

    public function followupDataTypes()
    {
        return $this->hasMany(DocumentRequisitionFollowupDataTypeInfo:: class, 'followup_id', 'id');
    }
}
