<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class filemanager_permission extends Model
{
    use HasFactory;
    protected $table = 'file_manager_permissions';
    protected $fillable = ['company_id','status', 'user_id', 'dir_name', 'permission_type', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(company_info::class, 'company_id', 'id');
    }
}
