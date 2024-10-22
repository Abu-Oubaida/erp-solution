<?php

namespace App\Traits;

use App\Models\BloodGroup;
use App\Models\branch;
use App\Models\BranchType;
use App\Models\company_info;
use App\Models\CompanyModulePermission;
use App\Models\department;
use App\Models\Designation;
use App\Models\Fixed_asset;
use App\Models\fixed_asset_specifications;
use App\Models\Op_reference_type;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompanyPermission;
use App\Models\userProjectPermission;
use Illuminate\Database\Eloquent\Builder;

trait ParentTraitCompanyWise
{
    use AuthTrait;
    protected function getUser($operation_permission_name)
    {
        $object = User::with(['permissions','department','designation','branch','getCompany','companyPermissions']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    protected function getUserAll()
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

    protected function getCompanyModulePermissionWise($operation_permission_name)
    {
        $permission = permission::where('name',$operation_permission_name)->first();
        $companyModulePermission = CompanyModulePermission::whereIn('company_id',$this->getUserCompanyPermissionsArray())->where('module_id',$permission->id)->pluck('company_id')->unique()->toArray();

        $userCompanyPermission = UserCompanyPermission::where('user_id',$this->user->id)->get();
        $companyPermissionIDs = [];
        foreach ($userCompanyPermission as $value)
        {
            if ($value->users->where('id',$this->user->id)->first()->companyWiseRoles()->first()->name == 'superadmin')
            {
                $companyPermissionIDs[] = $value->company_id;
            }
        }
        if ($this->user->isSuperAdmin())
        {
            $companyPermissionIDs[] = $this->user->company;
        }
        $otherCompanyPermissionIDs = PermissionUser::whereIn('company_id',$companyModulePermission)->where('user_id',$this->user->id)->where('permission_name',$operation_permission_name)->pluck('company_id')->unique()->toArray();
        $companyPermissionIDs = array_merge($companyPermissionIDs,$otherCompanyPermissionIDs);
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('id',$this->getUserCompanyPermissionsArray())->whereIn('id',$companyPermissionIDs);
    }

    protected function getCompanyModulePermissionWiseArray($operation_permission_name)
    {
        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
//        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
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
    protected function companyWisePermissionUsers($company_id)
    {
        $userIDs = $this->companyWisePermissionUserArray($company_id);
        return $this->getUser()->whereIn('id',$userIDs)->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'systemsuperadmin');
//                ->orWhere('name', 'systemadmin'); // Add other roles if needed
        });
    }
    protected function companyWisePermissionUserArray($company_id): array
    {
        $userCompanyPermissions = userCompanyPermission::where('company_id', $company_id)->pluck('user_id')->unique()->toArray();
        $users = $this->getUser()->where('company',$company_id)->pluck('id')->unique()->toArray();
        return array_merge($userCompanyPermissions,$users);
    }
    protected function getUserProjectPermissions($user_id)
    {
        $object = branch::with(['getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray())->whereIn('id',$this->userProjectPermissionArray($user_id));
    }
    private function userProjectPermissionArray($user_id)
    {
        return userProjectPermission::where('user_id',$user_id)->get()->pluck('project_id')->unique()->toArray();
    }
    private function getFixedAsset()
    {
        $object = Fixed_asset::with(['company','specifications','createdBy','updatedBy']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    private function getFixedAssetSpecification()
    {
        $object = fixed_asset_specifications::with(['company','fixed_asset','createdBy','createdBy']);
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
        $userDefaultCompanyID = $this->getUserAll()->where('id',$user_id)->first('company');
        $userComPerIDs[] = (integer)$userDefaultCompanyID->company;
        return $userComPerIDs;
    }
    protected function getBloodGroup()
    {
        return BloodGroup::all();
    }

    protected function getOperationReferenceType()
    {
        $object = Op_reference_type::with(['createdBy','updatedBy','company','fixedAssetOpening']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    protected function getFixedAssets()
    {
        $object = Fixed_asset::with(['company','specifications','createdBy','updatedBy']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
}
