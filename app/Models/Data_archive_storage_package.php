<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_archive_storage_package extends Model
{
    use HasFactory;

    protected $fillable = ['status','package_name','package_size','package_type','created_by','updated_by'];

    public function useesCompany(){
        return $this->belongsToMany(company_info::class,Company_wise_archive_storage_info::class,'storage_package_id','company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
