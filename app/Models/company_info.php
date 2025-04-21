<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class company_info extends Model
{
    use HasFactory;
    protected $fillable = ['status','company_name','company_type_id','contract_person_name','company_code','phone','contract_person_phone','email','location','remarks','logo','logo_sm','logo_icon','cover','created_by','updated_by','created_at','updated_at'];

    public function createdBY()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function companyType()
    {
        return $this->belongsTo(company_type::class,'company_type_id');
    }
    public function users()
    {
        return $this->hasMany(User::class,'company','id');
    }
    public function permissionUsers()
    {
        return $this->belongsToMany(User::class,'user_company_permissions','company_id','user_id');
    }
    public function fixedAssets()
    {
        return $this->hasMany(fixed_asset::class,'company_id');
    }

    public function projects()
    {
        return $this->hasMany(branch::class,'company_id');
    }

    public function departments()
    {
        return $this->hasMany(department::class,'company_id');
    }

    public function archiveStorage()
    {
        return $this->hasMany(Company_wise_archive_storage_info::class,'company_id');
    }
}
