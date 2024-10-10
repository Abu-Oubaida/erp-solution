<?php

namespace App\Traits;

use App\Models\BloodGroup;
use App\Models\branch;
use App\Models\BranchType;
use App\Models\company_info;
use App\Models\department;
use App\Models\Designation;
use App\Models\Fixed_asset;
use App\Models\fixed_asset_specifications;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompanyPermission;
use Illuminate\Database\Eloquent\Builder;

trait ParentTraitCompanyWise
{
    use AuthTrait;
    protected function getUser()
    {
        $object = User::with(['permissions','department','designation','branch','getCompany']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getUserCompanyPermissionsArray());
    }
    protected function getBranch()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    protected function getProject()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    protected function getBranchType()
    {
        $object = BranchType::with(['getBranches','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    protected function getDepartment()
    {
        $object = department::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }

    protected function getDesignation()
    {
        $object = Designation::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }

    protected function getRole()
    {
        $object = Role::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray())->where('id','>=',$this->user->roles()->first()->id);
    }

    protected function getCompany()
    {
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('id',$this->getUserCompanyPermissionsArray());
    }
    private function getUserCompanyPermissionsArray()
    {
        $userComPerIDs = [];
        $userCompanyPermissions = UserCompanyPermission::where('user_id',$this->user->id)->get();
        if (count($userCompanyPermissions) > 0) {
            $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
        }
        $userComPerIDs[] = (integer)$this->user->company_id;
        return $userComPerIDs;
    }
    private function getFixedAsset()
    {
        $object = Fixed_asset::with(['company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    private function getFixedAssetSpecification()
    {
        $object = fixed_asset_specifications::with(['fixed_asset','createdBy','createdBy']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    public function getUserCompanyPermissionArray($user_id)
    {
        $userComPerIDs = [];
        $userCompanyPermissions = UserCompanyPermission::where('user_id',$user_id)->get();
        if (count($userCompanyPermissions) > 0) {
            $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
        }
        $userDefaultCompanyID = $this->getUser()->where('id',$user_id)->first('company');
        $userComPerIDs[] = (integer)$userDefaultCompanyID->company;
        return $userComPerIDs;
    }
    protected function getBloodGroup()
    {
        return BloodGroup::all();
    }
}
