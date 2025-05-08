<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Required_data_type_upload_responsible_user_infos_delete_history extends Model
{
    use HasFactory;
    protected $table = 'req_data_type_upload_res_user_delete_histories';
    protected $fillable = ['old_id','company_id','pwdtr_id','user_id','old_created_by','old_updated_by','old_created_at','old_updated_at','created_by','updated_by'];
}
