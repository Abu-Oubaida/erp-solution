<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Required_data_type_upload_responsible_user_info extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','pwdtr_id','user_id','created_by','updated_by'];

    public function projectWiseDataType()
    {
        return $this->belongsTo(Project_wise_data_type_required_info::class,'pwdtr_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id','id');
    }
}
