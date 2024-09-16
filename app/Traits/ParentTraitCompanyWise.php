<?php

namespace App\Traits;

use App\Models\BloodGroup;
use App\Models\branch;
use App\Models\BranchType;
use App\Models\company_info;
use App\Models\department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait ParentTraitCompanyWise
{
    use AuthTrait;
    protected function getUser()
    {
        $object = User::with(['permissions','department','designation','branch','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }
    protected function getBranch()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }
    protected function getProject()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }
    protected function getBranchType()
    {
        $object = BranchType::with(['getBranches','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }
    protected function getDepartment()
    {
        $object = department::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }

    protected function getDesignation()
    {
        $object = Designation::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }

    protected function getRole()
    {
        $object = Role::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('company_id',$this->user->company_id);
    }

    protected function getCompany()
    {
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where('id',$this->user->company_id);
    }

    protected function getBloodGroup()
    {
        return BloodGroup::class;
    }
}
