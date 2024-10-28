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
use App\Models\Fixed_asset_opening_balance;
use App\Models\Fixed_asset_opening_with_spec;
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
    use AuthTrait, PermissionTrait;
    protected function getSelfInfo()
    {
        return User::with(['permissions','department','designation','branch','getCompany','companyPermissions','roles'])->where('id',$this->user->id)->first();
    }
    protected function getUser($operation_permission_name)
    {
        $object = User::with(['permissions','department','designation','branch','getCompany','companyPermissions'])->where('status',1);
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
    protected function getBranch($operation_permission_name)
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
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
    protected function getBranchType($operation_permission_name)
    {
        $object = BranchType::with(['getBranches','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    protected function getDepartment($operation_permission_name)
    {
        $object = department::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    protected function getDesignation($operation_permission_name)
    {
        $object = Designation::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    protected function getRole($operation_permission_name)
    {
        $object = Role::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name))->where('id','>=',$this->user->roles()->first()->id);
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
    protected function companyWisePermissionUsers($company_id,$operation_permission_name)
    {
        $userIDs = $this->companyWisePermissionUserArray($company_id,$operation_permission_name);
        return $this->getUser($operation_permission_name)->whereIn('id',$userIDs)->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'systemsuperadmin');
//                ->orWhere('name', 'systemadmin'); // Add other roles if needed
        });
    }
    protected function companyWisePermissionUserArray($company_id,$operation_permission_name): array
    {
        $userCompanyPermissions = userCompanyPermission::where('company_id', $company_id)->pluck('user_id')->unique()->toArray();
        $users = $this->getUser($operation_permission_name)->where('company',$company_id)->pluck('id')->unique()->toArray();
        return array_merge($userCompanyPermissions,$users);
    }
    protected function getUserProjectPermissions($user_id,$permission)
    {
        $object = branch::with(['getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        $user = $this->getUser($permission)->where('id',$user_id)->first();
        if ($user->companyWiseRoleName() == 'superadmin')
        {
            return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission));
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->whereIn('id',$this->userProjectPermissionArray($user_id));
    }
    private function userProjectPermissionArray($user_id)
    {
        return userProjectPermission::where('user_id',$user_id)->get()->pluck('project_id')->unique()->toArray();
    }
    protected function getFixedAssets($operation_permission_name)
    {
        $object = Fixed_asset::with(['company','specifications','createdBy','updatedBy','withRefUses']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    private function getFixedAssetSpecification($operation_permission_name)
    {
        $object = fixed_asset_specifications::with(['company','fixed_asset','createdBy','createdBy','fixedWithRefData']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
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

    protected function getFixedAssetStockMaterials($operation_permission_name,$project_id,$company_id)
    {
        $with_ref_stock = Fixed_asset_opening_balance::with(['withSpecifications','detailsUsingReference','branch','company']);
        if (!$this->user->isSystemSuperAdmin())
        {
            $with_ref_stock = $with_ref_stock->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
        }
    }
}
