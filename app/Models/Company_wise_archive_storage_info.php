<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_wise_archive_storage_info extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','storage_package_id','status','storage_size','created_by','updated_by'];

    public function company()
    {
        return $this->belongsTo(company_info::class, 'company_id', 'id');
    }
    public function package()
    {
        return $this->belongsTo(Data_archive_storage_package::class, 'storage_package_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updateddBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
