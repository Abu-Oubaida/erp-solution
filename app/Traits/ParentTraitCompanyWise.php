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
use App\Models\Fixed_asset_transfer;
use App\Models\Fixed_asset_transfer_with_spec;
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
    public function getSelfInfo()
    {
        return User::with(['permissions','department','designation','branch','getCompany','companyPermissions','roles'])->where('id',$this->user->id)->first();
    }
    public function getUser($operation_permission_name)
    {
        $object = User::with(['permissions','department','designation','branch','getCompany','companyPermissions'])->where('status',1);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getUserAll()
    {
        $object = User::with(['permissions','department','designation','branch','getCompany']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getUserCompanyPermissionsArray());
    }
    public function getBranch($operation_permission_name)
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getProject()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    public function getBranchType($operation_permission_name)
    {
        $object = BranchType::with(['getBranches','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getDepartment($operation_permission_name)
    {
        $object = department::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    public function getDesignation($operation_permission_name)
    {
        $object = Designation::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    public function getRole($operation_permission_name)
    {
        $object = Role::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name))->where('id','>=',$this->user->roles()->first()->id);
    }

    public function getCompanyModulePermissionWise($operation_permission_name)
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

    public function getCompanyModulePermissionWiseArray($operation_permission_name)
    {
        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
//        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
    }
    public function getCompany()
    {
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('id',$this->getUserCompanyPermissionsArray());
    }
    public function getUserCompanyPermissionsArray()
    {
        $userComPerIDs = [];
        $userCompanyPermissions = UserCompanyPermission::where('user_id',$this->user->id)->get();
        if (count($userCompanyPermissions) > 0) {
            $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
        }
        $userComPerIDs[] = (integer)$this->user->company_id;
        return $userComPerIDs;
    }
    public function companyWisePermissionUsers($company_id,$operation_permission_name)
    {
        $userIDs = $this->companyWisePermissionUserArray($company_id,$operation_permission_name);
        return $this->getUser($operation_permission_name)->whereIn('id',$userIDs)->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'systemsuperadmin');
//                ->orWhere('name', 'systemadmin'); // Add other roles if needed
        });
    }
    public function companyWisePermissionUserArray($company_id,$operation_permission_name): array
    {
        $userCompanyPermissions = userCompanyPermission::where('company_id', $company_id)->pluck('user_id')->unique()->toArray();
        $users = $this->getUser($operation_permission_name)->where('company',$company_id)->pluck('id')->unique()->toArray();
        return array_merge($userCompanyPermissions,$users);
    }
    public function getUserProjectPermissions($user_id,$permission)
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
    public function userProjectPermissionArray($user_id)
    {
        return userProjectPermission::where('user_id',$user_id)->get()->pluck('project_id')->unique()->toArray();
    }
    public function getFixedAssets($operation_permission_name)
    {
        $object = Fixed_asset::with(['company','specifications','createdBy','updatedBy','withRefUses']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetSpecification($operation_permission_name)
    {
        $object = fixed_asset_specifications::with(['company','fixed_asset','createdBy','createdBy','fixedWithRefData']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetWithRefData($operation_permission_name)
    {
        $object = Fixed_asset_opening_balance::with(['withSpecifications','attestedDocuments','branch','createdBy','updatedBy','refType','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetWithRefSpecification($operation_permission_name)
    {
        $object = Fixed_asset_opening_with_spec::with(['asset','fixed_asset_opening_balance','specification','createdBy','updatedBy','company']);
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
    public function getBloodGroup()
    {
        return BloodGroup::all();
    }

    public function getOperationReferenceType()
    {
        $object = Op_reference_type::with(['createdBy','updatedBy','company','fixedAssetOpening']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }

    public function getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$project_id,$company_id): Builder
    {
        return Fixed_asset_opening_with_spec::with(['fixed_asset_opening_balance','specification','asset'])->whereHas('fixed_asset_opening_balance', function ($query) use ($company_id, $project_id) {
            $query->where('company_id', $company_id)
                ->where('branch_id', $project_id)
                ->where('status',1);
        });
    }
    public function getProjectWiseTransferMaterialsStock($operation_permission_name,$to_project_id,$to_company_id): Builder
    {
        return Fixed_asset_transfer_with_spec::with(['fixed_asset_transfer','specification','asset'])->whereHas('fixed_asset_transfer', function ($query) use ($to_company_id, $to_project_id) {
            $query->where('to_company_id', $to_company_id)
                ->where('to_company_id', $to_project_id)
                ->where('status','<=',1);
        });
    }
    public function getProjectWiseTransferMaterialsOut($operation_permission_name,$from_project_id,$from_company_id): Builder
    {
        return Fixed_asset_transfer_with_spec::with(['fixed_asset_transfer','specification','asset'])->whereHas('fixed_asset_transfer', function ($query) use ($from_company_id, $from_project_id) {
            $query->where('from_company_id', $from_company_id)
                ->where('from_company_id', $from_project_id)
                ->where('status','<=',1);
        });
    }

    public function getFixedAssetStockMaterials($operation_permission_name,$project_id,$company_id)
    {
        $fixed_asset_id = [];
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$project_id,$company_id)->pluck('asset_id')->unique()->toArray();
        $transfer = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$project_id,$company_id)->pluck('asset_id')->unique()->toArray();
        $fixed_asset_id = array_merge($withRef,$transfer);
        // $issue_return--------
        // $mrf or $mpr---------
        return $fixed_asset_id;
    }
    public function getFixedAssetStockMaterialSpecifications($operation_permission_name,$materials_id,$project_id,$company_id)
    {
        $fixed_asset_specification_id = [];
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->pluck('spec_id')->unique()->toArray();
        $transfer = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->pluck('spec_id')->unique()->toArray();
        $fixed_asset_specification_id = array_merge($withRef,$transfer);
        // $issue_return--------
        // $mrf or $mpr---------
        return $fixed_asset_specification_id;
    }
    public function getFixedAssetSpecificationWiseStockBalance($operation_permission_name,$specification_id,$materials_id,$project_id,$company_id)
    {
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->where('spec_id',$specification_id)->sum('qty');
        $transferIn = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->sum('qty');
        $transferOut = $this->getProjectWiseTransferMaterialsOut($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->sum('qty');
        $transferStock = ($transferIn - $transferOut);
        // $issue_return--------
        // $mrf or $mpr---------
        return (float) ($withRef+$transferStock);
    }

    public function getFixedAssetSpecificationWiseStockRate($operation_permission_name,$specification_id,$materials_id,$project_id,$company_id)
    {
        $count = 0;
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->where('spec_id',$specification_id)->avg('rate');
        if ($withRef > 0)
        {
            $count++;
        }
        $transferIn = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$project_id,$company_id)->where('asset_id',$materials_id)->avg('rate');
        if ($transferIn > 0)
        {
            $count++;
        }
        return (float) (($withRef+$transferIn)/$count);
    }
    public function getFixedAssetGpAll($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('to_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name))->orWhereIn('from_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetGpDataIn($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object->whereIn('to_company_id',$this->getCompany()->pluck('id')->unique()->toArray());
        }
        return $object->whereIn('to_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetGpDataOut($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object->whereIn('from_company_id',$this->getCompany()->pluck('id')->unique()->toArray());
        }
        return $object->whereIn('from_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetGpSpecificationAll($operation_permission_name)
    {
        return Fixed_asset_transfer_with_spec::with(['specification','asset','fixed_asset_transfer','createdBy','updatedBy']);
    }
}
